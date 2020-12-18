<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;

#объявляем имя переменной именной в таком написании $module_id
#обязательно, иначе права доступа не работают!
$module_id = 'impulsit.expansionsite';

#подключаем языковые константы
Loc::loadMessages($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
Loc::loadMessages(__FILE__);

if (!Loader::includeModule('iblock')){ return; }

#Проверяем есть ли доступ к модулю
if ($APPLICATION->GetGroupRight($module_id)<"S")
{
    #Метод вызывает форму авторизации
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

#Подключаем наш модуль
\Bitrix\Main\Loader::includeModule($module_id);

#Получаем данные отправленные пользователем с формы
$request = \Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();

#Инфоблоки сайта
$arIBlock = array(Loc::getMessage('IMPULSIT_ES_SELECT_DEFAULT'));
$rsIBlock = CIBlock::GetList(array("SORT"=>"ASC","ID"=>"ASC"), array("ACTIVE"=>"Y"));
while($arr = $rsIBlock->Fetch())
{
    $arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
    $arIBlockId[] = $arr["ID"];
}

#Описание опций
$aTabs = array(
    array(
        'DIV' => 'edit1',
        'TAB' => Loc::getMessage('IMPULSIT_ES_TAB1'),
        'TITLE' => Loc::getMessage('IMPULSIT_ES_TAB1_TITLE'),
        'OPTIONS' => array(
            Loc::getMessage('IMPULSIT_ES_TAB1_TITLE_HEADER_1'),
            array('tab1_catalog_iblock_id', Loc::getMessage('IMPULSIT_ES_TAB1_CATALOG_IBLOCK_ID'),$arIBlockId[0],array('selectbox',$arIBlock), '', ' *1.1'),
            
            array('note'=>Loc::getMessage('IMPULSIT_ES_TAB1_NOTE')),
        )
    ),
    array(
        'DIV' => 'edit2',
        'TAB' => Loc::getMessage('IMPULSIT_ES_TAB2'),
        'TITLE' => Loc::getMessage('IMPULSIT_ES_TAB2_TITLE'),
        'OPTIONS' => array(
            Loc::getMessage('IMPULSIT_ES_TAB2_TITLE_HEADER_1'),
            array('tab2_metatag_logo', Loc::getMessage('IMPULSIT_ES_TAB2_METATAG_LOGO'),'',array('text', 30),'',' *2.1'),

            array('note'=>Loc::getMessage('IMPULSIT_ES_TAB1_NOTE')),
        )
    ),
    array(
        'DIV' => 'edit99',
        'TAB' => Loc::getMessage('MAIN_TAB_RIGHTS'),
        'TITLE' => Loc::getMessage('MAIN_TAB_TITLE_RIGHTS')
    ),
);

#Сохранение
if ($request->isPost() && $request['Update'] && check_bitrix_sessid())
{
    foreach ($aTabs as $aTab)
    {
        #Или можно использовать __AdmSettingsSaveOptions($MODULE_ID, $arOptions);
        foreach ($aTab['OPTIONS'] as $arOption)
        {
            #Строка с подсветкой. Используется для разделения настроек в одной вкладке
            if (!is_array($arOption)){ continue; }
                
            #Уведомление с подсветкой
            if ($arOption['note']){ continue; }
                
            #Или __AdmSettingsSaveOption($MODULE_ID, $arOption);
            $optionName = $arOption[0];

            $optionValue = $request->getPost($optionName);

            Option::set($module_id, $optionName, is_array($optionValue) ? implode(",", $optionValue):$optionValue);
        }
    }
    
    LocalRedirect($APPLICATION->GetCurPage().'?lang='.LANGUAGE_ID.'&mid='.$module_id);
}

#Визуальный вывод
#получаем объект класса от CAdminTabControl где tabControl id формы
$tabControl = new CAdminTabControl('tabControl', $aTabs);

#Открываем форму
$tabControl->Begin(); ?>
<form method='post' action='<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($request['mid'])?>&amp;lang=<?=$request['lang']?>' name='impulsit_salefood_settings'>
    <?
        foreach ($aTabs as $aTab):
            if($aTab['OPTIONS']):
                #открываем вкладку
                $tabControl->BeginNextTab();

                #методо который по переданным данным подгружает данные (опции) из бд и формитрет
                __AdmSettingsDrawList($module_id, $aTab['OPTIONS']); 
            endif;
        endforeach;

        #Добавим вкладку управление правами доступами
        $tabControl->BeginNextTab();
            require_once( $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php" );
        $tabControl->Buttons(); 
    ?>
    <input type="submit" name="Update" value="<?echo GetMessage('MAIN_SAVE')?>" />
    <input type="reset" name="reset" value="<?echo GetMessage('MAIN_RESET')?>" />
    <?=bitrix_sessid_post();?>
</form>
<? $tabControl->End(); ?>