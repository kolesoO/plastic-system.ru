<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["PERSON_TYPE_COUNT"] > 1) :?>
    <div class="tumbler js-tumbler">
        <div
            class="tumbler-content"
            data-content
            data-target="#PERSON_TYPE_<?=implode(",#PERSON_TYPE_", array_column($arResult["PERSON_TYPE"], "ID"))?>"
            data-positions="left,right"
            data-value="#PERSON_TYPE_<?=$arResult["ACTIVE_PERSON_TYPE"]?>"
            data-position="<?=$arResult["ACTIVE_PERSON_TYPE_KEY"] == 0 ? "left" : "right"?>"
        ></div>
        <?foreach($arResult["PERSON_TYPE"] as $v):?>
            <input
                id="PERSON_TYPE_<?=$v["ID"]?>"
                type="radio"
                name="PERSON_TYPE"
                class="hidden-lg hidden-md hidden-xs"
                value="<?=$v["ID"]?>"
                onchange="BX.saleOrderAjax.submitForm();"
                <?if ($v["CHECKED"]=="Y") :?> checked<?endif?>
            >
            <label class="tumbler-title"><?=$v["NAME"]?></label>
        <?endforeach?>
    </div>
<?else:?>
    <?foreach ($arResult["PERSON_TYPE"] as $v) :?>
        <input type="hidden" id="PERSON_TYPE" name="PERSON_TYPE" value="<?=$v["ID"]?>" />
        <input type="hidden" name="PERSON_TYPE_OLD" value="<?=$v["ID"]?>" />
    <?endforeach?>
<?endif?>