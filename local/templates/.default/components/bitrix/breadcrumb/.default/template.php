<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(empty($arResult)) return "";

$strReturn = '';
$count = count($arResult);
if ($count > 0) {
    $strReturn .= '<div class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">';
    foreach ($arResult as $key => $arItem) {
        $strReturn .= '<div class="breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        if ($key < $count - 1) {
            $strReturn .= '
                <a href="'.$arItem["LINK"].'" class="link" itemscope itemtype="https://schema.org/Thing" itemprop="item">
                    <span itemprop="name">'.$arItem["TITLE"].'</span>
                    <meta itemprop="id" content="'.$key.'">
                </a>
                <i class="icon arrow-right"></i>
            ';
        } else {
            $strReturn .= '<span itemprop="name">'.$arItem["TITLE"].'</span>';
        }
        $strReturn .= '<meta itemprop="position" content="'.($key + 1).'" />';
        $strReturn .= '</div>';
    }
    $strReturn .= '</div>';
}

return $strReturn;