<?php

include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');

/* Load objects */
$cart = new Cart(intval($cookie->id_cart));
$address = new Address(intval($cart->id_address_delivery));
$countryObj = new Country(intval($address->id_country));
$customer = new Customer(intval($cart->id_customer));
$currency = new Currency(intval($cart->id_currency));
$lang = new Language(intval($cookie->id_lang));

$mbParams = array();

/* MoneyBookers countries (for iso 3 letters compatibility) */
$country = array();
$country['AF'] = 'AFG';
$country['AL'] = 'ALB';
$country['DZ'] = 'DZA';
$country['AS'] = 'ASM';
$country['AD'] = 'AND';
$country['AO'] = 'AGO';
$country['AI'] = 'AIA';
$country['AQ'] = 'ATA';
$country['AG'] = 'ATG';
$country['AR'] = 'ARG';
$country['AM'] = 'ARM';
$country['AW'] = 'ABW';
$country['AU'] = 'AUS';
$country['AT'] = 'AUT';
$country['AZ'] = 'AZE';
$country['BS'] = 'BHS';
$country['BH'] = 'BHR';
$country['BD'] = 'BGD';
$country['BB'] = 'BRB';
$country['BY'] = 'BLR';
$country['BE'] = 'BEL';
$country['BZ'] = 'BLZ';
$country['BJ'] = 'BEN';
$country['BM'] = 'BMU';
$country['BT'] = 'BTN';
$country['BO'] = 'BOL';
$country['BA'] = 'BIH';
$country['BW'] = 'BWA';
$country['BV'] = 'BVT';
$country['BR'] = 'BRA';
$country['IO'] = 'IOT';
$country['BN'] = 'BRN';
$country['BG'] = 'BGR';
$country['BF'] = 'BFA';
$country['BI'] = 'BDI';
$country['KH'] = 'KHM';
$country['CM'] = 'CMR';
$country['CA'] = 'CAN';
$country['CV'] = 'CPV';
$country['KY'] = 'CYM';
$country['CF'] = 'CAF';
$country['TD'] = 'TCD';
$country['CL'] = 'CHL';
$country['CN'] = 'CHN';
$country['CX'] = 'CXR';
$country['CC'] = 'CCK';
$country['CO'] = 'COL';
$country['KM'] = 'COM';
$country['CG'] = 'COG';
$country['CK'] = 'COK';
$country['CR'] = 'CRI';
$country['CI'] = 'CIV';
$country['HR'] = 'HRV';
$country['CU'] = 'CUB';
$country['CY'] = 'CYP';
$country['CZ'] = 'CZE';
$country['DK'] = 'DNK';
$country['DJ'] = 'DJI';
$country['DM'] = 'DMA';
$country['DO'] = 'DOM';
$country['TP'] = 'TMP';
$country['EC'] = 'ECU';
$country['EG'] = 'EGY';
$country['SV'] = 'SLV';
$country['GQ'] = 'GNQ';
$country['ER'] = 'ERI';
$country['EE'] = 'EST';
$country['ET'] = 'ETH';
$country['FK'] = 'FLK';
$country['FO'] = 'FRO';
$country['FJ'] = 'FJI';
$country['FI'] = 'FIN';
$country['FR'] = 'FRA';
$country['FX'] = 'FXX';
$country['GF'] = 'GUF';
$country['PF'] = 'PYF';
$country['TF'] = 'ATF';
$country['GA'] = 'GAB';
$country['GM'] = 'GMB';
$country['GE'] = 'GEO';
$country['DE'] = 'DEU';
$country['GH'] = 'GHA';
$country['GI'] = 'GIB';
$country['GR'] = 'GRC';
$country['GL'] = 'GRL';
$country['GD'] = 'GRD';
$country['GP'] = 'GLP';
$country['GU'] = 'GUM';
$country['GT'] = 'GTM';
$country['GN'] = 'GIN';
$country['GW'] = 'GNB';
$country['GY'] = 'GUY';
$country['HT'] = 'HTI';
$country['HM'] = 'HMD';
$country['HN'] = 'HND';
$country['HK'] = 'HKG';
$country['HU'] = 'HUN';
$country['IS'] = 'ISL';
$country['IN'] = 'IND';
$country['ID'] = 'IDN';
$country['IR'] = 'IRN';
$country['IQ'] = 'IRQ';
$country['IE'] = 'IRL';
$country['IL'] = 'ISR';
$country['IT'] = 'ITA';
$country['JM'] = 'JAM';
$country['JP'] = 'JPN';
$country['JO'] = 'JOR';
$country['KZ'] = 'KAZ';
$country['KE'] = 'KEN';
$country['KI'] = 'KIR';
$country['KP'] = 'PRK';
$country['KR'] = 'KOR';
$country['KW'] = 'KWT';
$country['KG'] = 'KGZ';
$country['LA'] = 'LAO';
$country['LV'] = 'LVA';
$country['LB'] = 'LBN';
$country['LS'] = 'LSO';
$country['LR'] = 'LBR';
$country['LY'] = 'LBY';
$country['LI'] = 'LIE';
$country['LT'] = 'LTU';
$country['LU'] = 'LUX';
$country['MO'] = 'MAC';
$country['MK'] = 'MKD';
$country['MG'] = 'MDG';
$country['MW'] = 'MWI';
$country['MY'] = 'MYS';
$country['MV'] = 'MDV';
$country['ML'] = 'MLI';
$country['MT'] = 'MLT';
$country['MH'] = 'MHL';
$country['MQ'] = 'MTQ';
$country['MR'] = 'MRT';
$country['MU'] = 'MUS';
$country['YT'] = 'MYT';
$country['MX'] = 'MEX';
$country['FM'] = 'FSM';
$country['MD'] = 'MDA';
$country['MC'] = 'MCO';
$country['MN'] = 'MNG';
$country['MS'] = 'MSR';
$country['MA'] = 'MAR';
$country['MZ'] = 'MOZ';
$country['MM'] = 'MMR';
$country['NA'] = 'NAM';
$country['NR'] = 'NRU';
$country['NP'] = 'NPL';
$country['NL'] = 'NLD';
$country['AN'] = 'ANT';
$country['NC'] = 'NCL';
$country['NZ'] = 'NZL';
$country['NI'] = 'NIC';
$country['NE'] = 'NER';
$country['NG'] = 'NGA';
$country['NU'] = 'NIU';
$country['NF'] = 'NFK';
$country['MP'] = 'MNP';
$country['NO'] = 'NOR';
$country['OM'] = 'OMN';
$country['PK'] = 'PAK';
$country['PW'] = 'PLW';
$country['PA'] = 'PAN';
$country['PG'] = 'PNG';
$country['PY'] = 'PRY';
$country['PE'] = 'PER';
$country['PH'] = 'PHL';
$country['PN'] = 'PCN';
$country['PL'] = 'POL';
$country['PT'] = 'PRT';
$country['PR'] = 'PRI';
$country['QA'] = 'QAT';
$country['RE'] = 'REU';
$country['RO'] = 'ROM';
$country['RU'] = 'RUS';
$country['RW'] = 'RWA';
$country['KN'] = 'KNA';
$country['LC'] = 'LCA';
$country['VC'] = 'VCT';
$country['WS'] = 'WSM';
$country['SM'] = 'SMR';
$country['ST'] = 'STP';
$country['SA'] = 'SAU';
$country['SN'] = 'SEN';
$country['SC'] = 'SYC';
$country['SL'] = 'SLE';
$country['SG'] = 'SGP';
$country['SK'] = 'SVK';
$country['SI'] = 'SVN';
$country['SB'] = 'SLB';
$country['SO'] = 'SOM';
$country['ZA'] = 'ZAF';
$country['GS'] = 'SGS';
$country['ES'] = 'ESP';
$country['LK'] = 'LKA';
$country['SH'] = 'SHN';
$country['PM'] = 'SPM';
$country['SD'] = 'SDN';
$country['SR'] = 'SUR';
$country['SJ'] = 'SJM';
$country['SZ'] = 'SWZ';
$country['SE'] = 'SWE';
$country['CH'] = 'CHE';
$country['SY'] = 'SYR';
$country['TW'] = 'TWN';
$country['TJ'] = 'TJK';
$country['TZ'] = 'TZA';
$country['TH'] = 'THA';
$country['TG'] = 'TGO';
$country['TK'] = 'TKL';
$country['TO'] = 'TON';
$country['TT'] = 'TTO';
$country['TN'] = 'TUN';
$country['TR'] = 'TUR';
$country['TM'] = 'TKM';
$country['TC'] = 'TCA';
$country['TV'] = 'TUV';
$country['UG'] = 'UGA';
$country['UA'] = 'UKR';
$country['AE'] = 'ARE';
$country['GB'] = 'GBR';
$country['US'] = 'USA';
$country['UM'] = 'UMI';
$country['UY'] = 'URY';
$country['UZ'] = 'UZB';
$country['VU'] = 'VUT';
$country['VA'] = 'VAT';
$country['VE'] = 'VEN';
$country['VN'] = 'VNM';
$country['VG'] = 'VGB';
$country['VI'] = 'VIR';
$country['WF'] = 'WLF';
$country['EH'] = 'ESH';
$country['YE'] = 'YEM';
$country['YU'] = 'YUG';
$country['ZR'] = 'ZAR';
$country['ZM'] = 'ZMB';
$country['ZW'] = 'ZWE';

