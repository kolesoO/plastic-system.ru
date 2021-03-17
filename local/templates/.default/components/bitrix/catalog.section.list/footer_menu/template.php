<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$count = 0;
foreach($arResult["SECTIONS"] as $arSection)
{
    if($arSection == reset($arResult["SECTIONS"]) || $count == 10 || $count == 20)
        echo '<div class="footer__menu-item">';

    echo '<a href="'.$arSection["SECTION_PAGE_URL"].'">'.$arSection["NAME"].'</a>';

    $count ++;

    if($count == 10 || $count == 20 || $arSection == end($arResult["SECTIONS"]))
        echo '</div>';
}