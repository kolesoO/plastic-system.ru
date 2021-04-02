<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult))
{
    echo '<div class="footer__menu-item">';
        foreach($arResult as $arItem)
        {
            if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1){ continue; }

            echo '<a '.(($arItem["SELECTED"]) ? ' class="active"' : '').' href="'.$arItem["LINK"].'">'.$arItem["TEXT"].'</a>';
        }
    echo '</div>';
}?>