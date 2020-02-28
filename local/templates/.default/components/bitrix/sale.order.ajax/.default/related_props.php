<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @global $USER */
?>

<?foreach ($arResult["ORDER_PROP"]["RELATED"] as $arProp) :
    //name
    $name = $arProp["NAME"].($arProp["REQUIRED"] == "Y" ? "*" : "");
    //end
    ?>
    <div class="order_form-item">
        <div class="order_form-title">
            <div class="title-3 text"><?=$arProp["NAME"]?></div>
        </div>
        <div class="order_form-item-wrap order_form-item-input" flex-align="center">
            <?if ($arProp["CODE"] == "ADDRESS") :
                $arValue = explode(",", $arProp["VALUE"]);
                ?>
                <div class="col-lg-6 col-md-9 animate_input js-animate_input">
                    <label for="city">Город</label>
                    <input
                            id="city"
                            type="text"
                            name="<?=$arProp["FIELD_NAME"]?>_PSEUDO[]"
                            data-type="<?=\Kladr\ObjectType::City?>"
                            data-target="#<?=$arProp["FIELD_ID"]?>"
                            onkeyup="BX.saleOrderAjax.getAddress(this)"
                            value="<?=$arValue[0]?>"
                    >
                </div>
                <div class="col-lg-6 col-md-9 animate_input js-animate_input">
                    <label for="street">Улица</label>
                    <input
                            id="street"
                            type="text"
                            name="<?=$arProp["FIELD_NAME"]?>_PSEUDO[]"
                            data-type="<?=\Kladr\ObjectType::Street?>"
                            data-target="#<?=$arProp["FIELD_ID"]?>"
                            onkeyup="BX.saleOrderAjax.getAddress(this, 'city')"
                            value="<?=$arValue[1]?>"
                    >
                </div>
                <div class="col-lg-3 col-md-9 animate_input js-animate_input">
                    <label for="house">Дом</label>
                    <input
                            id="house"
                            type="text"
                            name="<?=$arProp["FIELD_NAME"]?>_PSEUDO[]"
                            data-type="<?=\Kladr\ObjectType::Building?>"
                            data-target="#<?=$arProp["FIELD_ID"]?>"
                            value="<?=$arValue[2]?>"
                    >
                </div>
                <div class="col-lg-3 col-md-9 animate_input js-animate_input">
                    <label for="korp">Корпус</label>
                    <input
                            id="korp"
                            type="text"
                            name="<?=$arProp["FIELD_NAME"]?>_PSEUDO[]"
                            data-target="#<?=$arProp["FIELD_ID"]?>"
                            value="<?=$arValue[3]?>"
                    >
                </div>
                <input
                        id="<?=$arProp["FIELD_ID"]?>"
                        type="hidden"
                        name="<?=$arProp["FIELD_NAME"]?>"
                        value="<?=$arProp["VALUE"]?>"
                        onchange="BX.saleOrderAjax.submitForm()"
                >
            <?else:?>
                <div class="col-lg-6 col-md-9">
                    <label for="<?=$arProp["FIELD_ID"]?>"><?=$name?></label>
                    <input id="<?=$arProp["FIELD_ID"]?>" type="text" name="<?=$arProp["FIELD_NAME"]?>">
                </div>
            <?endif?>
        </div>
        <?if ($arProp["CODE"] == "ADDRESS" && $USER->IsAuthorized()) :?>
            <div class="order_form-item-wrap" flex-align="center">
                <div class="col-lg-6 col-md-9">
                    <button class="form_button" onclick="obAjax.getUserAddressList('address-list', event)">Добавить из адресов</button>
                </div>
            </div>
            <div id="address-list" class="order_form-item-wrap order_address"></div>
        <?endif?>
    </div>
<?endforeach?>
