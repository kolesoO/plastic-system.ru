<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Localization\Loc;
?>

<?if ($arResult["SECTION_COUNT"] > 0) :?>
    <div class="catalog_menu">
        <div class="catalog_menu-list">
            <?foreach ($arResult["SECTIONS"] as $arSection) :
                $subSectionsCounter = 0;
                ?>
                <div class="catalog_menu-item">
                    <div class="catalog_menu-item-wrap" flex-align="center" flex-text_align="space-between">
                        <div class="catalog_menu-img col-lg-6">
                            <img src="<?=(is_array($arSection["PICTURE"]) ? $arSection["PICTURE"]["SAFE_SRC"] : SITE_TEMPLATE_PATH."/images/no-image.png")?>" alt="<?=$arSection["NAME"]?>">
                        </div>
                        <a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="catalog_menu-item-title col-lg-15"><?=$arSection["NAME"]?></a>
                    </div>
                    <?if (is_array($arSection["SUB_SECTIONS"])) :?>
                        <?foreach ($arSection["SUB_SECTIONS"] as $arSubSection) :
                            if ($subSectionsCounter > 2) continue;
                            ?>
                            <div><a href="<?=$arSubSection["SECTION_PAGE_URL"]?>" class="link"><?=$arSubSection["NAME"]?></a></div>
                            <?
                            $subSectionsCounter ++;
                        endforeach?>
                    <?endif?>
                </div>
            <?endforeach?>
        </div>
    </div>
<?endif?>