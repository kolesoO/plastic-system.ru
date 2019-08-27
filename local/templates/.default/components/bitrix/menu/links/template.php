<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if (is_array($arResult)) :?>
    <div>
        <?foreach ($arResult as $arItem) :?>
            <?if ($arItem["SELECTED"] == "Y") :?>
                <span><?=$arItem["TEXT"]?></span>
            <?else:?>
                <a href="<?=$arItem["LINK"]?>" class="link"><?=$arItem["TEXT"]?></a>
            <?endif?>
        <?endforeach;?>
    </div>
<?endif?>