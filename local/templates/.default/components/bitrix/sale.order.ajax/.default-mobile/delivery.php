<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["DELIVERY_COUNT"] > 0) :?>
    <div class="order_form-item">
        <div class="order_form-title">
            <div class="title-3 text">Способ доставки</div>
        </div>
        <div flex-align="start" class="order_form-item-input">
            <?foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery): ?>
                <?if ($delivery_id > 0) :?>
                    <div class="radio_btn">
                        <input type="radio"
                               id="DELIVERY_ID_<?=$arDelivery["ID"]?>"
                               name="<?=$arDelivery["FIELD_NAME"]?>"
                               value="<?=$arDelivery["ID"]?>"
                               onchange="BX.saleOrderAjax.submitForm();"
                            <?if ($arDelivery["CHECKED"]=="Y") echo " checked";?>
                        >
                        <label for="DELIVERY_ID_<?=$arDelivery["ID"]?>"><?=$arDelivery["NAME"]?></label>
                        <?if (strlen($arDelivery["DESCRIPTION"]) > 0) :?>
                            <div class="radio_btn-desc"><?=$arDelivery["DESCRIPTION"]?></div>
                        <?endif?>
                    </div>
                <?endif?>
            <?endforeach?>
        </div>
    </div>
<?endif?>