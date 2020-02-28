<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["PAY_SYSTEM_COUNT"] > 1) :?>
    <div class="order_form-item">
        <div class="order_form-title">
            <div class="title-3 text">Оплата</div>
        </div>
        <div flex-align="center">
            <?foreach ($arResult["PAY_SYSTEM"] as $arPaySystem) :?>
                <div class="radio_btn col-xs-24">
                    <div flex-align="center">
                        <input
                                id="PAY_SYSTEM_<?=$arPaySystem["ID"]?>"
                                type="radio"
                                name="PAY_SYSTEM_ID"
                                value="<?=$arPaySystem["ID"]?>"
                                onchange="BX.saleOrderAjax.submitForm();"
                                <?if ($arPaySystem["CHECKED"] == "Y" ) echo "checked";?>
                        >
                        <label for="PAY_SYSTEM_<?=$arPaySystem["ID"]?>"><?=$arPaySystem["PSA_NAME"]?></label>
                    </div>
                </div>
            <?endforeach?>
        </div>
    </div>
<?elseif ($arResult["PAY_SYSTEM_COUNT"] == 1) :
    $arPaySystem = $arResult["PAY_SYSTEM"][0];
    ?>
    <input
            id="PAY_SYSTEM_<?=$arPaySystem["ID"]?>"
            type="hidden"
            name="PAY_SYSTEM_ID"
            value="<?=$arPaySystem["ID"]?>"
        <?if ($arPaySystem["CHECKED"] == "Y" ) echo "checked";?>
    >
<?endif?>
