<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["ITEMS_COUNT"] > 0) :?>
    <section
        class="default_slider clearfix js-slider"
        data-autoplay="true"
        data-autoplaySpeed="5000"
        data-infinite="true"
        data-speed="1000"
        data-arrows="false"
        data-dots="true"
    >
        <?foreach ($arResult["ITEMS"] as $arItem) :
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <?if (strlen($arItem["PROPERTIES"]["LINK"]["VALUE"]) > 0) :?>
                <a id="<?=$this->GetEditAreaId($arItem['ID']);?>" href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" class="default_slider-item" style="background-image:url('<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>')">
                    <div class="container">
                        <div class="default_slider-desc"><?=($arItem["PREVIEW_TEXT_TYPE"] == "text" ? $arItem["PREVIEW_TEXT"] : htmlspecialcharsback($arItem["PREVIEW_TEXT"]))?></div>
                    </div>
                </a>
            <?else:?>
                <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="default_slider-item" style="background-image:url('<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>')">
                    <div class="container">
                        <div class="default_slider-desc"><?=($arItem["PREVIEW_TEXT_TYPE"] == "text" ? $arItem["PREVIEW_TEXT"] : htmlspecialcharsback($arItem["PREVIEW_TEXT"]))?></div>
                    </div>
                </div>
            <?endif?>
        <?endforeach;?>
    </section>
<?endif?>