<?php
namespace Impulsit\ExpansionSite;

use \Bitrix\Main;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader;

class Handler
{
    /**
     * @brief Получить все настройки модуля
     **/
	public function getOptions()
	{
        $resOptions = array();
        $settings = Option::getForModule("impulsit.expansionsite");

        #Общие настройки
        $resOptions["COMMON"]["CATALOG_IBLOCK_ID"] = $settings["tab1_catalog_iblock_id"];

        #МетаТег
        $resOptions["META_TAG"]["LOGO"] = $settings["tab2_metatag_logo"];

        return $resOptions;
	}
}