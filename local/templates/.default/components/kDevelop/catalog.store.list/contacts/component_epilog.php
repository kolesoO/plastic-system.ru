<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if ($arResult["STORES_COUNT"] > 0) {
    global $rsAsset;
    $rsAsset->addString('<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&onload=onLoadedYandexMap" type="text/javascript" async></script>');
    $rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/modules/ymap/script.js');
}