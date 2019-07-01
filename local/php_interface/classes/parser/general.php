<?php
namespace kDevelop\Parser;

class General
{
    private $dbFilePath = __DIR__."/database.csv";

    private $uploadDir = "/upload/parser";

    private $implodeSymbol = "||";

    public function __construct()
    {

    }

    public function load(array $arFilePath)
    {
        $arCache = [];
        $fHandle = fopen($this->dbFilePath, "w");
        foreach ($arFilePath as $filePath) {
            $rsXml = simplexml_load_string(file_get_contents($filePath));
            foreach ($rsXml->shop->offers as $offers) {
                foreach ($offers as $offer) {
                    $offer->name = (string)$offer->name;
                    if (in_array($offer->name, $arCache)) {
                        echo "continue<br>";
                        continue;
                    }
                    fputcsv($fHandle, [
                        $offer->name,
                        implode($this->implodeSymbol, (array)$offer->picture)
                    ]);
                    $arCache[] = $offer->name;
                }
            }
        }
        fclose($fHandle);
    }

    public function update()
    {
        global $DB;

        $fHandle = fopen($this->dbFilePath, "r");
        while (($data = fgetcsv($fHandle, 1000)) !== false) {
            $arImg = explode($this->implodeSymbol, $data[1]);
            if (count($arImg) > 0 && strlen($arImg[0]) > 0) {
                $rsQuery = $DB->Query("select * from b_iblock_element where name='".$data[0]."'");
                if ($arResultQuery = $rsQuery->fetch()) {
                    $arImgToSave = [
                        "PREVIEW_PICTURE" => $this->loadFile($arImg[0]),
                        "DETAIL_PICTURE" => $this->loadFile($arImg[0])
                    ];
                    foreach ($arImg as $key => $file) {
                        if ($key < 1) continue;
                        $arImgToSave["MORE_PHOTO"][] = [
                            "VALUE" => $this->loadFile($file, false),
                            "DESCRIPTION" => "test"
                        ];
                    }
                    $DB->Update(
                        "b_iblock_element",
                        [
                            "PREVIEW_PICTURE" => $arImgToSave["PREVIEW_PICTURE"],
                            "DETAIL_PICTURE" => $arImgToSave["DETAIL_PICTURE"]
                        ],
                        "WHERE ID='".$arResultQuery["ID"]."'"
                    );
                    if (is_array($arImgToSave["MORE_PHOTO"]) && count($arImgToSave["MORE_PHOTO"]) > 0) {
                        echo "update MORE_PHOTO<br>";
                        \CIBlockElement::SetPropertyValuesEx($arResultQuery["ID"], $arResultQuery["IBLOCK_ID"], ["MORE_PHOTO" => $arImgToSave["MORE_PHOTO"]]);
                    }
                    echo "updated - ".$arResultQuery["ID"]."<br>";
                }
            }
        }
        fclose($fHandle);
    }

    /**
     * @param $url
     * @param bool $needSave
     * @return bool|null
     */
    private function loadFile($url, $needSave = true)
    {
        if (is_string($url) && strlen($url) > 0) {
            $return = null;
            $arUrlName = explode("/", $url);
            $resultFileName = $_SERVER["DOCUMENT_ROOT"].$this->uploadDir."/".$arUrlName[count($arUrlName) - 1];
            copy($url, $resultFileName);
            if ($needSave) {
                $return = \CFile::SaveFile(\CFile::MakeFileArray($resultFileName), "iblock");
                unlink($resultFileName);
            } else {
                $return = \CFile::MakeFileArray($resultFileName);
            }
            return $return;
        }

        return false;
    }
}