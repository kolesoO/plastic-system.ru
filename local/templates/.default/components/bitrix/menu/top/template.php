<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if (is_array($arResult)) :?>
    <nav class="header-menu">
        <?foreach ($arResult as $arItem) :?>
            <?if ($arItem["SELECTED"] == "Y") :?>
                <span class="header-menu-item link"><?=$arItem["TEXT"]?></span>
            <?else:?>
                <a href="<?=$arItem["LINK"]?>" class="header-menu-item"><?=$arItem["TEXT"]?></a>
            <?endif?>
        <?endforeach;?>
    </nav>
<?endif?>