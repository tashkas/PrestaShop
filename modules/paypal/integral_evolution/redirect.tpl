<html>
	<head>
		<script type="text/javascript" src="http://{$url}js/jquery/jquery-1.2.6.pack.js"></script>
	</head>
	<body>
		<p>{$redirect_text}<br /><a href="javascript:history.go(-1);">{$cancel_text}</a></p>
		<form action="{$paypal_url}" method="post" id="paypal_form" class="hidden">
			<input type="hidden" name="upload" value="1" />
			<input type="hidden" name="address_override" value="1" />
			<input type="hidden" name="showShippingAddress" value="false">
			<input type="hidden" name="showBillingAddress" value="false">
			<input type="hidden" name="first_name" value="{$shipping_address->firstname|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="last_name" value="{$shipping_address->lastname|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="address1" value="{$shipping_address->address1|escape:'htmlall':'UTF-8'}" />
			{if $shipping_address->address2 != NULL}
			<input type="hidden" name="address2" value="{$shipping_address->address2|escape:'htmlall':'UTF-8'}" />
			{/if}
			<input type="hidden" name="city" value="{$shipping_address->city|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="zip" value="{$shipping_address->postcode}" />
			<input type="hidden" name="country" value="{$shipping_country->iso_code}" />
			{if $shipping_state != NULL}
			<input type="hidden" name="state" value="{$shipping_state->iso_code}" />
			{/if}
			<input type="hidden" name="billing_first_name" value="{$billing_address->firstname|escape:'htmlall':'UTF-8'}">
			<input type="hidden" name="billing_last_name" value="{$billing_address->lastname|escape:'htmlall':'UTF-8'}">
			<input type="hidden" name="billing_address1" value="{$billing_address->address1|escape:'htmlall':'UTF-8'}">
			{if $billing_address->address2 != NULL}
			<input type="hidden" name="billing_address2" value="{$billing_address->address2|escape:'htmlall':'UTF-8'}">
			{/if}
			<input type="hidden" name="billing_city" value="{$billing_address->city|escape:'htmlall':'UTF-8'}">
			{if $billing_state != NULL}
			<input type="hidden" name="billing_state" value="{$billing_state->iso_code}">
			{/if}
			<input type="hidden" name="billing_zip" value="{$billing_address->postcode}">
			<input type="hidden" name="billing_country" value="{$billing_country->iso_code}">
			
			<input type="hidden" name="subtotal" value="{$total}" />
			<input type="hidden" name="buyer_email" value="{$customer->email}" />
			<input type="hidden" name="item_name" value="{$cart_text}" />
			<input type="hidden" name="business" value="{$business}" />
			<input type="hidden" name="cmd" value="_hosted-payment">
			<input type="hidden" name="charset" value="utf-8" />
			<input type="hidden" name="currency_code" value="{$currency_module->iso_code}" />
			<input type="hidden" name="payer_id" value="{$customer->id}" />
			<input type="hidden" name="payer_email" value="{$customer->email}" />
			<input type="hidden" name="custom" value="{$cart_id}" />
			<input type="hidden" name="return" value="http://{$url}order-confirmation.php?key={$customer->secure_key}&id_cart={$cart_id}&id_module={$paypal_id}&slowvalidation" />
			<input type="hidden" name="cancel_return" value="http://{$url}index.php" />
			<input type="hidden" name="notify_url" value="http://{$url}modules/paypal/validation.php" />
			<input type="hidden" name="paymentaction" value="{$paymentaction}" />
			{if $header != NULL}
			<input type="hidden" name="cpp_header_image" value="{$header}" />
			{/if}
			<input type="hidden" name="template" value="{$template}" />
			<input type="hidden" name="rm" value="2" />
			<input type="hidden" name="bn" value="FR_PRESTASHOP_H3S" />
			<input type="hidden" name="cbt" value="{$return_text}" />
		</form>
		<script type="text/javascript">
		{literal}
		$(document).ready(function() {
			$('#paypal_form').submit();
		});
		{/literal}
		</script>
	</body>
</html>
