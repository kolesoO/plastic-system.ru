<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["ITEMS_COUNT"] > 0) :?>
    <div class="block_wrapper big">
        <div class="contacts_list">
            <?foreach ($arResult["ITEMS"] as $arItem) :
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="contacts_list-item">
                    <?if (is_array($arItem["DETAIL_PICTURE"])) :?>
                        <div class="contacts_list-img">
                            <img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>">
                        </div>
                    <?endif?>
                    <div class="contacts_list-title"><?=$arItem["NAME"]?></div>
                    <div class="contacts_list-desc"><?=$arItem["DETAIL_TEXT"]?></div>
                    <?if(isset($arItem["PROPERTIES"]["SCHEME"]) && strlen($arItem["PROPERTIES"]["SCHEME"]["VALUE"]) > 0) :?>
                        <a href="<?=$arItem["PROPERTIES"]["SCHEME"]["VALUE"]?>" class="link dashed">Схема проезда, pdf</a>
                    <?endif?>
                </div>
            <?endforeach?>
        </div>
    </div>
<?endif?>