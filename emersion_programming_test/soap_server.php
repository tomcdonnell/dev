<?php
/*
 * vim: ts=3 sw=3 et wrap co=100 go-=b
 */

require_once 'move_index_in_array.php';

ini_set('soap.wsdl_cache_enabled', '0');

$server = new SoapServer('interface.wsdl');
$server->addFunction('moveIndexInArray');
$server->handle();
?>
