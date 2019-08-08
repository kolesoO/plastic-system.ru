<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div flex-align="center" class="order_form-item-input">
    <?
    $counter = 0;
    foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $arProp) :
        //name
        $name = $arProp["NAME"].($arProp["REQUIRED"] == "Y" ? "*" : "");
        //end
        //type
        $type = "text";
        if ($arProp["IS_PHONE"] == "Y") {
            $type = "tel";
        } elseif ($arProp["IS_EMAIL"] == "Y") {
            $type = "email";
        }
        //end
        ?>
        <? if ($counter % 3 == 0 && $counter > 0) :?>
            </div><div flex-align="center" class="order_form-item-input">
        <?endif?>
        <?if ($arProp["IS_LOCATION"] == "Y") :?>
            <input type="hidden" name="<?=$arProp["FIELD_NAME"]?>" value="<?=intval($arProp["VALUE"]) > 0 ? $arProp["VALUE"] : $arProp["DEFAULT_VALUE"]?>">
        <?else:?>
            <div class="col-lg-6 col-md-9 animate_input js-animate_input">
                <label for="<?=$arProp["FIELD_ID"]?>"><?=$name?></label>
                <input id="<?=$arProp["FIELD_ID"]?>" type="<?=$type?>" name="<?=$arProp["FIELD_NAME"]?>" value="<?=$arProp["VALUE"]?>">
            </div>
        <?endif?>
        <?
        $counter ++;
    endforeach?>
</div>