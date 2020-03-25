<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogProductsViewedComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */
?>

<?if ($arResult["ITEMS_COUNT"] > 0) :?>
    <?foreach ($arResult["ITEMS"] as $arItem) :
        if ($arResult["SET_AREA"]) {
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        }
        $APPLICATION->IncludeComponent(
            "bitrix:catalog.item",
            $arResult["INNER_TEMPLATE"],
            [
                "RESULT" => [
                    "ITEM" => $arItem,
                    "OFFER_KEY" => $arItem["OFFER_ID_SELECTED"],
                    "OFFERS_LIST" => $arItem["OFFERS"],
                    "WRAP_ID" => "catalog-item-".$arItem["ID"],
                    "AREA_ID" => ($arResult["SET_AREA"] ? $this->GetEditAreaId($arItem["ID"]) : null),
                    "CATALOG_TOP" => "Y"
                ],
                "PARAMS" => $arResult["ORIGINAL_PARAMETERS"],
                "PRICES" => $arResult["PRICES"]
            ],
            null,
            ['HIDE_ICONS' => 'Y']
        );
        ?>
    <?endforeach?>
<?endif?>
