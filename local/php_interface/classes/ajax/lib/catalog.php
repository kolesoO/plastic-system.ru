<?
namespace kDevelop\Ajax;

class Catalog
{
    private static $arDefIbFields = ["ID", "IBLOCK_ID", "NAME"];

    /**
     * @param array $arSort
     * @param array $arFilter
     * @param array $arFieldCode
     * @param array $arPropCode
     * @return array|bool|mixed
     */
    private static function getIblockElement(array $arSort, array $arFilter, array $arFieldCode, array $arPropCode)
    {
        if (\Bitrix\Main\Loader::includeModule("iblock")) {
            $arReturn = [];
            $rsItems = \CIblockElement::GetList(
                $arSort,
                $arFilter,
                false,
                false,
                array_unique(array_merge(self::$arDefIbFields, $arFieldCode))
            );
            while ($rsItem = $rsItems->GetNextElement()) {
                $arItem = $rsItem->getFields();
                $arProps = $rsItem->getProperties();
                foreach ($arProps as $code => $arPropItem) {
                    if (!in_array($code, $arPropCode)) continue;
                    $arItem["PROPERTIES"][$code] = $arPropItem;
                }
                $arReturn[] = $arItem;
            }
            return count($arReturn) > 1 ? $arReturn : $arReturn[0];
        }

        return false;
    }

    /**
     * @param $convertCurrency
     * @param $curId
     * @return array
     */
    private static function getCurrencyConvert($convertCurrency, $curId)
    {
        $return = [];
        if ($convertCurrency === 'Y') {
            $correct = false;
            if (\Bitrix\Main\Loader::includeModule('currency')) {
                $correct = \Bitrix\Currency\CurrencyManager::isCurrencyExist($curId);
            }
            if ($correct) {
                $return = ['CURRENCY_ID' => $curId];
            }
        }

        return $return;
    }

    /**
     * @param $arParams
     * @return array
     */
    public static function getCatalogItem($arParams)
    {
        global $APPLICATION;

        $return = "";
        if (\Bitrix\Main\Loader::includeModule("catalog")) {
            if ($arProdList = \CCatalogSKU::getProductList($arParams["offer_id"], $arParams["CATALOGS"][$arParams["PARAMS"]["IBLOCK_ID"]]["IBLOCK_ID"])) {
                $productId = $arProdList[$arParams["offer_id"]]["ID"];
                if ($arItem = self::getIblockElement(
                    [],
                    ["IBLOCK_ID" => $arProdList[$arParams["offer_id"]]["IBLOCK_ID"], "ID" => $productId],
                    ["*"],
                    $arParams["PARAMS"]["PROPERTY_CODE"]
                )) {
                    //данные по ценам
                    if (is_array($arParams["PARAMS"]["PRICE_CODE"]) && count($arParams["PARAMS"]["PRICE_CODE"]) > 0) {
                        $arItem["PRICES"] = \CIBlockPriceTools::GetCatalogPrices(false, $arParams["PARAMS"]["PRICE_CODE"]);
                    }
                    //end
                    if ($arItem["OFFERS"] = self::getIblockElement(
                        [$arParams["PARAMS"]["OFFERS_SORT_FIELD"] => $arParams["PARAMS"]["OFFERS_SORT_ORDER"]],
                        ["IBLOCK_ID" => $arProdList[$arParams["offer_id"]]["OFFER_IBLOCK_ID"], "PROPERTY_".$arProdList[$arParams["offer_id"]]["SKU_PROPERTY_ID"] => $productId],
                        $arParams["PARAMS"]["OFFERS_FIELD_CODE"],
                        $arParams["PARAMS"]["OFFERS_PROPERTY_CODE"]
                    )) {
                        foreach ($arItem["OFFERS"] as $key => &$arOffer) {
                            if ($arOffer["ID"] == $arParams["offer_id"]) {
                                $arItem["OFFER_KEY"] = $key;
                            }
                            $arOffer["PRICES"] = \CIBlockPriceTools::GetItemPrices(
                                $arOffer["IBLOCK_ID"],
                                $arItem["PRICES"],
                                $arOffer,
                                $arParams["PARAMS"]['PRICE_VAT_INCLUDE'],
                                self::getCurrencyConvert($arParams["PARAMS"]["CONVERT_CURRENCY"], $arParams["PARAMS"]["CURRENCY_ID"])
                            );
                        }
                        unset($arOffer);
                        ob_start();
                        $APPLICATION->IncludeComponent(
                            "bitrix:catalog.item",
                            ".default",
                            [
                                "RESULT" => [
                                    "ITEM" => $arItem,
                                    "OFFER_KEY" => $arItem["OFFER_KEY"],
                                    "OFFERS_LIST" => $arItem["OFFERS"],
                                    "WRAP_ID" => $arParams["target_id"]
                                ],
                                "PARAMS" => $arParams["PARAMS"],
                                "PRICES" => $arItem["PRICES"]
                            ],
                            null,
                            ['HIDE_ICONS' => 'Y']
                        );
                        $return = ob_get_contents();
                        ob_end_clean();
                    }
                }
            }
        }

        return ["js_callback" => "getCatalogItemCallBack", "html" => $return];
    }

    /**
     * @param $arParams
     * @return array
     */
    public static function getCatalogCalcItems($arParams)
    {
        global $APPLICATION;
        global $arCatalogTopFilter;

        $return = "";
        if (isset($arParams["AJAX_TEMPLATE"]) && strlen($arParams["AJAX_TEMPLATE"]) > 0) {
            $arCatalogTopFilter = (is_array($arParams["FILTER_VALUES"]) ? $arParams["FILTER_VALUES"] : []);
            $arParams["FILTER_NAME"] = "arCatalogTopFilter";
            ob_start();
            $APPLICATION->IncludeComponent("bitrix:catalog.top", $arParams["AJAX_TEMPLATE"], $arParams);
            $return = ob_get_contents();
            ob_end_clean();
        }


        return ["html" => $return];
    }
}