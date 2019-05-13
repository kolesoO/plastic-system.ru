<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="title-3">Список адресов</div>
<?if (count($arResult["ITEMS"]) > 0) :?>
    <?if ($arParams["CAN_SELECT"] != "Y") :?>
        <p>Следующие адреса будут использованы при оформлении заказов</p>
    <?endif?>
    <div class="address_list">
        <?foreach ($arResult["ITEMS"] as $arItem) :
            $arItem["WRAP_ID"] = "address-".$arItem["ID"];
            $arItem["CAN_CHANGE"] = $arParams["CAN_CHANGE"];
            $arItem["CAN_SELECT"] = $arParams["CAN_SELECT"];
            ?>
            <div id="<?=$arItem["WRAP_ID"]?>" class="address_list-item">
                <?$APPLICATION->IncludeComponent(
                    "kDevelop:blank",
                    "user.address-item",
                    $arItem
                );?>
            </div>
        <?endforeach?>
    </div>
<?else:?>
    <p>Нет активных адресов</p>
<?endif?>
<?if ($arParams["CAN_CREATE"] == "Y") :?>
    <div id="address-form"></div>
    <br>
    <a href="#" id="address-btn" class="form_button color col-lg-5" onclick="obAjax.getUserAddressForm('', 'address-form', event)">Добавить адрес</a>
<?endif?>