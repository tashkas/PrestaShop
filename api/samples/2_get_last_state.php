<?php

////////////////////////
// get the last state //
////////////////////////

echo '<h3>Get the last state</h3>';

$xpaths = $resources->xpath('/p:prestashop/p:states/p:state[last()]');
$entity = $xpaths[0]->attributes($namespaces['xlink']);
try
{
	$xml = $ws->get(array('url' => (string)$entity['href']));
	$object = $xml->children()->state;
	$fields = $object->children();
	displayResource($fields);
}
catch (PrestaShopWebserviceException $e)
{
	displayException($e);
}
