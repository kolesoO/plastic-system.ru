<?
use \Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()){ return; }

$cache_type=\Bitrix\Main\Config\Configuration::getInstance()->get('cache');

if ($ex = $APPLICATION->GetException())
{
	echo CAdminMessage::ShowMessage(array(
		"TYPE" => "ERROR",
		"MESSAGE" => Loc::getMessage("MOD_INST_ERR"),
		"DETAILS" => $ex->GetString(),
		"HTML" => true,
	));    
}
else
{
    echo CAdminMessage::ShowNote(Loc::getMessage("MOD_INST_OK"));
}

if(!$cache_type['type'] || $cache_type['type']=='none')
{
    echo CAdminMessage::ShowMessage(array("MESSAGE"=>Loc::getMessage("IMPULSIT_ES_NO_CACHE"),"TYPE"=>"ERROR"));
}
?>
<form action="<?=$APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?=LANGUAGE_ID?>" />
	<input type="submit" name="" value="<?=Loc::getMessage("MOD_BACK")?>" />
<form>