<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */

if ($arParams["SET_TITLE"] == "Y") {
	$APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_COMPLETE"));
}
?>

<div class="block_wrapper">
    <? if (!empty($arResult["ORDER"])): ?>
        <p>
            <?=Loc::getMessage("SOA_ORDER_SUC", array(
                "#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"]->toUserTime()->format('d.m.Y H:i'),
                "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]
            ))?>
        </p>
        <? if (!empty($arResult['ORDER']["PAYMENT_ID"])): ?>
            <?=Loc::getMessage("SOA_PAYMENT_SUC", array(
                "#PAYMENT_ID#" => $arResult['PAYMENT'][$arResult['ORDER']["PAYMENT_ID"]]['ACCOUNT_NUMBER']
            ))?>
        <? endif ?>
        <? if ($arParams['NO_PERSONAL'] !== 'Y'): ?>
            <br /><br />
            <?=Loc::getMessage('SOA_ORDER_SUC1', ['#LINK#' => $arParams['PATH_TO_PERSONAL']])?>
        <? endif; ?>
    <? else: ?>
        <b><?=Loc::getMessage("SOA_ERROR_ORDER")?></b>
        <br /><br />
        <p><?=Loc::getMessage("SOA_ERROR_ORDER_LOST", ["#ORDER_ID#" => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"])])?></p>
        <p><?=Loc::getMessage("SOA_ERROR_ORDER_LOST1")?></p>
    <? endif ?>
</div>