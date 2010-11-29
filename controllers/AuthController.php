<?php

class AuthControllerCore extends FrontController
{
	public function __construct()
	{
		$this->ssl = true;
	
		parent::__construct();
	}
	public function preProcess()
	{
		parent::preProcess();
		
		if ($this->cookie->isLogged())
			Tools::redirect('my-account.php');
		
		if (Tools::getValue('create_account'))
		{
			$create_account = 1;
			$this->smarty->assign('email_create', 1);
		}

		if (Tools::isSubmit('SubmitCreate'))
		{
			if (!Validate::isEmail($email = Tools::getValue('email_create')))
				$this->errors[] = Tools::displayError('invalid e-mail address');
			elseif (Customer::customerExists($email))
				$this->errors[] = Tools::displayError('someone has already registered with this e-mail address');	
			else
			{
				$create_account = 1;
				$this->smarty->assign('email_create', Tools::safeOutput($email));
				$_POST['email'] = $email;
			}
		}

		if (Tools::isSubmit('submitAccount'))
		{
			$create_account = 1;
			$this->smarty->assign('email_create', 1);
			$validateDni = Validate::isDni(Tools::getValue('dni'));

			if (!Validate::isEmail($email = Tools::getValue('email')))
				$this->errors[] = Tools::displayError('e-mail not valid');
			elseif (Customer::customerExists($email))
				$this->errors[] = Tools::displayError('someone has already registered with this e-mail address');
			if (!Validate::isPasswd(Tools::getValue('passwd')))
				$this->errors[] = Tools::displayError('invalid password');
			if (!Tools::getValue('phone') AND !Tools::getValue('phone_mobile'))
				$this->errors[] = Tools::displayError('You must register at least one phone number');
			$zip_code_format = Country::getZipCodeFormat((int)(Tools::getValue('id_country')));
			if (Country::getNeedZipCode((int)(Tools::getValue('id_country'))))
			{
				if (($postcode = Tools::getValue('postcode')) AND $zip_code_format)
				{
					$zip_regexp = '/^'.$zip_code_format.'$/ui';
					$zip_regexp = str_replace(' ', '( |)', $zip_regexp);
					$zip_regexp = str_replace('-', '(-|)', $zip_regexp);
					$zip_regexp = str_replace('N', '[0-9]', $zip_regexp);
					$zip_regexp = str_replace('L', '[a-zA-Z]', $zip_regexp);
					$zip_regexp = str_replace('C', Country::getIsoById((int)(Tools::getValue('id_country'))), $zip_regexp);
					if (!preg_match($zip_regexp, $postcode))
						$this->errors[] = Tools::displayError('Your postal code/zip code is incorrect.');
				}
				elseif ($zip_code_format)
					$this->errors[] = Tools::displayError('postcode is required.');
				elseif ($postcode AND !preg_match('/^[0-9a-zA-Z -]{4,9}$/ui', $postcode))
					$this->errors[] = Tools::displayError('Your postal code/zip code is incorrect.');
			}
			if (Tools::getValue('dni') != NULL AND $validateDni != 1)
			{
				$error = array(
				0 => Tools::displayError('DNI isn\'t valid'),
				-1 => Tools::displayError('this DNI has been already used'),
				-2 => Tools::displayError('NIF isn\'t valid'),
				-3 => Tools::displayError('CIF isn\'t valid'),
				-4 => Tools::displayError('NIE isn\'t valid')
				);
				$this->errors[] = $error[$validateDni];
			}
			if (!@checkdate(Tools::getValue('months'), Tools::getValue('days'), Tools::getValue('years')) AND !(Tools::getValue('months') == '' AND Tools::getValue('days') == '' AND Tools::getValue('years') == ''))
				$this->errors[] = Tools::displayError('invalid birthday');
			if (!sizeof($this->errors))
			{
				$customer = new Customer();
				if (Tools::isSubmit('newsletter'))
				{
					$customer->ip_registration_newsletter = pSQL(Tools::getRemoteAddr());
					$customer->newsletter_date_add = pSQL(date('Y-m-d H:i:s'));
				}

				$customer->birthday = (empty($_POST['years']) ? '' : (int)($_POST['years']).'-'.(int)($_POST['months']).'-'.(int)($_POST['days']));

				/* Customer and address, same fields, caching data */
				$addrLastname = isset($_POST['lastname']) ? $_POST['lastname'] : $_POST['customer_lastname'];
				$addrFirstname = isset( $_POST['firstname']) ?  $_POST['firstname'] : $_POST['customer_firstname'];
				$_POST['lastname'] = $_POST['customer_lastname'];
				$_POST['firstname'] = $_POST['customer_firstname'];
				$this->errors = $customer->validateControler();
				$_POST['lastname'] = $addrLastname;
				$_POST['firstname'] = $addrFirstname;
				$address = new Address();
				$address->id_customer = 1;
				$this->errors = array_unique(array_merge($this->errors, $address->validateControler()));
				if (!sizeof($this->errors))
				{
					if (!$country = new Country($address->id_country, Configuration::get('PS_LANG_DEFAULT')) OR !Validate::isLoadedObject($country))
						die(Tools::displayError());
					if ((int)($country->contains_states) AND !(int)($address->id_state))
						$this->errors[] = Tools::displayError('this country require a state selection');
					else
					{
						$customer->active = 1;
						if (!$customer->add())
							$this->errors[] = Tools::displayError('an error occurred while creating your account');
						else
						{
							$address->id_customer = (int)($customer->id);
							if (!$address->add())
								$this->errors[] = Tools::displayError('an error occurred while creating your address');
							else
							{
								if (!Mail::Send((int)($this->cookie->id_lang), 'account', Mail::l('Welcome!'),
								array('{firstname}' => $customer->firstname, '{lastname}' => $customer->lastname, '{email}' => $customer->email, '{passwd}' => Tools::getValue('passwd')), $customer->email, $customer->firstname.' '.$customer->lastname))
									$this->errors[] = Tools::displayError('cannot send email');
								$this->smarty->assign('confirmation', 1);
								$this->cookie->id_customer = (int)($customer->id);
								$this->cookie->customer_lastname = $customer->lastname;
								$this->cookie->customer_firstname = $customer->firstname;
								$this->cookie->passwd = $customer->passwd;
								$this->cookie->logged = 1;
								$this->cookie->email = $customer->email;
								/* Update cart address */
								$this->cart->id_address_delivery = Address::getFirstCustomerAddressId((int)($customer->id));
								$this->cart->id_address_invoice = Address::getFirstCustomerAddressId((int)($customer->id));
								$this->cart->update();
								Module::hookExec('createAccount', array(
									'_POST' => $_POST,
									'newCustomer' => $customer
								));
								if ($back)
									Tools::redirect($back);
								Tools::redirect('my-account.php');
							}
						}
					}
				}
			}
		}

		if (Tools::isSubmit('SubmitLogin'))
		{
			$passwd = trim(Tools::getValue('passwd'));
			$email = trim(Tools::getValue('email'));
			if (empty($email))
				$this->errors[] = Tools::displayError('e-mail address is required');
			elseif (!Validate::isEmail($email))
				$this->errors[] = Tools::displayError('invalid e-mail address');
			elseif (empty($passwd))
				$this->errors[] = Tools::displayError('password is required');
			elseif (Tools::strlen($passwd) > 32)
				$this->errors[] = Tools::displayError('password is too long');
			elseif (!Validate::isPasswd($passwd))
				$this->errors[] = Tools::displayError('invalid password');
			else
			{
				$customer = new Customer();
				$authentication = $customer->getByEmail(trim($email), trim($passwd));
				/* Handle brute force attacks */
				sleep(1);
				if (!$authentication OR !$customer->id)
					$this->errors[] = Tools::displayError('authentication failed');
				else
				{
					$this->cookie->id_customer = (int)($customer->id);
					$this->cookie->customer_lastname = $customer->lastname;
					$this->cookie->customer_firstname = $customer->firstname;
					$this->cookie->logged = 1;
					$this->cookie->passwd = $customer->passwd;
					$this->cookie->email = $customer->email;
					if (Configuration::get('PS_CART_FOLLOWING') AND (empty($this->cookie->id_cart) OR Cart::getNbProducts($this->cookie->id_cart) == 0))
						$this->cookie->id_cart = (int)(Cart::lastNoneOrderedCart((int)($customer->id)));
					/* Update cart address */
					$this->cart->id_address_delivery = Address::getFirstCustomerAddressId((int)($customer->id));
					$this->cart->id_address_invoice = Address::getFirstCustomerAddressId((int)($customer->id));
					$this->cart->update();
					Module::hookExec('authentication');
					if ($back = Tools::getValue('back'))
						Tools::redirect($back);
					Tools::redirect('my-account.php');
				}
			}
		}

		if (isset($create_account))
		{
			/* Generate years, months and days */
			if (isset($_POST['years']) AND is_numeric($_POST['years']))
				$selectedYears = (int)($_POST['years']);
			$years = Tools::dateYears();
			if (isset($_POST['months']) AND is_numeric($_POST['months']))
				$selectedMonths = (int)($_POST['months']);
			$months = Tools::dateMonths();

			if (isset($_POST['days']) AND is_numeric($_POST['days']))
				$selectedDays = (int)($_POST['days']);
			$days = Tools::dateDays();

			/* Select the most appropriate country */
			if (isset($_POST['id_country']) AND is_numeric($_POST['id_country']))
				$selectedCountry = (int)($_POST['id_country']);
			elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
			{
				$array = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
				if (Validate::isLanguageIsoCode($array[0]))
				{
					$selectedCountry = Country::getByIso($array[0]);
					if (!$selectedCountry)
						$selectedCountry = (int)(Configuration::get('PS_COUNTRY_DEFAULT'));
				}
			}
			if (!isset($selectedCountry))
				$selectedCountry = (int)(Configuration::get('PS_COUNTRY_DEFAULT'));
			$countries = Country::getCountries((int)($this->cookie->id_lang), true);

			$this->smarty->assign(array(
				'years' => $years,
				'sl_year' => (isset($selectedYears) ? $selectedYears : 0),
				'months' => $months,
				'sl_month' => (isset($selectedMonths) ? $selectedMonths : 0),
				'days' => $days,
				'sl_day' => (isset($selectedDays) ? $selectedDays : 0),
				'countries' => $countries,
				'sl_country' => (isset($selectedCountry) ? $selectedCountry : 0),
				'vat_management' => Configuration::get('VATNUMBER_MANAGEMENT')
			));

			/* Call a hook to display more information on form */
			$this->smarty->assign(array('HOOK_CREATE_ACCOUNT_FORM' => Module::hookExec('createAccountForm'),
																					'HOOK_CREATE_ACCOUNT_TOP' => Module::hookExec('createAccountTop')
																));
		}
	}
	
	public function setMedia()
	{
		parent::setMedia();
		Tools::addCSS(_THEME_CSS_DIR_.'authentication.css');
		Tools::addJS(array(_THEME_JS_DIR_.'tools/statesManagement.js', _PS_JS_DIR_.'jquery/jquery-typewatch.pack.js'));
	}
	
	public function process()
	{
		parent::process();
		
		$this->smarty->assign('opc', (int)(Configuration::get('PS_ORDER_PROCESS_TYPE')));
		
		$back = Tools::getValue('back');
		$key = Tools::safeOutput(Tools::getValue('key'));
		if (!empty($key))
			$back .= (strpos($back, '?') !== false ? '&' : '?').'key='.$key;
		if (!empty($back))
			$this->smarty->assign('back', Tools::safeOutput($back));
	}
	
	public function displayContent()
	{
		parent::displayContent();
		$this->smarty->display(_PS_THEME_DIR_.'authentication.tpl');
	}
}

?>
