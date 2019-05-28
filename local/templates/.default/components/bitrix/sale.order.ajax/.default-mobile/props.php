<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="order_form-item-input">
    <?
    $counter = 0;
    foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $arProp) :
        //name
        $name = $arProp["NAME"].($arProp["REQUIRED"] == "Y" ? "*" : "");
        //end

        //type
        $type = "text";
        if ($arProp["IS_PHONE"]) {
            $type = "tel";
        } elseif ($arProp["IS_EMAIL"]) {
            $type = "email";
        }
        //end
        ?>
        <? if ($counter % 3 == 0 && $counter > 0) :?>
            </div><div class="order_form-item-input">
        <?endif?>
        <div class="animate_input js-animate_input">
            <label for="PROPERTY_<?=$arProp["CODE"]?>"><?=$name?></label>
            <input id="PROPERTY_<?=$arProp["CODE"]?>" type="<?=$type?>" name="<?=$arProp["FIELD_NAME"]?>" value="<?=$arProp["VALUE"]?>">
        </div>
        <?
        $counter ++;
    endforeach?>
</div>