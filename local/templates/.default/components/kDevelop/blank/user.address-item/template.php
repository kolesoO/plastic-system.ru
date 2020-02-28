<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<div class="title-4">Адрес доставки</div>
<p>
    <span>Имя: <?=$arParams["NAME"]?></span>
    <?foreach ($arParams["PROPERTIES"] as $arProp) :?>
        <br>
        <span><?=$arProp["NAME"]?>: <?=(is_array($arProp["VALUE"]) ? implode(", ", $arProp["VALUE"]) : $arProp["VALUE"])?></span>
    <?endforeach?>
</p>
<?if ($arParams["CAN_CHANGE"] == "Y") :?>
    <a href="#" class="form_button color col-lg-5" onclick="obAjax.getUserAddressForm('<?=$arParams["ID"]?>', '<?=$arParams["WRAP_ID"]?>', event)">Изменить</a>
<?endif?>
<?if ($arParams["CAN_SELECT"] == "Y") :?>
    <a href="#" class="form_button color col-lg-5" onclick="obAjax.setUserAddress('<?=$arParams["ID"]?>', event)">Выбрать</a>
<?endif?>