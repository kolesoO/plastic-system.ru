<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;

IncludeModuleLangFile(__FILE__);

class map_parser extends CModule
{

    var $MODULE_ID = 'map.parser';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_PATH;

    /** @var array */
    private $errors;

    function __construct()
    {
    	$path = str_replace("\\", "/", __FILE__);
    	$path = substr($path, 0, strlen($path) - strlen("/install/index.php"));

    	include($path."/install/version.php");
    	include($path."/config.php");

        $this->MODULE_PATH = $path;
        $this->MODULE_NAME =  Loc::getMessage('MAP_PARSER_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MAP_PARSER_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('MAP_PARSER_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('MAP_PARSER_PARTNER_URI');

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
    }

    function InstallFiles($arParams = array())
    {
        CopyDirFiles(
            $_SERVER["DOCUMENT_ROOT"] . '/local/modules/' . $this->MODULE_ID . '/install/components',
            $_SERVER["DOCUMENT_ROOT"] . '/bitrix/components',
            true,
            true
        );
    }

    function UnInstallFiles()
    {
        DeleteDirFiles(
            $_SERVER["DOCUMENT_ROOT"] . '/local/modules/' . $this->MODULE_ID . '/install/components',
            $_SERVER["DOCUMENT_ROOT"] . '/bitrix/components'
        );
    }

    function installDB()
    {
        global $APPLICATION, $DB;

        $this->errors = $DB->runSQLBatch(
            $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/db/' . strtolower($DB->type) . '/install.sql'
        );

        if ($this->errors !== false) {
            $APPLICATION->throwException(implode('', $this->errors));

            return false;
        }

        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandler('sale', 'onSaleDeliveryServiceCalculate', $this->MODULE_ID, '\kDevelop\MapParser\Handlers\Order', 'onSaleDeliveryServiceCalculateHandler');
        $eventManager->registerEventHandler('sale', 'OnOrderAdd', $this->MODULE_ID, '\kDevelop\MapParser\Handlers\Order', 'OnOrderAddHandler');

        return true;
    }

    function uninstallDB()
    {
        global $APPLICATION, $DB;

        $this->errors = $DB->runSQLBatch(
            $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/db/' . strtolower($DB->type) . '/uninstall.sql'
        );

        if ($this->errors !== false) {
            $APPLICATION->throwException(implode('', $this->errors));

            return false;
        }

        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler('sale', 'onSaleDeliveryServiceCalculate', $this->MODULE_ID, '\kDevelop\MapParser\Handlers\Order', 'onSaleDeliveryServiceCalculateHandler');
        $eventManager->unRegisterEventHandler('sale', 'OnOrderAdd', $this->MODULE_ID, '\kDevelop\MapParser\Handlers\Order', 'OnOrderAddHandler');

        return true;
    }

	function DoInstall()
    {
        $this->InstallFiles();
        $this->installDB();

        RegisterModule($this->MODULE_ID);
        COption::SetOptionInt($this->MODULE_ID, "delete", false);

        return true;
	}

	function DoUninstall()
    {
        $this->UnInstallFiles();
        $this->uninstallDB();

        COption::SetOptionInt($this->MODULE_ID, "delete", true);
        DeleteDirFilesEx($this->MODULE_ID);
        UnRegisterModule($this->MODULE_ID);

        return true;
	}
}
