<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["STORES_COUNT"] > 0) :?>
    <div class="footer-part-item footer_content-wrap">
        <?foreach ($arResult["STORES"] as $arItem) :?>
            <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="footer_content">
                <div class="footer_content-title"><?=$arItem["STORE_TITLE"]?></div>
                <?if (strlen($arItem["ADDRESS"]) > 0) :?>
                    <span><?=$arItem["ADDRESS"]?></span>
                <?endif?>
                <?if (strlen($arItem["PHONE"]) > 0) :?>
                    <br><span><?=$arItem["PHONE"]?></span>
                <?endif?>
                <?if (strlen($arItem["EMAIL"]) > 0) :?>
                    <br><span><?=$arItem["EMAIL"]?></span>
                <?endif?>
            </div>
        <?endforeach?>
    </div>
<?endif?>