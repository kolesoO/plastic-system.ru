<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult))
{
    $count = 0;

    foreach($arResult as $arItem)
    {
        if($arItem == reset($arResult) || $count == 10 || $count == 20)
            echo '<div class="footer__menu-item">';

        // if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1){ continue; }

        echo '<a '.(($arItem["SELECTED"]) ? ' class="active"' : '').' href="'.$arItem["LINK"].'">'.$arItem["TEXT"].'</a>';

        $count ++;

        if($count == 10 || $count == 20 || $arItem == end($arResult))
            echo '</div>';
    }
}?>