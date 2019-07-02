<?php
namespace kDevelop\Service;

class Catalog
{
    public static function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == IBLOCK_CATALOG_CATALOG) {
            self::updateProperty(
                ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOG, "PROP_CODE" => "TSVET"],
                ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOGSKU, "PROP_CODE" => "TSVET"],
                $arFields
            );
        }
    }

    private static function updateProperty($arStartData, $arEndData, $arFields)
    {
        if (
            !isset($arStartData["IBLOCK_ID"]) || !isset($arStartData["PROP_CODE"]) ||
            !isset($arEndData["IBLOCK_ID"]) || !isset($arEndData["PROP_CODE"])
        ) return;
        if ($arProperty = \CIBlockProperty::GetByID($arStartData["PROP_CODE"], IBLOCK_CATALOG_CATALOG)->fetch()) {
            $methodName = "updateProperty".strtoupper($arProperty["PROPERTY_TYPE"]);
            if (method_exists(self::class, $methodName)) {
                self::$methodName($arStartData, $arEndData, $arFields);
            }
        }
    }

    private static function updatePropertyL($arStartData, $arEndData, $arFields)
    {
        ;
    }
}