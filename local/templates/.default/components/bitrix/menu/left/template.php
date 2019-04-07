<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if (is_array($arResult)) :?>
    <div class="personal_aside">
        <?foreach ($arResult as $arItem) :?>
            <div class="personal_aside-item">
                <?if ($arItem["SELECTED"] == "Y") :?>
                    <div class="link"><?=$arItem["TEXT"]?></div>
                <?else:?>
                    <a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
                <?endif?>
            </div>
        <?endforeach;?>
    </div>
<?endif?>