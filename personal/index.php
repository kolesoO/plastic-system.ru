<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!$USER->isAuthorized()) {
    LocalRedirect("/", true, 301);
} else {
    LocalRedirect("/personal/profile/", true, 301);
}