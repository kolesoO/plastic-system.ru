<?
use \Bitrix\Main;
use kDevelop\Help\Tools;
use kDevelop\Service\Catalog;
use kDevelop\Service\Order;
use kDevelop\Settings\Store;

$rsManager = Main\EventManager::getInstance();

//Классы
Main\Loader::registerAutoLoadClasses(null, [
    "\kDevelop\Help\Hload" => "/local/php_interface/classes/help/hload.php",
    "\kDevelop\Help\Mobile_Detect" => "/local/php_interface/classes/help/mobile_detect.php",
    "\kDevelop\Help\Tools" => "/local/php_interface/classes/help/tools.php",
    "\kDevelop\Settings\Store" => "/local/php_interface/classes/settings/store.php",
    "\kDevelop\Service\Catalog" => "/local/php_interface/classes/service/catalog.php",
    "\kDevelop\Service\Logger" => "/local/php_interface/classes/service/logger.php",
    "\kDevelop\Service\Order" => "/local/php_interface/classes/service/order.php",
    "\kDevelop\Service\SbPolyPointer" => "/local/php_interface/classes/service/sbPolyPointer.php",
    "\kDevelop\Service\catalogProductProvider" => "/local/php_interface/classes/service/catalogProductProvider.php",
    "\kDevelop\Ajax\MsgHandBook" => "/local/php_interface/classes/ajax/msgHandBook.php",
    "\kDevelop\Ajax\Favorite" => "/local/php_interface/classes/ajax/lib/favorite.php",
]);
//end

//autoloaders
require_once $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/classes/php-yandex-geo/autoload.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/classes/kladrapi-php/kladr.php";
//end

\kDevelop\Help\Tools::definders();

//Обработчики событий
if (strpos($APPLICATION->GetCurDir(), "/bitrix/admin") === false) {
    Catalog::defineSettings();
    //main module
    $rsManager->addEventHandler("main", "OnProlog", [Tools::class, "setDeviceType"], false, 100);
    $rsManager->addEventHandler("main", "OnProlog", [Store::class, "setStore"], false, 200);
    $rsManager->addEventHandler("main", "OnProlog", [Tools::class, "defineAjax"], false, 300);
    $rsManager->addEventHandler("sale", "OnOrderNewSendEmail", [Order::class, "OnOrderNewSendEmailHandler"], false, 100);
    $rsManager->addEventHandler("sale", "onSaleDeliveryServiceCalculate", [Order::class, "onSaleDeliveryServiceCalculateHandler"], false, 100);
    //end
} else {
    //iblock module
    $rsManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", [Catalog::class, "OnAfterIBlockElementUpdateHandler"], false, 100);
    //end
}
//end
