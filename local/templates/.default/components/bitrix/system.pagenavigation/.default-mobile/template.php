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
        <div align="center" class="col-xs-24">Страница <b><?=$arResult["NavPageNomer"]?></b> из <?=$arResult["NavPageCount"]?></div>
        <br>
        <br>
        <?if ($arResult["NavPageNomer"] > 1) :
            $prevUrl = $arResult["sUrlPath"];
            if ($arResult["NavPageNomer"] > 2) {
                $prevUrl .= "?PAGEN_".$arResult["NavNum"]."=".($arResult["NavPageNomer"] - 1);
            }
            ?>
            <a href="<?=$prevUrl?>" class="form_button color col-xs-24">Предыдущая</a>
        <?endif?>
        <?if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]) :?>
            <a href="<?=$arResult["sUrlPath"]?>?PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"] + 1)?>" class="form_button color col-xs-24">Следующая</a>
        <?endif?>
    </div>
<?endif?>