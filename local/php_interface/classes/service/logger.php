<?php

namespace kDevelop\Service;

class Logger
{
    private static $fileDir = "/upload/log";

    /**
     * @param $fileName
     */
    public static function setFile($fileName)
    {
        self::$fileDir .= "/".$fileName;
    }

    /**
     *
     */
    private static function prepareDir()
    {
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . dirname(self::$fileDir))) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . dirname(self::$fileDir), 0755, true);
        }
    }

    /**
     *
     */
    public static function saveCatalogPictures()
    {
        self::prepareDir();
        $fileHandle = fopen($_SERVER["DOCUMENT_ROOT"].self::$fileDir, "a");
        $rsItem = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOG],
            false,
            false,
            ["IBLOCK_ID", "ID", "XML_ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE"]
        );
        while ($arItem = $rsItem->GetNext()) {
            fwrite($fileHandle, $arItem["XML_ID"].";".$arItem["PREVIEW_PICTURE"].";".$arItem["DETAIL_PICTURE"]."\n");
        }
        fclose($fileHandle);
    }
}