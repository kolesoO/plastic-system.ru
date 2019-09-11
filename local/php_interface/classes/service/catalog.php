<?php
namespace kDevelop\Service;

class Catalog
{
    /**
     * @var array
     */
    private static $arSku = [];

    /**
     * @var
     */
    private static $iblockId = IBLOCK_SERVICE_CATALOG_SETTINGS;

    /**
     * @param $arFields
     */
    public static function OnAfterIBlockElementUpdateHandler($arFields)
    {
        if ($arFields["IBLOCK_ID"] == IBLOCK_CATALOG_CATALOG) {
            self::setItemSku($arFields["ID"], ["PROPERTY_TSVET_VALUE" => false]);
            if (count(self::$arSku) > 0) {
                self::updateProperty(
                    ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOG, "PROP_CODE" => "TSVET"],
                    ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOGSKU, "PROP_CODE" => "TSVET"],
                    $arFields
                );
            }
        }
    }

    /**
     * @param $itemId
     * @param $arFilter
     */
    private static function setItemSku($itemId, $arFilter)
    {
        $arSkuInfo = \CCatalogSKU::getOffersList(
            [$itemId],
            0,
            $arFilter,
            ["ID", "IBLOCK_ID"]
        );
        if (count($arSkuInfo) > 0) {
            foreach ($arSkuInfo[$itemId] as $arSku) {
                self::$arSku[] = $arSku;
            }
        }
    }

    /**
     * @param $arStartData
     * @param $arEndData
     * @param $arFields
     */
    private static function updateProperty($arStartData, $arEndData, $arFields)
    {
        if (
            !isset($arStartData["IBLOCK_ID"]) || !isset($arStartData["PROP_CODE"]) ||
            !isset($arEndData["IBLOCK_ID"]) || !isset($arEndData["PROP_CODE"])
        ) return;
        if ($arProperty = \CIBlockProperty::GetByID($arStartData["PROP_CODE"], IBLOCK_CATALOG_CATALOG)->fetch()) {
            $methodName = "updateProperty".strtoupper($arProperty["PROPERTY_TYPE"]);
            if (method_exists(self::class, $methodName)) {
                self::$methodName($arStartData, $arEndData, $arFields["PROPERTY_VALUES"][$arProperty["ID"]]);
            }
        }
    }

    /**
     * @param $arStartData
     * @param $arEndData
     * @param $arPropValue
     */
    private static function updatePropertyL($arStartData, $arEndData, $arPropValue)
    {
        if ($arOldValue = \CIBlockPropertyEnum::GetByID($arPropValue[0]["VALUE"])) {
            if ($arNewValue = \CIBlockPropertyEnum::GetList([], ["IBLOCK_ID" => self::$arSku[0]["IBLOCK_ID"], "VALUE" => $arOldValue["VALUE"]])->fetch()) {
                foreach (self::$arSku as $arSkuInfo) {
                    \CIBlockElement::SetPropertyValuesEx(
                        $arSkuInfo["ID"],
                        $arSkuInfo["IBLOCK_ID"],
                        [
                            $arEndData["PROP_CODE"] => $arNewValue["ID"]
                        ]
                    );
                }
            }
        }
    }

    /**
     *
     */
    public static function defineSettings()
    {
        $rs = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => self::$iblockId, "CODE" => "general", "ACTIVE" => "Y"],
            false,
            false,
            ["ID", "IBLOCK_ID"]
        );
        if ($rsItem = $rs->getNextElement()) {
            $props = $rsItem->getProperties();
            foreach ($props as $code => $value) {
                if ($value["PROPERTY_TYPE"] == "S" && !defined($code)) {
                    define(strtoupper($code), $value["VALUE"]);
                }
            }
        }
    }
}