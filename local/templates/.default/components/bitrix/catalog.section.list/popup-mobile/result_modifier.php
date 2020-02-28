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
$this->setFrameMode(true);

$hasResize = is_array($arParams["IMAGE_SIZE"]);
$lastParentKey = 0;
$arResult["SECTION_COUNT"] = count($arResult["SECTIONS"]);

foreach ($arResult["SECTIONS"] as $key => &$arSection) {
    //ресайз изображений
    if ($hasResize && is_array($arSection["PICTURE"])) {
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
    //end
    if ($arResult["SECTIONS"][$lastParentKey]["DEPTH_LEVEL"] < $arSection["DEPTH_LEVEL"] ) {
        $arResult["SECTIONS"][$lastParentKey]["SUB_SECTIONS"][] = $arSection;
        unset($arResult["SECTIONS"][$key]);
    } else {
        $lastParentKey = $key;
    }
}
unset($arSection);

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["SECTION_COUNT"]);
}