<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

global $rsAsset;

$rsAsset->addString('<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=5a8e55ae-66ea-4959-8e40-16dc606be5c9" type="text/javascript" async></script>');
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/modules/ymap/script.js');
