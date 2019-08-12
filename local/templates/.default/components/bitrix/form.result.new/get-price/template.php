<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="title-3"><?=$arResult["arForm"]["NAME"]?></div>
<?if ($arResult["isFormNote"] != "Y") :?>
    <?=$arResult["FORM_HEADER"]?>
    <div class="popup_form">
        <?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) :?>
            <?if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') :?>
                <?=$arQuestion["HTML_CODE"];?>
            <?elseif ($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "file") :?>
                <div class="popup_form-item"><?=$arQuestion["HTML_CODE"];?></div>
            <?else:?>
                <div class="popup_form-item<?if ($arQuestion["HAS_ANIMATE"] == "Y"):?> animate_input js-animate_input<?endif?>">
                    <label for="<?=$FIELD_SID."_".$arQuestion["STRUCTURE"][0]["ID"]?>"><?=$arQuestion["CAPTION"]?></label>
                    <?=$arQuestion["HTML_CODE"];?>
                </div>
            <?endif?>
        <?endforeach?>
        <div class="popup_form-item">
            <input type="submit" name="web_form_submit" class="form_button color" value="<?=$arResult["arForm"]["BUTTON"]?>">
        </div>
    </div>
    <?=$arResult["FORM_FOOTER"]?>
<?else:?>
    <p>Ваш запрос успешно оправлен,прайс с оптовыми ценами вы получитев течение 24 часов</p>
<?endif?>