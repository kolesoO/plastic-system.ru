<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["STORES_COUNT"] > 0) :?>
    <div class="footer-part-item footer_content-wrap">
        <?foreach ($arResult["STORES"] as $arItem) :?>
            <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="footer_content" itemscope itemtype="https://schema.org/Organization">
                <div class="footer_content-title" itemprop="name"><?=$arItem["STORE_TITLE"]?></div>
                <?if (strlen($arItem["ADDRESS"]) > 0) :?>
                    <span itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                        <span itemprop="streetAddress"><?=$arItem["ADDRESS"]?></span>
                    </span>
                <?endif?>
                <?if (strlen($arItem["PHONE"]) > 0) :?>
                    <br><span itemprop="telephone"><?=$arItem["PHONE"]?></span>
                <?endif?>
                <?if (strlen($arItem["EMAIL"]) > 0) :?>
                    <br><span><?=$arItem["EMAIL"]?></span>
                <?endif?>
                <?if ($arItem['UF_MAP_LINK']) :?>
                    <div>
                        <a
                                href="<?=$arItem['UF_MAP_LINK']?>"
                                target="_blank"
                                class="link dashed"
                        >Открыть в Яндекс.Картах</a>
                    </div>
                <?endif?>
            </div>
        <?endforeach?>
    </div>
<?endif?>
