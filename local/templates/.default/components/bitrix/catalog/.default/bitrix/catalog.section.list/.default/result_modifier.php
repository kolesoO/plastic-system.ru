<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use kDevelop\Service\MultiSite;

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

$this->setFrameMode(true);

$arResult["SECTION_COUNT"] = count($arResult["SECTIONS"]);
if (is_array($arParams["IMAGE_SIZE"])) {
    //кеширование изображений
    foreach ($arResult["SECTIONS"] as &$arSection) {
        if (is_array($arSection["PICTURE"])) {
            $thumb = \CFile::ResizeImageGet(
                $arSection["PICTURE"],
                ["width" => $arParams["IMAGE_SIZE"]["WIDTH"], "height" => $arParams["IMAGE_SIZE"]["HEIGHT"]],
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
            if ($thumb["src"]) {
                $arSection["PICTURE"]["SAFE_SRC"] = $thumb["src"];
            }
        }
    }
    unset($arSection);
    //end
}

foreach ($arResult["SECTIONS"] as $key => $section) {
    $arResult["SECTIONS"][$key]["UF_SHORT_DESCR"] = MultiSite::valueOrDefault(
        $section['UF_SHORT_DESCR_' . strtoupper(SITE_ID)],
        $section['UF_SHORT_DESCR']
    );
}

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["SECTION_COUNT"]);
}
