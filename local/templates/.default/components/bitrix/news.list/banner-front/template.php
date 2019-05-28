<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["ITEMS_COUNT"] > 0) :?>
    <section class="section">
        <div class="container">
            <div class="banner">
                <?foreach ($arResult["ITEMS"] as $arItem) :
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    $style = "";
                    if (is_array($arItem["PREVIEW_PICTURE"])) {
                        $style .= "background-image:url('".$arItem["PREVIEW_PICTURE"]["SRC"]."');";
                    }
                    if (isset($arItem["PROPERTIES"]["STYLE"]) && strlen($arItem["PROPERTIES"]["STYLE"]["VALUE"]) > 0) {
                        $style .= $arItem["PROPERTIES"]["STYLE"]["VALUE"];
                    }
                    if (isset($arItem["PROPERTIES"]["COLOR"]) && strlen($arItem["PROPERTIES"]["COLOR"]["VALUE"]) > 0) {
                        $dopStyle .= "background-color:".$arItem["PROPERTIES"]["COLOR"]["VALUE"].";";
                    }
                    ?>
                    <a id="<?=$this->GetEditAreaId($arItem['ID']);?>" href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" class="banner-item" style="<?=$style?>">
                        <div class="banner-item-inner" style="<?=$dopStyle?>">
                            <div class="banner-item-title"><?=$arItem["NAME"]?></div>
                        </div>
                    </a>
                <?endforeach;?>
            </div>
        </div>
    </section>
<?endif?>