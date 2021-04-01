<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["SECTION_COUNT"] > 0) :?>
    <section class="section relative">
        <div class="container">
            <div class="table_list sections" items-count="<?=$arParams["ITEMS_IN_ROW"]?>">
                <?foreach ($arResult["SECTIONS"] as $arSection) :
                    $this->AddEditAction(
                        $arSection['ID'],
                        $arSection['EDIT_LINK'],
                        CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT")
                    );
                    $this->AddDeleteAction(
                        $arSection['ID'],
                        $arSection['DELETE_LINK'],
                        CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE"),
                        array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'))
                    );
                    ?>
                    <a href="<?=$arSection["SECTION_PAGE_URL"]?>" id="<?=$this->GetEditAreaId($arSection['ID'])?>" class="table_list-item">
                        <div class="table_list-img">
                            <img src="<?=(is_array($arSection["PICTURE"]) ? $arSection["PICTURE"]["SAFE_SRC"] : SITE_TEMPLATE_PATH."/images/no-image.png")?>" alt="<?=$arSection["NAME"]?>" title="<?=$arSection["NAME"]?>">
                        </div>
                        <div class="table_list-title"><?=$arSection["NAME"]?></div>
                    </a>
                <?endforeach?>
            </div>
        </div>
    </section>
<?endif?>