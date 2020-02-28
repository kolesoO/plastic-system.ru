<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
?>

<div class="block_wrapper">
    <div class="bx-sbb-empty-cart-text"><?=Loc::getMessage("SBB_EMPTY_BASKET_TITLE")?></div>
    <?
    if (!empty($arParams['EMPTY_BASKET_HINT_PATH']))
    {
        ?>
        <div class="bx-sbb-empty-cart-desc">
            <?=Loc::getMessage(
                'SBB_EMPTY_BASKET_HINT',
                [
                    '#A1#' => '<a href="'.$arParams['EMPTY_BASKET_HINT_PATH'].'" class="link">',
                    '#A2#' => '</a>',
                ]
            )?>
        </div>
        <?
    }
    ?>
</div>