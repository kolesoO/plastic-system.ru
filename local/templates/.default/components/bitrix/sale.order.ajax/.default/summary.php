<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="block_wrapper" flex-align="center" flex-text_align="space-between">
    <div>
        <?if ($arResult["DELIVERY_PRICE"] > 0) :?>
            <p>Стоимость доставки: <?=$arResult["DELIVERY_PRICE_FORMATED"]?></p>
        <?endif;?>
        <div class="order_form-price">
            <span>Итого к оплате: </span>
            <b><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></b>
            <small>c НДС</small>
        </div>
    </div>
    <div flex-align="start" flex-text_align="flex-end" class="col-lg-9 col-md-12">
        <button class="form_button color col-lg-9 col-md-12" onclick="BX.saleOrderAjax.submitForm('Y');">Оформить заказ</button>
    </div>
</div>