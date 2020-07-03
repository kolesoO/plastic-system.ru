<?php

/**
 * @var $REQUEST_METHOD
 * @var $Update
 * @var $Apply
 * @var $mid
 * @var $delete_regions
 * @var $cache_time
 * @var $clear_cache
 */

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale;
use kDevelop\MapParser\Exceptions\Location;
use kDevelop\MapParser\Repositories\MapRepository;
use kDevelop\MapParser\Repositories\PointRepository;
use kDevelop\MapParser\Repositories\PolygonRepository;
use kDevelop\MapParser\Services\MapFetcher;
use kDevelop\MapParser\Services\Parser;

global $CACHE_MANAGER;

$config = include __DIR__ . '/config.php';

Loader::includeModule('sale');
Loader::includeModule($config['MODULE_ID']);

$request = Application::getInstance()->getContext()->getRequest();
$messages = [];

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$mapFetcher = new MapFetcher();
$mapRepository = new MapRepository();
$polygonRepository = new PolygonRepository();
$pointRepository = new PointRepository();

if ($REQUEST_METHOD == 'POST' && strlen($Update . $Apply) > 0 && check_bitrix_sessid()) {
    $mapUrl = $request->get('map_url');

    try {
        if (!is_null($mapUrl) && strlen($mapUrl) > 0) {
            $parser = new Parser();
            $map = $parser->parseByUrl($mapUrl);
            $location = CSaleLocation::GetList(
                [],
                ["CITY_NAME" => $map->getTitle()],
                false,
                false,
                ["CODE"]
            )->fetch();

            if (!$location) {
                throw new Location('Местоположение ' . $map->getTitle() . ' не найдено');
            }

            $mapEntity = $mapRepository->add(
                $map->setLocation($location['CODE'])
            );

            foreach ($map->getPolygons() as $polygon) {
                $polygonEntity = $polygonRepository->add($mapEntity->getId(), $polygon);

                foreach ($polygon->getPoints() as $point) {
                    $pointRepository->add($polygonEntity->getId(), $point);
                }
            }
            $messages[] = 'Данные успешно загружены';
        }

        if (isset($delete_regions)) {
            foreach ($delete_regions as $regionId) {
                $mapSource = $mapRepository->find((int) $regionId);

                if (!$mapSource) continue;

                $map = $mapFetcher->createMap($mapSource);

                foreach ($map->getPolygons() as $polygon) {
                    foreach ($polygon->getPoints() as $point) {
                        $pointRepository->delete($point->getId());
                    }

                    $polygonRepository->delete($polygon->getId());
                }

                $mapRepository->delete($map->getId());
            }
            $messages[] = 'Регионы успешно удалены';
        }
    } catch (Throwable $exception) {
        $messages[] = $exception->getMessage();
    }

    COption::SetOptionInt($config['MODULE_ID'], 'cache_time', $cache_time);
    $messages[] = 'Время кеширования успешно обновлено';

    if ($clear_cache) {
        BXClearCache(true, '/iblock/locations_geo_data/');
        $messages[] = 'Кеш успешно очищен';
    }
}

//загруженные регионы
$loadedRegions = $mapFetcher->createAll();
//end

$tabControl = new CAdminTabControl("tabControl",  [
    [
        "DIV" => "edit1",
        "TAB" => Loc::getMessage('MAP_PARSER_TAB_NAME'),
        "ICON" => "blog_settings",
        "TITLE" => Loc::getMessage('MAP_PARSER_TAB_TITLE')
    ],
    [
        "DIV" => "edit2",
        "TAB" => Loc::getMessage('MAP_PARSER_TAB2_NAME'),
        "ICON" => "blog_settings",
        "TITLE" => Loc::getMessage('MAP_PARSER_TAB2_TITLE')
    ],
    [
        "DIV" => "edit3",
        "TAB" => Loc::getMessage('MAP_PARSER_TAB3_NAME'),
        "ICON" => "blog_settings",
        "TITLE" => Loc::getMessage('MAP_PARSER_TAB3_TITLE')
    ]
]);
$tabControl->Begin();
?>


<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&lang=<?=LANGUAGE_ID?>">
	<?= bitrix_sessid_post() ?>

	<? $tabControl->BeginNextTab(); ?>

    <tr>
        <td class="adm-detail-content-cell-l">Адрес конструктора карт</td>
        <td class="adm-detail-content-cell-r">
            <input type="text" size="30" name="map_url">
        </td>
    </tr>

    <? $tabControl->BeginNextTab(); ?>

    <?foreach ($loadedRegions as $region) :
        $pointsCount = 0;
        foreach ($region->getPolygons() as $polygon) {
            $pointsCount += count($polygon->getPoints());
        }
        ?>
        <tr class="heading">
            <td colspan="2"><b><?=$region->getTitle()?></b></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l" style="width:50%">Количество полигонов:</td>
            <td class="adm-detail-content-cell-r" style="width:50%"><?=count($region->getPolygons())?></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l" style="width:50%">Количество точек:</td>
            <td class="adm-detail-content-cell-r" style="width:50%"><?=$pointsCount?></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l" style="width:50%">Удалить регион</td>
            <td class="adm-detail-content-cell-r" style="width:50%">
                <input type="checkbox" name="delete_regions[]" value="<?=$region->getId()?>">
            </td>
        </tr>
    <?endforeach?>

    <? $tabControl->BeginNextTab(); ?>

    <tr>
        <td class="adm-detail-content-cell-l" style="width:50%">Время кеширования данных (сек):</td>
        <td class="adm-detail-content-cell-r" style="width:50%">
            <input type="text" size="30" name="cache_time" value="<?=COption::GetOptionInt($config['MODULE_ID'], 'cache_time', 3600)?>">
        </td>
    </tr>
    <tr>
        <td class="adm-detail-content-cell-l" style="width:50%">Сбросить кеш</td>
        <td class="adm-detail-content-cell-r" style="width:50%">
            <input type="checkbox" name="clear_cache" value="Y">
        </td>
    </tr>

    <? $tabControl->Buttons(); ?>
		<input type="submit" name="Update" value="<?= GetMessage("MAIN_SAVE") ?>" title="<?= GetMessage("MAIN_OPT_SAVE_TITLE") ?>" class="adm-btn-save">
		<input type="submit" name="Apply" value="<?= GetMessage("MAIN_OPT_APPLY") ?>" title="<?= GetMessage("MAIN_OPT_APPLY_TITLE") ?>">
		<? if (strlen($_REQUEST["back_url_settings"]) > 0): ?>
	        <input type="button" name="Cancel" value="<?= GetMessage("MAIN_OPT_CANCEL") ?>"
	               title="<?= GetMessage("MAIN_OPT_CANCEL_TITLE") ?>"
	               onclick="window.location='<? echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"])) ?>'">
	        <input type="hidden" name="back_url_settings" value="<?= htmlspecialcharsbx($_REQUEST["back_url_settings"]) ?>">
	    <? endif ?>
    <? $tabControl->End(); ?>

    <?if (count($messages) > 0) :?>
        <div class="adm-info-message">
            <?foreach ($messages as $message) :?>
                <div><?=$message?></div>
            <?endforeach?>
        </div>
    <?endif?>
</form>
