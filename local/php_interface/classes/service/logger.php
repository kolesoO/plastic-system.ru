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
            [
                "LOGIC" => "OR",
                ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOG],
                ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOGSKU]
            ],
            false,
            false,
            ["IBLOCK_ID", "ID", "XML_ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE"]
        );
        while ($arItem = $rsItem->GetNext()) {
            fwrite($fileHandle, $arItem["IBLOCK_ID"].";".$arItem["NAME"].";".\CFile::GetPath($arItem["PREVIEW_PICTURE"]).";".\CFile::GetPath($arItem["DETAIL_PICTURE"])."\n");
        }
        fclose($fileHandle);
    }

    /**
     *
     */
    public static function loadCatalogPictures()
    {
        global $DB;
        $fileHandle = fopen($_SERVER["DOCUMENT_ROOT"].self::$fileDir, "r");
        while (($row = fgets($fileHandle)) !== false) {
            $arInfo = explode(";", $row);
            if (strlen($arInfo[1]) > 0) {
                $arInfo[2] = trim($arInfo[2]);
                if (strlen($arInfo[2]) > 0) { //preview picture path
                    $fileId = \CFile::SaveFile(\CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$arInfo[2]), "iblock");
                    if (intval($fileId) > 0) {
                        $DB->Query("update b_iblock_element set PREVIEW_PICTURE=".$fileId." where NAME='".$arInfo[1]."' and IBLOCK_ID=".$arInfo[0]);
                    }
                }
                $arInfo[3] = trim($arInfo[3]);
                if (strlen($arInfo[3]) > 0) { //detail picture path
                    $fileId = \CFile::SaveFile(\CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$arInfo[3]), "iblock");
                    if (intval($fileId) > 0) {
                        $DB->Query("update b_iblock_element set DETAIL_PICTURE=".$fileId." where NAME='".$arInfo[1]."' and IBLOCK_ID=".$arInfo[0]);
                    }
                }
            }
        }
        fclose($fileHandle);
    }
}