<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

//стилизация html для полей
foreach ($arResult["QUESTIONS"] as $FIELD_SID => &$arQuestion) {
    $name = "form_".$arQuestion["STRUCTURE"][0]["FIELD_TYPE"]."_".$arQuestion["STRUCTURE"][0]["ID"]; //name
    $attrs = "";
    if (isset($_REQUEST[$name]) && strlen($_REQUEST[$name]) > 0) {
        $arQuestion["VALUE"] = $_REQUEST[$name]; //value
    }
    if (isset($arParams["FIELD_VALUES"][$FIELD_SID])) {
        $arQuestion["VALUE"] = $arParams["FIELD_VALUES"][$FIELD_SID];
    }
    if ($arQuestion["REQUIRED"] == "Y") {
        $arQuestion["CAPTION"].= "*";
        $attrs .= " required";
    }
    if ($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] != "hidden") {
        $class = "";
        if (array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])) {
            $class .= " error";
        }
        if ($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "file") {
            $class .= " link dashed";
            $arQuestion["HTML_CODE"] = '
                <div class="type_file">
                    <input id="file-'.$arQuestion["STRUCTURE"][0]["ID"].'" type="file" name="form_file_'.$arQuestion["STRUCTURE"][0]["ID"].'" class="hidden-lg hidden-md hidden-xs">
                    <label for="file-'.$arQuestion["STRUCTURE"][0]["ID"].'" class="'.$class.'">'.$arQuestion["CAPTION"].'</label>
                </div>
            ';
        } else {
            //type
            if ($FIELD_SID == "phone") {
                $type = "tel";
            } else {
                $type = $arQuestion["STRUCTURE"][0]["FIELD_TYPE"];
            }
            //end

            //id
            $id = $FIELD_SID."_".$arQuestion["STRUCTURE"][0]["ID"];
            //end

            if ($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "textarea") {
                $arQuestion["HTML_CODE"] = '<textarea id="'.$id.'" type="'.$type.'" name="'.$name.'" class="'.$class.'" '.$attrs.'>'.$arQuestion["VALUE"].'</textarea>';
            } else {
                $arQuestion["HTML_CODE"] = '<input id="'.$id.'" type="'.$type.'" name="'.$name.'" value="'.$arQuestion["VALUE"].'" class="'.$class.'" '.$attrs.'>';
            }

            $arQuestion["HAS_ANIMATE"] = "Y";
        }
    } else {
        $arQuestion["HTML_CODE"] = '<input type="hidden" name="'.$name.'" value="'.$arQuestion["VALUE"].'"'.$attrs.'>';
    }
}
unset($arQuestion);
//end