<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
?>

<div class="title-3">Учетная запись</div>
<?ShowError($arResult["strProfileError"]);?>
<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data" class="personal_form col-lg-12">
    <?=$arResult["BX_SESSION_CHECK"]?>
    <input type="hidden" name="lang" value="<?=LANG?>" />
    <input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
    <div class="personal_form-item animate_input js-animate_input">
        <label for="name"><?=GetMessage('NAME')?></label>
        <input id="name" type="text" name="NAME" value="<?=$arResult["arUser"]["NAME"]?>" required>
    </div>
    <div class="personal_form-item animate_input js-animate_input">
        <label for="lname"><?=GetMessage('LAST_NAME')?></label>
        <input id="lname" type="text" name="LAST_NAME" value="<?=$arResult["arUser"]["LAST_NAME"]?>">
    </div>
    <div class="personal_form-item animate_input js-animate_input">
        <label for="sname"><?=GetMessage('SECOND_NAME')?></label>
        <input id="sname" type="text" name="SECOND_NAME" value="<?=$arResult["arUser"]["SECOND_NAME"]?>">
    </div>
    <div class="personal_form-item animate_input js-animate_input">
        <label for="email"><?=GetMessage('EMAIL')?></label>
        <input id="email" type="email" name="EMAIL" value="<?=$arResult["arUser"]["EMAIL"]?>" required>
    </div>
    <div class="personal_form-item animate_input js-animate_input">
        <label for="login"><?=GetMessage('LOGIN')?></label>
        <input id="login" type="text" name="LOGIN" value="<?=$arResult["arUser"]["LOGIN"]?>" required>
    </div>
    <?if($arResult['CAN_EDIT_PASSWORD']):?>
        <div class="personal_form-item animate_input js-animate_input">
            <label for="new_pwd"><?=GetMessage('NEW_PASSWORD_REQ')?></label>
            <input id="new_pwd" type="password" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="off" required>
        </div>
        <div class="personal_form-item animate_input js-animate_input">
            <label for="confirm_new_pwd"><?=GetMessage('NEW_PASSWORD_CONFIRM')?></label>
            <input id="confirm_new_pwd" type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off" required>
        </div>
    <?endif?>
    <div class="personal_form-item bottom" flex-align="center" flex-text_align="space-between">
        <input type="submit" name="save" class="form_button color col-lg-9" value="<?=GetMessage("MAIN_SAVE")?>">
        <?if ($_REQUEST["AJAX_CALL"] == "Y" && strlen($arResult["strProfileError"]) == 0) :?>
            <span>Данные успешно сохранены</span>
        <?endif?>
    </div>
</form>