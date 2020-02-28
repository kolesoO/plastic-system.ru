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

$arResult["ITEMS_COUNT"] = count($arResult["ITEMS"]);
$arResult["SECTIONS_COUNT"] = 0;
$arResult["JS_MAP_DATA"] = [];
$arStoreRelations = [];
$arStoreId = [];

$rsSection = \CIblockSection::GetList(
    [],
    ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"],
    false,
    ["ID", "IBLOCK_ID", "NAME", "DESCRIPTION", "CODE", "UF_STORE_ID"]
);
while($arSection = $rsSection->GetNext()) {
    if ($arSection["UF_STORE_ID"]) {
        $arStoreRelations[$arSection["CODE"]] = $arSection["UF_STORE_ID"];
        $arStoreId = array_unique(array_merge($arSection["UF_STORE_ID"]));
    }
    $arResult["SECTIONS"][$arSection["CODE"]] = $arSection;
    $arResult["SECTIONS_COUNT"] ++;
    $arResult["JS_MAP_DATA"]["delivery_region-".$arSection["CODE"]] = [
        "code" => $arSection["CODE"],
        "items" => []
    ];
    //инфо по зонам доставки
    foreach ($arResult["ITEMS"] as $arItem) {
        if ($arItem["IBLOCK_SECTION_ID"] == $arSection["ID"] && count($arItem["PROPERTIES"]["POLYGON_COORDS"]["VALUE"]) > 0) {
            $arResult["JS_MAP_DATA"]["delivery_region-".$arSection["CODE"]]["items"][] = [
                "polygon" => [
                    "color" => $arItem["PROPERTIES"]["POLYGON_COLOR"]["VALUE"],
                    "items" => $arItem["PROPERTIES"]["POLYGON_COORDS"]["VALUE"]
                ],
                "price" => $arItem["PROPERTIES"]["PRICE"]["VALUE"]
            ];
            break;
        }
    }
    //end
}

//Инфо по складам
if (count($arStoreRelations) > 0 && count($arStoreId) > 0) {
    $rsStore = CCatalogStore::GetList(
        ['TITLE' => 'ASC', 'ID' => 'ASC'],
        ["ACTIVE" => "Y", "ID" => $arStoreId, "!GPS_N" => "0", "!GPS_S" => "0"],
        false,
        false,
        ["ID", "TITLE", "ADDRESS", "PHONE", "GPS_N", "GPS_S"]
    );
    while ($arStore = $rsStore->GetNext()) {
        foreach ($arStoreRelations as $sectionCode => $arStoreId) {
            if (in_array($arStore["ID"], $arStoreId)) {
                $arResult["JS_MAP_DATA"]["delivery_region-".$sectionCode]["store"][] = $arStore;
            }
        }
    }
}
//end

//очистка данных для карты с пустыми полигонами
foreach ($arResult["JS_MAP_DATA"] as $key => $arData) {
    if (count($arData["items"]) == 0) {
        unset($arResult["JS_MAP_DATA"][$key]);
    }
}
//end

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["ITEMS_COUNT", "SECTIONS_COUNT", "JS_MAP_DATA"]);
}