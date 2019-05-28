<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if (is_array($arResult)) :?>
    <div id="header-menu-popup" class="popup_liener header_menu-popup">
        <div class="popup_content">
            <a href="#" class="popup_content-close js-toggle" data-target="#header-menu-popup" data-class="active">
                <i class="icon close"></i>
            </a>
            <div class="popup_menu">
                <?foreach ($arResult as $arItem) :?>
                    <div class="popup_menu-item">
                        <?if ($arItem["SELECTED"] == "Y") :?>
                            <div class="link"><?=$arItem["TEXT"]?></div>
                        <?else:?>
                            <a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
                        <?endif?>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </div>
<?endif?>