<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::registerAutoLoadClasses(null, [
    "\kDevelop\Parser\General" => "/local/php_interface/classes/parser/general.php",
]);

$rsParser = new \kDevelop\Parser\General();

/*$rsParser->load([
    "https://box-plastic.ru/yandex_market.xml?hash_tag=0167e647be3754ab39969b504963f3cd&sales_notes=&product_ids=&group_ids=&label_ids=&exclude_fields=&html_description=0&yandex_cpa=&process_presence_sure=",
    "https://box-plastic.ru/google_merchant_center.xml?hash_tag=380f902cfe476df4fddb6d86a2fae026&product_ids=&group_ids=&label_ids="
]);*/

$rsParser->update();