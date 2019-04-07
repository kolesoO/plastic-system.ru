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

$this->setFrameMode(true);
?>

<?if ($arResult["ITEMS_COUNT"] > 0) :?>
    <section class="section">
        <div class="container">
            <div class="title-2">Акции</div>
            <div class="table_list catalog" items-count="<?=$arParams["ELEMENT_COUNT"]?>">
                <?foreach ($arResult["ITEMS"] as $arItem) :
                    if ($arResult["SET_AREA"]) {
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    }
                    ?>
                    <div id="catalog-item-<?=$arItem["ID"]?>" class="table_list-item">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:catalog.item",
                            $arResult["INNER_TEMPLATE"],
                            [
                                "RESULT" => [
                                    "ITEM" => $arItem,
                                    "OFFER_KEY" => 0,
                                    "OFFERS_LIST" => $arItem["OFFERS"],
                                    "WRAP_ID" => "catalog-item-".$arItem["ID"],
                                    "AREA_ID" => ($arResult["SET_AREA"] ? $this->GetEditAreaId($arItem["ID"]) : null)
                                ],
                                "PARAMS" => $arResult["ORIGINAL_PARAMETERS"],
                                "PRICES" => $arResult["PRICES"]
                            ],
                            null,
                            ['HIDE_ICONS' => 'Y']
                        );?>
                    </div>
                <?endforeach?>
            </div>
        </div>
    </section>
<?endif?>