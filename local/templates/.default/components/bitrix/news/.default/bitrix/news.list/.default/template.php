<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?if ($arResult["ITEMS_COUNT"]) :?>
    <?if ($arParams["DISPLAY_TOP_PAGER"]) :?>
        <?=$arResult["NAV_STRING"]?>
    <?endif;?>
    <div class="news_list">
        <?foreach ($arResult["ITEMS"] as $arItem) :
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            $previewImagePath = (is_array($arItem["PREVIEW_PICTURE"]) ? $arItem["PREVIEW_PICTURE"]["SRC"] : SITE_TEMPLATE_PATH."/images/no-image.png");
            ?>
            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="news_list-item">
                <div class="news_list-img" style="background-image:url('<?=$previewImagePath?>')"></div>
                <div class="news_list-desc">
                    <div class="news_list-date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
                    <div class="link"><?=$arItem["NAME"]?></div>
                </div>
            </a>
        <?endforeach;?>
    </div>
    <?if ($arParams["DISPLAY_BOTTOM_PAGER"]) :?>
        <?=$arResult["NAV_STRING"]?>
    <?endif;?>
<?endif?>