<?php
define('CURRENCY', 'SGD');
define('RETURN_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/action/checkout.php');
define('CANCEL_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/index.php');
define('LOGO_URL', 'http://i.imgur.com/yQhPF2K.png');

define('ITEM_IDENTIFIER', 'item');
define('ITEM_ID_IDENTIFIER', 'item_id');
define('GST_AMOUNT', 0.07);

define('SET_METHOD', 'SetExpressCheckout');
define('DO_METHOD', 'DoExpressCheckoutPayment');

define('ACK_SUCCESS', 'SUCCESS');
define('ACK_SUCCESS_WITH_WARNING', 'SUCCESSWITHWARNING');
define('RESPONSE_COMPLETED', 'COMPLETED');
define('RESPONSE_PENDING', 'PENDING');
?>