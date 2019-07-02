<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::registerAutoLoadClasses(null, [
    "\kDevelop\Parser\General" => "/local/php_interface/classes/parser/general.php",
]);

$rsParser = new \kDevelop\Parser\General();

$rsParser->load([
    $_SERVER["DOCUMENT_ROOT"]."/upload/export.yml"
]);

$rsParser->update();