<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<form id="regform" name="regform" enctype="multipart/form-data" class="popup_form" onsubmit="obAjax.userRegister(this, event)">
    <?if (strlen($arResult["BACKURL"]) > 0) :?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
    <?endif?>
    <div class="popup_form-item animate_input js-animate_input">
        <label for="email"><?=GetMessage("REGISTER_FIELD_LOGIN")?>*</label>
        <input id="email" type="email" name="LOGIN" value="<?=$arResult["VALUES"]["LOGIN"]?>" required>
    </div>
    <div class="popup_form-item animate_input js-animate_input">
        <label for="pwd"><?=GetMessage("REGISTER_FIELD_PASSWORD")?>*</label>
        <input id="pwd" type="password" name="PASSWORD" value="<?=$arResult["VALUES"]["PASSWORD"]?>" required minlength="6" maxlength="50">
    </div>
    <div class="popup_form-item animate_input js-animate_input">
        <label for="confirm-pwd"><?=GetMessage("REGISTER_FIELD_CONFIRM_PASSWORD")?>*</label>
        <input id="confirm-pwd" type="password" name="CONFIRM_PASSWORD" value="<?=$arResult["VALUES"]["CONFIRM_PASSWORD"]?>" required minlength="6" maxlength="50">
    </div>
    <?if ($arResult["USE_CAPTCHA"] == "Y") :?>
        <div class="popup_form-item">
            <img id="reg-captcha-picture" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA">
            <input id="reg-captcha-sid" type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
            <input type="text" class="col-lg-24 col-md-24 col-xs-24" name="captcha_word" maxlength="50" value="" autocomplete="off" placeholder="<?=GetMessage("REGISTER_CAPTCHA_PROMT")?>">
        </div>
    <?endif?>
    <div class="popup_form-item">
        <button type="submit" class="form_button color"><?=GetMessage("AUTH_REGISTER")?></button>
    </div>
    <br>
    <div class="error_txt"></div>
</form>
