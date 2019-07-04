<?
define('STOP_STATISTICS', true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::registerAutoLoadClasses(null, [
    "\kDevelop\Ajax\MsgHandBook" => "/local/php_interface/classes/ajax/msgHandBook.php",
    "\kDevelop\Ajax\General" => "/local/php_interface/classes/ajax/general.php",
    "\kDevelop\Ajax\Result" => "/local/php_interface/classes/ajax/result.php",
    "\kDevelop\Ajax\Catalog" => "/local/php_interface/classes/ajax/lib/catalog.php",
    "\kDevelop\Ajax\Basket" => "/local/php_interface/classes/ajax/lib/basket.php",
    "\kDevelop\Ajax\User" => "/local/php_interface/classes/ajax/lib/user.php",
    "\kDevelop\Ajax\Iblock" => "/local/php_interface/classes/ajax/lib/iblock.php",
    "\kDevelop\Ajax\Component" => "/local/php_interface/classes/ajax/lib/component.php",
    "\kDevelop\Ajax\Favorite" => "/local/php_interface/classes/ajax/lib/favorite.php"
]);

$rsAjax = new \kDevelop\Ajax\General($_REQUEST["class"], $_REQUEST["method"], $_REQUEST["params"]);
$rsResult = new \kDevelop\Ajax\Result("json");
$rsResult->setData($rsAjax->callMethod());
$rsResult->setError($rsAjax->getErrors());
$rsResult->output();
die();