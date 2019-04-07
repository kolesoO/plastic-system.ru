<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["ITEMS_COUNT"] > 0) :?>
    <section class="section">
        <div class="container">
            <div class="title-2">Новости</div>
            <div class="table_list" items-count="<?=$arParams["ITEMS_IN_ROW"]?>">
                <?foreach ($arResult["ITEMS"] as $arItem) :
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="table_list-item">
                        <div class="news-date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
                        <div class="link"><?=$arItem["NAME"]?></div>
                    </a>
                <?endforeach;?>
            </div>
        </div>
    </section>
<?endif?>