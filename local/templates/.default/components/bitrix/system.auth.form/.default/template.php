<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
    ShowMessage($arResult['ERROR_MESSAGE']);
?>

<form name="system_auth_form<?=$arResult["RND"]?>" method="post" action="<?=$arResult["AUTH_URL"]?>" class="popup_form" novalidate>
    <?if($arResult["BACKURL"] <> ''):?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <?endif?>
    <?foreach ($arResult["POST"] as $key => $value):?>
        <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
    <?endforeach?>
    <input type="hidden" name="AUTH_FORM" value="Y">
    <input type="hidden" name="TYPE" value="AUTH">
    <div class="popup_form-item animate_input js-animate_input">
        <label for="email1"><?=GetMessage("AUTH_LOGIN")?>*</label>
        <input id="email1" type="email" name="USER_LOGIN">
    </div>
    <div class="popup_form-item animate_input js-animate_input">
        <label for="pwd1"><?=GetMessage("AUTH_PASSWORD")?>*</label>
        <input id="pwd1" type="password" name="USER_PASSWORD">
    </div>
    <div class="popup_form-item">
        <button type="submit" name="Login" class="form_button color"><?=GetMessage("AUTH_LOGIN_BUTTON")?></button>
    </div>
</form>