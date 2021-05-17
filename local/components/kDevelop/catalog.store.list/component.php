<?
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
/** @global CCacheManager $CACHE_MANAGER */

use Bitrix\Iblock;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arParams['PHONE'] = (isset($arParams['PHONE']) && $arParams['PHONE'] == 'Y' ? 'Y' : 'N');
$arParams['SCHEDULE'] = (isset($arParams['SCHEDULE']) && $arParams['SCHEDULE'] == 'Y' ? 'Y' : 'N');

$arParams['PATH_TO_ELEMENT'] = (isset($arParams['PATH_TO_ELEMENT']) ? trim($arParams['PATH_TO_ELEMENT']) : '');
if ($arParams['PATH_TO_ELEMENT'] == '')
	$arParams['PATH_TO_ELEMENT'] = 'store/#store_id#';

$arParams['MAP_TYPE'] = (int)(isset($arParams['MAP_TYPE']) ? $arParams['MAP_TYPE'] : 0);

$arParams['SET_TITLE'] = (isset($arParams['SET_TITLE']) && $arParams['SET_TITLE'] == 'Y' ? 'Y' : 'N');
$arParams['TITLE'] = (isset($arParams['TITLE']) ? trim($arParams['TITLE']) : '');

$arParams["SORT_BY"] = isset($arParams["SORT_BY"]) ? $arParams["SORT_BY"] : "ID";
$arParams["SORT_ORDER"] = isset($arParams["SORT_ORDER"]) ? $arParams["SORT_ORDER"] : "ASC";

if (!isset($arParams['CACHE_TIME']))
	$arParams['CACHE_TIME'] = 3600;

if ($this->startResultCache())
{
	if (!\Bitrix\Main\Loader::includeModule("catalog"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("CATALOG_MODULE_NOT_INSTALL"));
		return;
	}

	$arResult["TITLE"] = GetMessage("SCS_DEFAULT_TITLE");
	$arResult["MAP"] = $arParams["MAP_TYPE"];

	$arSelect = array(
		"ID",
		"TITLE",
		"ADDRESS",
		"DESCRIPTION",
		"GPS_N",
		"GPS_S",
		"IMAGE_ID",
		"PHONE",
		"EMAIL",
		"SCHEDULE",
		"SITE_ID"
	);
	$dbStoreProps = CCatalogStore::GetList(
	    array($arParams["SORT_BY"] => $arParams["SORT_ORDER"]),
        array("ACTIVE"=>"Y", '!SITE_ID' => false),
        false,
        false,
        $arSelect
    );
	$arResult["PROFILES"] = array();
	while ($arProp = $dbStoreProps->GetNext())
	{
        $viewMap = false;
		$url = CComponentEngine::makePathFromTemplate($arParams["PATH_TO_ELEMENT"], array("store_id" => $arProp["ID"]));

		$storeImg = false;
		$arProp['IMAGE_ID'] = (int)$arProp['IMAGE_ID'];
		if ($arProp['IMAGE_ID'] > 0)
			$storeImg = CFile::GetFileArray($arProp['IMAGE_ID']);
		if (!empty($storeImg))
			$storeImg['SRC'] = Iblock\Component\Tools::getImageSrc($storeImg, true);
		$arProp['IMAGE_ID'] = (empty($storeImg) ? false : $storeImg);

		if ($arProp["TITLE"]=='' && $arProp["ADDRESS"]!='')
			$storeName = $arProp["ADDRESS"];
		elseif ($arProp["ADDRESS"]=='' && $arProp["TITLE"]!='')
			$storeName = $arProp["TITLE"];
		else
			$storeName = $arProp["TITLE"]." (".$arProp["ADDRESS"].")";

		if ($arParams["PHONE"]=='Y' && $arProp["PHONE"]!='')
			$storePhone = $arProp["PHONE"];
		else
			$storePhone = null;

        if ($arParams["EMAIL"]=='Y' && $arProp["EMAIL"]!='')
            $storeEmail = $arProp["EMAIL"];
        else
            $storeEmail = null;

		if ($arParams["SCHEDULE"]=='Y' && $arProp["SCHEDULE"]!='')
			$storeSchedule = $arProp["SCHEDULE"];
		else
			$storeSchedule = null;
		if($arProp["GPS_N"] && $arProp["GPS_S"])
		{
			$viewMap=true;
			$this->abortResultCache();
		}
		$arResult["STORES"][] = array(
			'ID' => $arProp["ID"],
			'TITLE' => $storeName,
			'PHONE' => $storePhone,
			"EMAIL" => $storeEmail,
			'SCHEDULE' => $storeSchedule,
			'DETAIL_IMG' => $arProp['IMAGE_ID'],
			'GPS_N' => $arProp["GPS_N"],
			'GPS_S' => $arProp["GPS_S"],
			'STORE_TITLE' => $arProp['TITLE'],
			'ADDRESS' => $arProp["ADDRESS"],
			'URL' => $url,
			'DESCRIPTION' => (string)$arProp['DESCRIPTION'],
            'VIEW_MAP' => $viewMap,
            'SITE_ID' => $arProp['SITE_ID'],
		);
	}

	$sitesList = [];
    $rsSites = CSite::GetList($by="sort", $order="desc", ['ID' => array_column($arResult["STORES"], 'SITE_ID')]);

	while ($site = $rsSites->fetch()) {
	    foreach ($arResult["STORES"] as $key => $store) {
	        if ($store['SITE_ID'] !== $site['ID']) {
	            continue;
            }

            $arResult["STORES"][$key]['SITE'] = $site;

	        unset($arResult["STORES"][$key]['SITE_ID']);
        }
        $sitesList[$site['ID']] = $site;
    }

	$this->includeComponentTemplate();
}

if ($arParams['SET_TITLE'] == 'Y') {
    $APPLICATION->SetTitle($arParams['TITLE']);
}
