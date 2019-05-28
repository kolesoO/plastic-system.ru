<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
?>

<form class="def_form col-lg-12 col-md-12 col-xs-24" onsubmit="obAjax.createUserAddress(this, '<?=$arParams["WRAP_ID"]?>', event)">
    <input type="hidden" name="class" value="Iblock">
    <input type="hidden" name="method" value="add">
    <input type="hidden" name="params[IBLOCK_ID]" value="<?=$arParams["IBLOCK_ID"]?>">
    <?if (isset($arParams["ITEM"]["ID"])) :?>
        <input type="hidden" name="params[ID]" value="<?=$arParams["ITEM"]["ID"]?>">
    <?endif?>
    <?foreach ($arParams["FIELDS"] as $arField) :
        $id = "user_address-".strtolower($arField["CODE"]);
        $isRequired = $arField["IS_REQUIRED"] == "Y";
        //value
        $value = "";
        if (isset($arParams["ITEM"][$arField["CODE"]])) {
            $value = $arParams["ITEM"][$arField["CODE"]];
        } elseif (isset($arParams["ITEM"]["PROPERTIES"][$arField["CODE"]])) {
            $value = $arParams["ITEM"]["PROPERTIES"][$arField["CODE"]]["VALUE"];
        }
        //end
        //name
        $name = "params[".$arField["CODE"]."]";
        if ($arField["IS_PROPERTY"] == "Y") {
            $name = "params[PROPERTIES][".$arField["CODE"]."]";
        }
        //end
        ?>
        <?if ($arField["MULTIPLE"] == "Y") :?>
            <?for ($counter = 0; $counter < $arField["MULTIPLE_CNT"]; $counter ++) :?>
                <div class="animate_input js-animate_input">
                    <label for="<?=$id."-".$counter?>"><?=Loc::getMessage($arField["CODE"]."_".$counter)?></label>
                    <input id="<?=$id."-".$counter?>" type="text" name="<?=$name?>[<?=$counter?>]" value="<?=$value[$counter]?>"<?if ($isRequired) :?> required<?endif?>>
                    <input type="hidden" name="params[DESCRIPTION][<?=$arField["CODE"]?>][<?=$counter?>]" value="<?=Loc::getMessage($arField["CODE"]."_".$counter)?>">
                </div>
            <?endfor?>
        <?else:?>
            <?if ($arField["USER_PROP"] == "Y") :?>
                <input type="hidden" name="<?=$name?>" value="<?=(strlen($value) > 0 ? $value : $USER->GetId())?>">
            <?else:?>
                <div class="animate_input js-animate_input">
                    <label for="<?=$id?>"><?=$arField["NAME"]?></label>
                    <input id="<?=$id?>" type="text" name="<?=$name?>" value="<?=$value?>"<?if ($isRequired) :?> required<?endif?>>
                </div>
            <?endif?>
        <?endif?>
    <?endforeach?>
    <button type="submit" class="form_button">Сохранить</button>
    <div id="<?=$arParams["WRAP_ID"]?>-error" class="error"></div>
</form>