<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("keywords", "");
$APPLICATION->SetPageProperty("description", "");
$APPLICATION->SetPageProperty("title", "Избранное - Складские лотки и пластиковая тара от компании ООО «Пластик Система»");
$APPLICATION->SetTitle("Избранное");

$APPLICATION->IncludeComponent(
    "bitrix:catalog.section",
    "search",
    array()
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');