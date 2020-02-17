<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?foreach ($arResult['JS_DATA']['ORDER_PROP']['groups'] as $group) :
    if ($group['RELATED_PROPS_COUNT'] == 0) continue;
    ?>
    <div class="order_form-item">
        <div class="order_form-title">
            <div class="title-3 text"><?=$group['NAME']?></div>
        </div>
        <?
        $counter = 0;
        foreach ($arResult["ORDER_PROP"]["RELATED"] as $arProp) :
            if ($arProp['PROPS_GROUP_ID'] != $group['ID']) continue;
            //name
            $name = $arProp["NAME"].($arProp["REQUIRED"] == "Y" ? "*" : "");
            //end
            ?>
            <div class="order_form-item-wrap order_form-item-input">
                <?if ($arProp["CODE"] == "ADDRESS") :
                    $arValue = explode(",", $arProp["VALUE"]);
                    ?>
                    <div class="animate_input js-animate_input">
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
                    <div class="animate_input js-animate_input">
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
                    <div class="animate_input js-animate_input">
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
                    <div class="animate_input js-animate_input">
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
                    <?
                    switch ($arProp['TYPE']) {
                        case 'CHECKBOX':?>
                            <div class="checkbox_btn col-xs-24">
                                <input
                                        id="<?=$arProp["FIELD_ID"]?>"
                                        type="checkbox"
                                        name="<?=$arProp["FIELD_NAME"]?>"
                                        value="Y"
                                        <?if ($arProp['VALUE'] == 'Y') :?>checked<?endif?>
                                >
                                <label for="<?=$arProp["FIELD_ID"]?>"><?=$name?></label>
                            </div>
                            <?break;
                        default:?>
                            <div class="animate_input js-animate_input">
                                <label for="<?=$arProp["FIELD_ID"]?>"><?=$name?></label>
                                <input id="<?=$arProp["FIELD_ID"]?>" type="text" name="<?=$arProp["FIELD_NAME"]?>">
                            </div>
                            <?break;
                    }?>
                <?endif?>
            </div>
            <?if ($arProp["CODE"] == "ADDRESS") :?>
                <div class="order_form-item-wrap">
                    <button class="form_button" onclick="obAjax.getUserAddressList('address-list', event)">Добавить из адресов</button>
                </div>
                <div id="address-list" class="order_form-item-wrap order_address"></div>
            <?endif?>
            <?
            $counter++;
        endforeach?>
    </div>
<?endforeach?>
