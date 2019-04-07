<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["OFFERS_COUNT"] > 0) :?>
    <?foreach ($arResult["ITEM"]["OFFERS"] as $arOffer) :?>
        <a href="#" class="calculator_list-item">
            <div class="calculator_list-img">
                <img src="<?=(is_array($arOffer["PREVIEW_PICTURE"]) ? $arOffer["PREVIEW_PICTURE"]["SRC"] : SITE_TEMPLATE_PATH."/images/no-image.png")?>" alt="<?=$arOffer["NAME"]?>">
            </div>
            <div class="calculator_list-desc">
                <div><?=$arOffer["NAME"]?></div>
                <div class="calculator_list-price"><?=$arOffer["ITEM_PRICES"][0]["PRINT_PRICE"]?></div>
            </div>
        </a>
    <?endforeach?>
<?endif?>