/* About the merchant */
$mbParams['pay_to_email'] = Configuration::get('MB_PAY_TO_EMAIL');
$mbParams['recipient_description'] = Configuration::get('PS_SHOP_NAME');
$mbParams['hide_login'] = intval(Configuration::get('MB_HIDE_LOGIN'));
$mbParams['id_logo'] = intval(Configuration::get('MB_ID_LOGO'));
$mbParams['id_logo_wallet'] = intval(Configuration::get('MB_ID_LOGO_WALLET'));

/* About the customer */
$mbParams['pay_from_email'] = $customer->email;
$mbParams['firstname'] = $address->firstname;
$mbParams['lastname'] = $address->lastname;
$mbParams['address'] = $address->address1;
$mbParams['address2'] = $address->address2;
$mbParams['phone_number'] = !empty($address->phone_mobile) ? $address->phone_mobile : $address->phone;
$mbParams['postal_code'] = $address->postcode;
$mbParams['city'] = $address->city;
$mbParams['country'] = $country[strtoupper($countryObj->iso_code)];
$mbParams['language'] = strtoupper($lang->iso_code);
$mbParams['date_of_birth'] = substr($customer->birthday, 5, 2).substr($customer->birthday, 8, 2).substr($customer->birthday, 0, 4);

/* About the cart */
$mbParams['transaction_id'] = intval($cart->id);
$mbParams['currency'] = $currency->iso_code;
$mbParams['amount'] = number_format($cart->getOrderTotal(), 2, '.', '');

/* URLs */
$mbParams['status_url'] = 'http://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__.'modules/moneybookers/validation.php';

/* Assign settings to Smarty template */
$smarty->assign($mbParams);

/*	<input type="hidden" name="return_url" value="{$return_url}">
<input type="hidden" name="return_url_text" value="{$return_url}">
<input type="hidden" name="cancel_url" value="{$return_url}">

<input type="hidden" name="state" value="{$state}">

<input type="hidden" name="amount2_description" value="{$amount2_description}">
<input type="hidden" name="amount2" value="{$amount2}">
<input type="hidden" name="amount3_description" value="{$amount3_description}">
<input type="hidden" name="amount3" value="{$amount3}">
<input type="hidden" name="amount4_description" value="{$amount4_description}">
<input type="hidden" name="amount4" value="{$amount4}"> */
	
$products = $cart->getProducts();
foreach ($products as $key => $product)
{
	
}

$smarty->display(dirname(__FILE__).'/moneybookers-form.tpl');

?>