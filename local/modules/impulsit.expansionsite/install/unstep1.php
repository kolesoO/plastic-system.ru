<?
use \Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()){ return; }

Loc::loadMessages(__FILE__);
?>
<form action="<?=$APPLICATION->GetCurPage()?>">
    <?=bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?=LANGUAGE_ID?>" />
	<input type="hidden" name="id" value="impulsit.expansionsite" />
	<input type="hidden" name="uninstall" value="Y" />
	<input type="hidden" name="step" value="2" />
    <?#Выводим сообщение о том что модуль будет удалён?>
	<?=CAdminMessage::ShowMessage(Loc::getMessage("MOD_UNINST_WARN"))?>
	<p><?=Loc::getMessage("MOD_UNINST_SAVE")?></p>
	<p><input type="checkbox" name="savedata" id="savedata" value="Y" checked="checked" /><label for="savedata"><?=Loc::getMessage("MOD_UNINST_SAVE_TABLES")?></label></p>
	<input type="submit" name="" value="<?=Loc::getMessage("MOD_UNINST_DEL")?>" />
</form>