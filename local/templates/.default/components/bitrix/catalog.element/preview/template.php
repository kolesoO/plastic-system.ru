<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);

$APPLICATION->IncludeComponent(
    "bitrix:catalog.item",
    $arResult["INNER_TEMPLATE"],
    [
        "RESULT" => [
            "ITEM" => $arResult,
            "OFFER_KEY" => $arResult["OFFER_ID_SELECTED"],
            "OFFERS_LIST" => $arResult["OFFERS"],
            "WRAP_ID" => "catalog-item-".$arResult["ID"],
            "AREA_ID" => $this->GetEditAreaId($arResult["ID"])
        ],
        "PARAMS" => array_merge($arResult["ORIGINAL_PARAMETERS"], [
            "PRICES" => $arResult["PRICES"],
            "COMPARE" => [
                'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
                'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
                'COMPARE_PATH' => $arParams['COMPARE_PATH']
            ],
            "COMPARE_NAME" => $arParams['COMPARE_NAME'],
            "FAVORITE_ITEM" => isset($arParams["FAVORITE_ITEM"]) ? $arParams["FAVORITE_ITEM"] : "N"
        ])
    ],
    $component,
    ['HIDE_ICONS' => 'Y']
);
