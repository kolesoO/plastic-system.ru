<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["ITEMS_COUNT"] > 0) :?>
    <div class="pay_sys-list">
        <?foreach ($arResult["ITEMS"] as $arItem) :
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <a href="#" id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="pay_sys-item" data-popup-open="#payment-popup-<?=$arItem["ID"]?>">
                <?if (is_array($arItem["PREVIEW_PICTURE"])) :?>
                    <div class="img_wrap">
                        <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>">
                    </div>
                <?endif?>
                <span><?=(strlen($arItem["PREVIEW_TEXT"]) > 0 ? $arItem["PREVIEW_TEXT"] : $arItem["NAME"])?></span>
            </a>
        <?endforeach?>
    </div>
    <?foreach ($arResult["ITEMS"] as $arItem) :
        if (strlen($arItem["DETAIL_TEXT"]) == 0) {
            continue;
        }
        ?>
        <div id="payment-popup-<?=$arItem["ID"]?>" class="popup" data-animate>
            <div class="popup_wrapper">
                <div class="popup_content big animate-start js-popup_content">
                    <a href="#" class="popup_content-close" data-popup-close><i class="icon close"></i></a>
                    <?=$arItem["DETAIL_TEXT"]?>
                </div>
            </div>
        </div>
    <?endforeach?>
<?endif?>