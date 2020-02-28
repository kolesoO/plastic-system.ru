<?php
namespace kDevelop\Service;

use kDevelop\Help\Util;

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
     * @param $id
     * @return bool
     */
    protected static function isRightIblock($id)
    {
        return $id == IBLOCK_CATALOG_CATALOG;
    }

    /**
     * @param $arFields
     */
    public static function OnAfterIBlockElementUpdateHandler($arFields)
    {
        if (!self::isRightIblock($arFields["IBLOCK_ID"])) {
            return;
        }

        //перекрестная синхронизация свойств между связанными инфоблоками
        self::setItemSku($arFields["ID"], ["PROPERTY_TSVET_VALUE" => false]);
        if (count(self::$arSku) > 0) {
            self::updateProperty(
                ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOG, "PROP_CODE" => "TSVET"],
                ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOGSKU, "PROP_CODE" => "TSVET"],
                $arFields
            );
        }
        //end

        self::ListToStringProperty(
            ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOG, "PROP_CODE" => "DLINA_MM"],
            ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOG, "PROP_CODE" => "DLINA_MM_NUMBER"],
            $arFields
        );
        self::ListToStringProperty(
            ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOG, "PROP_CODE" => "SHIRINA_MM"],
            ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOG, "PROP_CODE" => "SHIRINA_MM_NUMBER"],
            $arFields
        );
        self::ListToStringProperty(
            ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOG, "PROP_CODE" => "VYSOTA_MM"],
            ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOG, "PROP_CODE" => "VYSOTA_MM_NUMBER"],
            $arFields
        );
    }

    /**
     * @param $arFields
     */
    public static function transformElementFields(&$arFields)
    {
        $arFields['CODE'] = Util::translit($arFields['NAME'], LANGUAGE_ID);
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

    /**
     * @param $arStartData
     * @param $arEndData
     * @param $arFields
     */
    public static function ListToStringProperty($arStartData, $arEndData, $arFields)
    {
        if (
            !isset($arStartData["IBLOCK_ID"]) || !isset($arStartData["PROP_CODE"]) ||
            !isset($arEndData["IBLOCK_ID"]) || !isset($arEndData["PROP_CODE"])
        ) return;

        if ($arProperty = \CIBlockProperty::GetByID($arStartData["PROP_CODE"], $arStartData["IBLOCK_ID"])->fetch()) {
            $arPropValue = $arFields["PROPERTY_VALUES"][$arProperty["ID"]];
            if ($valueInfo = \CIBlockPropertyEnum::GetByID($arPropValue[0]["VALUE"])) {
                \CIBlockElement::SetPropertyValuesEx(
                    $arFields["ID"],
                    $arFields["IBLOCK_ID"],
                    [
                        $arEndData["PROP_CODE"] => $valueInfo["VALUE"]
                    ]
                );
            }
        }
    }
}
