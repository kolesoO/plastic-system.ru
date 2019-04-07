<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/**
 * @global array $arParams
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global string $cartId
 */
$compositeStub = (isset($arResult['COMPOSITE_STUB']) && $arResult['COMPOSITE_STUB'] == 'Y');
?>
<a href="<?=($arResult['NUM_PRODUCTS'] > 0 ? $arParams['PATH_TO_BASKET'] : "#")?>">
    <i class="icon basket">
        <?if ($arResult['NUM_PRODUCTS'] > 0) :?>
            <span class="icon-inner"><?=$arResult['NUM_PRODUCTS']?></span>
        <?endif?>
    </i>
</a>