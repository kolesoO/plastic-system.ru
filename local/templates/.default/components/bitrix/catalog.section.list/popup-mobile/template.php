<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Localization\Loc;
?>

<?if ($arResult["SECTION_COUNT"] > 0) :?>
    <div class="popup full_popup js-catalog-menu" data-animate>
        <div class="popup_wrapper">
            <div class="popup_content full_popup animate-start js-popup_content">
                <a href="#" class="popup_content-close" data-popup-close><i class="icon close"></i></a>
                <div class="catalog_menu">
                    <div class="catalog_menu-title">
                        <div class="title-3"><?=Loc::getMessage("CSL_P_TITLE")?></div>
                    </div>
                    <div class="catalog_menu-list js-mobile_menu">
                        <a href="#" class="catalog_menu-item back link js-mobile_menu-back">
                            <i class="icon arrow-left"></i>
                            <span><?=Loc::getMessage("CSL_P_BACK")?></span>
                        </a>
                        <?foreach ($arResult["SECTIONS"] as $arSection) :
                            $dopClass = "";
                            $href = $arSection["SECTION_PAGE_URL"];
                            if (is_array($arSection["SUB_SECTIONS"])) {
                                $dopClass = " js-mobile_menu-item";
                                $href = "#";
                            }
                            $dopClass = (is_array($arSection["SUB_SECTIONS"]) ? " js-mobile_menu-item" : "");
                            ?>
                            <a href="<?=$href?>" class="catalog_menu-item<?=$dopClass?>">
                                <div class="catalog_menu-img">
                                    <img src="<?=(is_array($arSection["PICTURE"]) ? $arSection["PICTURE"]["SAFE_SRC"] : SITE_TEMPLATE_PATH."/images/no-image.png")?>" alt="<?=$arSection["NAME"]?>">
                                </div>
                                <div class="catalog_menu-item-title"><?=$arSection["NAME"]?></div>
                                <i class="icon arrow-right"></i>
                            </a>
                            <?if (is_array($arSection["SUB_SECTIONS"])) :?>
                                <div class="catalog_menu-item-sub">
                                    <?foreach ($arSection["SUB_SECTIONS"] as $arSubSection) :?>
                                        <div><a href="<?=$arSubSection["SECTION_PAGE_URL"]?>" class="link"><?=$arSubSection["NAME"]?></a></div>
                                    <?endforeach?>
                                </div>
                            <?endif?>
                        <?endforeach?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?endif?>