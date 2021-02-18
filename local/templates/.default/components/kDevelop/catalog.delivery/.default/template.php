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

<div class="def_form">
    <h2 class="title-3">Доставка</h2>
    <p>Нет доступных служб доставок</p>
    <a href="/shipping-and-payment/" class="link" target="_blank">
        <span>Подробнее о доставке и оплате</span>
        <i class="icon arrow-right"></i>
    </a>
</div>

<!--form id="get_delivery-form" class="def_form" onsubmit="obAjax.getDelivery(this, event)">
    <input id="address" type="hidden" name="address" value="<?=$arParams["ADDRESS"]?>">
    <h2 class="title-3">Доставка</h2>
    <div class="animate_input js-animate_input">
        <label for="city">Город</label>
        <input
                id="city"
                type="text"
                name="city"
                value="<?=$arParams["LOCATION_NAME"]?>"
                data-type="city"
                data-target="#address"
                onkeyup="catalogDelivery.getAddress(this)"
        >
    </div>
    <div class="animate_input js-animate_input">
        <label for="street">Улица</label>
        <input
                id="street"
                type="text"
                name="street"
                value="<?=$arParams["LOCATION_STREET"]?>"
                data-type="street"
                data-target="#address"
                onkeyup="catalogDelivery.getAddress(this, 'city')"
        >
    </div>
    <div class="animate_input js-animate_input">
        <label for="building">Дом</label>
        <input
                id="building"
                type="text"
                name="building"
                value="<?=$arParams["LOCATION_BUILDING"]?>"
                data-type="street"
                data-target="#address"
                onkeyup="catalogDelivery.updateAddress(this)"
        >
    </div>
    <button type="submit" class="form_button">Рассчитать</button>
    <br>
    <?if ($arResult['DELIVERY']) :?>
        <div class="title-3 text"><?=$arResult['DELIVERY']['PRICE']?></div>
    <?else:?>
        <p>Нет доступных служб доставок<br><small>(возможно вы не корректно ввели название города)</small></p>
    <?endif?>
    <br>
    <a href="/shipping-and-payment/" class="link" target="_blank">
        <span>Подробнее о доставке и оплате</span>
        <i class="icon arrow-right"></i>
    </a>
</form-->
