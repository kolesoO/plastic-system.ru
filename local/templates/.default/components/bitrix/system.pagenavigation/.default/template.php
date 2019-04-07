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

<?if ($arResult["NavPageCount"] > 1) :?>
    <div class="table_nav">
        <?if ($arResult["NavPageNomer"] > 1) :
            $prevUrl = $arResult["sUrlPath"];
            if ($arResult["NavPageNomer"] > 2) {
                $prevUrl .= "?PAGEN_".$arResult["NavNum"]."=".($arResult["NavPageNomer"] - 1);
            }
            ?>
            <a href="<?=$prevUrl?>" class="icon arrow-left"></a>
        <?endif?>
        <?while ($arResult["nStartPage"] <= $arResult["nEndPage"]) :?>
            <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]) :?>
                <div class="table_nav-item active"><?=$arResult["nStartPage"]?></div>
            <?else:
                $href = $arResult["sUrlPath"];
                if ($arResult["nStartPage"] > 1) {
                    $href .= "?PAGEN_".$arResult["NavNum"]."=".$arResult["nStartPage"];
                }
                ?>
                <a href="<?=$href?>" class="table_nav-item"><?=$arResult["nStartPage"]?></a>
            <?endif?>
            <?$arResult["nStartPage"] ++?>
        <?endwhile;?>
        <?if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]) :?>
            <a href="<?=$arResult["sUrlPath"]?>?PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"] + 1)?>" class="icon arrow-right"></a>
        <?endif?>
    </div>
<?endif?>