<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<form id="get_delivery-form" class="def_form" onsubmit="obAjax.getDelivery(this, event)">
    <div class="title-3">Доставка</div>
    <div class="animate_input js-animate_input">
        <label for="city">Город</label>
        <input id="city" type="text" name="city" value="<?=$arParams["LOCATION_NAME"]?>">
    </div>
    <div class="animate_input js-animate_input">
        <label for="address">Адрес</label>
        <input id="address" type="text" name="address" value="<?=$arParams["LOCATION_ADDRESS"]?>">
    </div>
    <button type="submit" class="form_button">Рассчитать</button>
    <br>
    <?if (count($arResult["ITEMS"]) > 0) :?>
        <?
        $counter = 0;
        foreach ($arResult["ITEMS"] as $key => $arItem) :
            if ($arItem["HAS_STORE"] == "Y" && !is_array($arItem["STORE_ITEMS"])) continue;
            ?>
            <?if ($counter > 0) :?>
                <br>
            <?endif?>
            <div><b><?=$arItem["NAME"]?></b></div>
            <?if ($arItem["PRICE"] > 0) :?>
                <div class="title-3 text"><?=SaleFormatCurrency($arItem["PRICE"], $arItem["CURRENCY"])?></div>
            <?endif?>
            <?if ($arItem["PERIOD_TO"] > 0) :?>
                <div><?=$arItem["PERIOD_FROM"]." - ".$arItem["PERIOD_TO"]?></div>
            <?endif?>
            <?if (is_array($arItem["STORE_ITEMS"])) :?>
                <?foreach ($arItem["STORE_ITEMS"] as $arStore) :?>
                    <div>
                        <span><?=$arStore["TITLE"]?></span>
                        <?if (strlen($arStore["ADDRESS"]) > 0) :?>
                            <br>
                            <small>(Адрес - <?=$arStore["ADDRESS"]?>)</small>
                        <?endif?>
                    </div>
                <?endforeach?>
            <?endif?>
            <?
            $counter ++;
        endforeach?>
    <?else:?>
        <p>Нет доступных служб доставок<br><small>(возможно вы не корректно ввели название города)</small></p>
    <?endif?>
    <br>
    <a href="/shipping-and-payment/" class="link" target="_blank">
        <span>Подробнее о доставке и оплате</span>
        <i class="icon arrow-right"></i>
    </a>
</form>