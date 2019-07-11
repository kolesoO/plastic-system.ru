<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["ITEMS_COUNT"] > 0) :?>
    <?foreach ($arResult["ITEMS"] as $arItem) :
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="block_wrapper js-drop_down">
            <div class="title-3"><?=$arItem["NAME"]?></div>
            <?=($arItem["PREVIEW_TEXT_TYPE"] == "text" ? '<p>'.$arItem["PREVIEW_TEXT"].'</p>' : htmlspecialcharsback($arItem["PREVIEW_TEXT"]))?>
            <div class="dynamic-text js-drop_down-content"><?=($arItem["DETAIL_TEXT_TYPE"] == "text" ? '<p>'.$arItem["DETAIL_TEXT"].'</p>' : htmlspecialcharsback($arItem["DETAIL_TEXT"]))?></div>
            <a href="#" class="link js-drop_down-btn" data-active_content="Скрыть" data-content="Подробнее">Подробнее</a>
        </div>
    <?endforeach;?>
<?endif?>