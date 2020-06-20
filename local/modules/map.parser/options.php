<?php

/**
 * @var $REQUEST_METHOD
 * @var $Update
 * @var $Apply
 * @var $mid
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
use kDevelop\MapParser\Services\Parser;

$config = include __DIR__ . '/config.php';

Loader::includeModule('sale');
Loader::includeModule($config['MODULE_ID']);

$request = Application::getInstance()->getContext()->getRequest();
$messages = [];

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

if ($REQUEST_METHOD == 'POST' && strlen($Update . $Apply) > 0 && check_bitrix_sessid()) {
    $mapUrl = $request->get('map_url');

    if (!is_null($mapUrl)) {
        $parser = new Parser();
        try {
            $map = $parser->parseByUrl($mapUrl);
            $location = CSaleLocation::GetList(
                [],
                ["CITY_NAME" => $map->getTitle()],
                false,
                false,
                ["CODE"]
            )->fetch();

            if (!$location) {
                throw new Location('Location ' . $map->getTitle() . ' not found');
            }

            $mapRepository = new MapRepository();
            $polygonRepository = new PolygonRepository();
            $pointRepository = new PointRepository();

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
        } catch (Throwable $exception) {
            $messages[] = $exception->getMessage();
        }
    }
}

$tabControl = new CAdminTabControl("tabControl",  array(
    array(
        "DIV" => "edit1",
        "TAB" => Loc::getMessage('MAP_PARSER_TAB_NAME'),
        "ICON" => "blog_settings",
        "TITLE" => Loc::getMessage('MAP_PARSER_TAB_TITLE')
    ),
));
$tabControl->Begin();
?>


<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&lang=<?=LANGUAGE_ID?>">
	<?= bitrix_sessid_post() ?>
	<? $tabControl->BeginNextTab(); ?>

    <tr>
        <td class="adm-detail-content-cell-l">Адрес конструктора карт</td>
        <td class="adm-detail-content-cell-r">
            <input type="text" size="30" required name="map_url">
        </td>
    </tr>

    <?if (count($messages) > 0) :?>
        <tr class="heading">
            <td colspan="2">Системные сообщения</td>
        </tr>
        <?foreach ($messages as $message) :?>
            <tr><td colspan="2"><?=$message?></td></tr>
        <?endforeach?>
    <?endif?>

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
</form>
