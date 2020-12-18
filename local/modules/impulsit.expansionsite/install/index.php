<?
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config as Conf;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Entity\Base;
use \Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

Class impulsit_expansionsite extends CModule
{
    var $exclusionAdminFiles;
	function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__."/version.php");

        $this->exclusionAdminFiles=array(
            '..',
            '.',
            'menu.php',
            'operation_description.php',
            'task_description.php'
        );

        $this->MODULE_ID = 'impulsit.expansionsite';
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("IMPULSIT_ES_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("IMPULSIT_ES_MODULE_DESC");

        $this->PARTNER_NAME = Loc::getMessage("IMPULSIT_ES_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("IMPULSIT_ES_PARTNER_URI");
    }

    public function GetPath($notDocumentRoot=false)
    {
        if($notDocumentRoot)
            return str_ireplace(Application::getDocumentRoot(),'',dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    function InstallDB()
    {

    }

    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        #Если есть файл options.php с настройками, то при удалении удаляем эти настройки из БД
        Option::delete($this->MODULE_ID);
    }

	function InstallEvents()
	{

    }

	function UnInstallEvents()
	{

    }

	function InstallFiles($arParams = array())
	{

	}

	function UnInstallFiles()
	{

	}

   /**
     * Установка модуля в один шаг 
     */
	function DoInstall()
	{
		global $APPLICATION;
        if($this->isVersionD7())
        {
            #Говорит системе что есть такой модуль и он установленный
            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
 
            #Установка бд таблиц и заполнения их демо данными.
            $this->InstallDB();

            #Регистрируем обработчики событий которые нам нужны.
            $this->InstallEvents();

            #Манипуляция с файлами копирование компонентов в администратиные части.
            $this->InstallFiles();
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage("IMPULSIT_ES_INSTALL_ERROR_VERSION"));
        }

        //Установка в один шаг (подключаеться всегда)
        $APPLICATION->IncludeAdminFile(Loc::getMessage("IMPULSIT_ES_INSTALL_TITLE"), $this->GetPath()."/install/step.php");
	}

   /**
     * Удаление модуля
     */
 	function DoUninstall()
	{
        global $APPLICATION;

        #Получаем данные отправленные пользователем с формы
        $request = \Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();

        if($request["step"]<2)
        {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("IMPULSIT_ES_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep1.php");
        }
        elseif($request["step"]==2)
        {
            #Манипуляция с файлами удалени компонентов в администратиные части.
            $this->UnInstallFiles();

            #Удаляем обработчики событий которые нам нужны.
			$this->UnInstallEvents();

            #Удаляем бд таблиц, если пользователь не захотел их сохранить
            if($request["savedata"] != "Y"){ $this->UnInstallDB(); }

            #Говорит системе что модуль Был удалён
            \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

            //Переходим на второй шаг удаления
            $APPLICATION->IncludeAdminFile(Loc::getMessage("IMPULSIT_ES_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep2.php");
        }
	}

   /**
     * Установим права доступа к модулю
     */
    #Используеться вместе с options.php
    function GetModuleRightList()
    {
        return array(
            "reference_id" => array("D","K","S","W"),
            "reference" => array(
                "[D] ".Loc::getMessage("IMPULSIT_ES_DENIED"),#Доступ закрыт
                "[K] ".Loc::getMessage("IMPULSIT_ES_READ_COMPONENT"),#Доступ к компонентам
                "[S] ".Loc::getMessage("IMPULSIT_ES_WRITE_SETTINGS"),#Изменение настроек модуля
                "[W] ".Loc::getMessage("IMPULSIT_ES_FULL"))#Полный доступ
        );
    }
}
?>