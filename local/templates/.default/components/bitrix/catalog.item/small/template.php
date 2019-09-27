<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<?if ($arResult["OFFERS_COUNT"] > 0) :?>
    <?foreach ($arResult["ITEM"]["OFFERS"] as $arOffer) :
        $arPrice = $arOffer["ITEM_PRICES"][$arOffer["ITEM_PRICE_SELECTED"]];
        ?>
        <a
                href="#"
                class="calculator_list-item"
                onclick="obCalculator.addCollection(<?=$arResult["ITEM"]["PROPERTIES"]["SHIRINA_MM"]["VALUE"]?>, <?=$arResult["ITEM"]["PROPERTIES"]["VYSOTA_MM"]["VALUE"]?>, <?=$arResult["ITEM"]["PROPERTIES"]["DLINA_MM"]["VALUE"]?>, <?=$arPrice["PRICE"]?>, <?=$arPrice["PRICE_TYPE_ID"]?>, <?=$arOffer["ID"]?>, event)"
        >
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