<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("keywords", "");
$APPLICATION->SetPageProperty("description", "");
$APPLICATION->SetPageProperty("header_section-class", "section");
$APPLICATION->SetTitle("Карта сайта");

$APPLICATION->IncludeComponent("bitrix:main.map", ".default", Array(
        "LEVEL"	=>	"3",
        "COL_NUM"	=>	"1",
        "SHOW_DESCRIPTION"	=>	"Y",
        "SET_TITLE"	=>	"N",
        "CACHE_TIME"	=>	"36000000"
    )
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');