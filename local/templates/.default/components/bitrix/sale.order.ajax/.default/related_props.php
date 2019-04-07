<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $arProp) :?>
    <div class="order_form-item">
        <div class="order_form-title">
            <div class="title-3 text"><?=$arProp["NAME"]?></div>
        </div>
        <div class="order_form-item-wrap order_form-item-input" flex-align="center">
            <?if ($arProp["CODE"] == "ADDRESS") :?>
                <div class="col-lg-6 col-md-9 animate_input js-animate_input">
                    <label for="city">Город</label>
                    <input id="city" type="text" name="<?=$arProp["FIELD_NAME"]?>[]">
                </div>
                <div class="col-lg-6 col-md-9 animate_input js-animate_input">
                    <label for="street">Улица</label>
                    <input id="street" type="text" name="<?=$arProp["FIELD_NAME"]?>[]">
                </div>
                <div class="col-lg-3 col-md-9 animate_input js-animate_input">
                    <label for="house">Дом</label>
                    <input id="house" type="text" name="<?=$arProp["FIELD_NAME"]?>[]">
                </div>
                <div class="col-lg-3 col-md-9 animate_input js-animate_input">
                    <label for="korp">Корпус</label>
                    <input id="korp" type="text" name="<?=$arProp["FIELD_NAME"]?>[]">
                </div>
            <?else:?>
                <div class="col-lg-6 col-md-9">
                    <input type="text" name="<?=$arProp["FIELD_NAME"]?>">
                </div>
            <?endif?>
        </div>
        <div class="order_form-item-wrap" flex-align="center">
            <div class="col-lg-6 col-md-9">
                <button class="form_button">Добавить из адресов</button>
            </div>
        </div>
    </div>
<?endforeach?>
