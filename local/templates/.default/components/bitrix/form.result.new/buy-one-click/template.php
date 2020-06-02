<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="title-3"><?=$arResult["arForm"]["NAME"]?></div>
<?if ($arResult["isFormNote"] != "Y") :?>
    <?=$arResult["FORM_HEADER"]?>
    <?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
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
        <?if ($arResult["isUseCaptcha"] == "Y") :?>
            <div class="popup_form-item" flex-align="center" flex-text_align="space-between">
                <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>">
                <img
                        class="col-lg-9 col-md-9 col-xs-9"
                        src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>"
                        height="40"
                >
                <div class="col-lg-12 col-md-12 col-xs-12 animate_input js-animate_input">
                    <label for="captcha_word">Ввод символов</label>
                    <input id="captcha_word" type="text" name="captcha_word" size="30" maxlength="50" value="" required>
                </div>
            </div>
        <?endif?>
        <div class="popup_form-item">
            <input type="submit" name="web_form_submit" class="form_button color" value="<?=$arResult["arForm"]["BUTTON"]?>">
        </div>
    </div>
    <?=$arResult["FORM_FOOTER"]?>
    <script>
        if (typeof obAnimateInput == "object") {
            obAnimateInput.init()
        }
    </script>
<?else:?>
    <p>Ваш заказ успешно оправлен,менеджер свяжется с вами с ближайшеевремя для уточнения деталей</p>
<?endif?>
