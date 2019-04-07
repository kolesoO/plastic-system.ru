<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(empty($arResult)) return "";

$strReturn = '';
$count = count($arResult);
if ($count > 0) {
    $strReturn .= '<div class="breadcrumbs">';
    foreach ($arResult as $key => $arItem) {
        $strReturn .= '<div class="breadcrumbs-item">';
        if ($key < $count - 1) {
            $strReturn .= '
                <a href="'.$arItem["LINK"].'" class="link">'.$arItem["TITLE"].'</a>
                <i class="icon arrow-right"></i>
            ';
        } else {
            $strReturn .= '<span>'.$arItem["TITLE"].'</span>';
        }
        $strReturn .= '</div>';
    }
    $strReturn .= '</div>';
}

return $strReturn;