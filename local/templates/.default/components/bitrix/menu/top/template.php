<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if (is_array($arResult)) :?>
    <nav class="header-menu">
        <?foreach ($arResult as $arItem) :?>
		
            <?if ($arItem["SELECTED"] == "Y") :?>
		<span  class="header-menu-item link"><?=$arItem["TEXT"]?></span>
            <?else:?>
                <a <?if($arItem["PARAMS"]["header-menu-item"]) { ?> data-fancybox <? } ?> href="<?=$arItem["LINK"]?>" class="<?if($arItem["PARAMS"]["header-menu-item"]) { ?> <?=$arItem["PARAMS"]["header-menu-item"];?><? } ?> header-menu-item"><?=$arItem["TEXT"]?></a>
            <?endif?>
        <?endforeach;?>
    </nav>
<?endif?>