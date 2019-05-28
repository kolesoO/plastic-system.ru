<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arParams
 * @var array $templateData
 */

include_once $_SERVER["DOCUMENT_ROOT"]."/local/php_interface/classes/ajax/msgHandBook.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/local/php_interface/classes/ajax/lib/favorite.php";

// check compared state
if ($arParams['DISPLAY_COMPARE']) :?>
    <script>
        if (typeof window.catalogElement == "function") {
            obCatalogElement_<?=$arResult["OFFER"]["ID"]?>.initCompare(<?=array_key_exists($arResult["OFFER"]['ID'], $_SESSION[$arParams['COMPARE_NAME']][$arParams['IBLOCK_ID']]['ITEMS']) ? "true" : "false"?>);
        }
    </script>
<?endif;
//end

// check favorite
?>
    <script>
        if (typeof window.catalogElement == "function") {
            obCatalogElement_<?=$arResult["OFFER"]["ID"]?>.initFavorite(<?=\kDevelop\Ajax\Favorite::isAdded($arResult["ITEM"]["ID"]) ? "true" : "false"?>);
        }
    </script>
<?
//end