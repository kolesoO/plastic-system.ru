<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="block_wrapper">
    <div class="order_form-item-wrap">
        <div class="order_form-price">
            <span>Итого к оплате: </span>
            <b><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></b>
            <small>c НДС</small>
        </div>
    </div>
    <div class="order_form-item-wrap">
        <button class="form_button color col-xs-24" onclick="BX.saleOrderAjax.submitForm('Y');">Оформить заказ</button>
    </div>
</div>