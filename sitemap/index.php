<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("keywords", "");
$APPLICATION->SetPageProperty("description", "");
$APPLICATION->SetPageProperty("header_section-class", "section");
$APPLICATION->SetTitle("Карта сайта");

$APPLICATION->IncludeComponent("bitrix:main.map","",Array(
        "LEVEL" => "3",
        "COL_NUM" => "1",
        "SHOW_DESCRIPTION" => "Y",
        "SET_TITLE" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600"
    )
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');