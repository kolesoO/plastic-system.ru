<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="title-3"><?=$arResult["arForm"]["NAME"]?></div>
<?if ($arResult["isFormNote"] != "Y") :?>
    <?=$arResult["FORM_HEADER"]?>
    <?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
    <div class="popup_form">
        <p><?=$arResult['arForm']['DESCRIPTION']?></p>
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
            <div class="popup_form-item">
                <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>">
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="70" height="30" alt="CAPTCHA">
                <input type="text" name="captcha_word" size="30" maxlength="50" value="" >
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
        Recaptchafree.reset();
    </script>
<?else:?>
    <p><?=$arResult['arForm']['RESULT_TEXT']?></p>
<?endif?>
