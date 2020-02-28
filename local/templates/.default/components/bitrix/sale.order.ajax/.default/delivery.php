<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$curDelivery = null;
?>

<?if ($arResult["DELIVERY_COUNT"] > 0) :?>
    <div class="order_form-item">
        <div class="order_form-title">
            <div class="title-3 text">Способ доставки</div>
        </div>
        <div flex-align="start" class="order_form-item-input">
            <?foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery):
                if ($arDelivery["CHECKED"] == 'Y') {
                    $curDelivery = $arDelivery;
                }
                ?>
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
    <?if (is_array($curDelivery['STORE']) && count($curDelivery['STORE']) > 0) :
        $curStore = $arResult['STORE_LIST'][STORE_ID];
        ?>
        <div class="order_form-item">
            <div class="order_form-title">
                <div class="title-3 text">Склад самовывоза</div>
            </div>
            <div flex-align="start" class="order_form-item-input">
                <div class="col-lg-24 col-md-24">
                    <select
                            class="col-lg-9 col-md-9 col-xs-24"
                            onchange="BX.saleOrderAjax.setStore(this)"
                    >
                        <?foreach ($curDelivery['STORE'] as $storeId) :
                            $store = $arResult['STORE_LIST'][$storeId];
                            ?>
                            <option
                                    value="<?=$store['ID']?>"
                                    <?if ($store['ID'] == $curStore['ID']) :?>selected<?endif?>
                            ><?=$store['TITLE']?></option>
                        <?endforeach;?>
                    </select>
                </div>
                <div class="col-lg-24 col-md-24">
                    <?if (strlen($curStore['ADDRESS']) > 0) :?>
                        <b>Адрес: </b><?=$curStore['ADDRESS']?>
                    <?endif;?>
                    <?if (strlen($curStore['SCHEDULE']) > 0) :?>
                        <br><b>Расписание: </b><?=$curStore['SCHEDULE']?>
                    <?endif;?>
                </div>
            </div>
        </div>
    <?endif;?>
<?endif?>
