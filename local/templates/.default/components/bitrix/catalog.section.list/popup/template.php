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
                    <div class="catalog_menu-list">
                        <?foreach ($arResult["SECTIONS"] as $arSection) :?>
                            <div class="catalog_menu-item">
                                <div class="catalog_menu-img">
                                    <img src="<?=(is_array($arSection["PICTURE"]) ? $arSection["PICTURE"]["SAFE_SRC"] : SITE_TEMPLATE_PATH."/images/no-image.png")?>" alt="<?=$arSection["NAME"]?>">
                                </div>
                                <a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="catalog_menu-item-title"><?=$arSection["NAME"]?></a>
                                <?if (is_array($arSection["SUB_SECTIONS"])) :?>
                                    <?foreach ($arSection["SUB_SECTIONS"] as $arSubSection) :?>
                                        <div><a href="<?=$arSubSection["SECTION_PAGE_URL"]?>" class="link"><?=$arSubSection["NAME"]?></a></div>
                                    <?endforeach?>
                                <?endif?>
                            </div>
                        <?endforeach?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?endif?>