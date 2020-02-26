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
                <?if ($arItem['VIEW_MAP'] === true && isset($arItem["UF_FROM_N"]) && isset($arItem["UF_FROM_S"])) :?>
                    <br>
                    <a
                            href="#"
                            class="link dashed js-store-map"
                            data-popup-open="#store-road-wrap"
                            data-target="#store-road-map"
                            data-way_point_body="<?=$arItem['MAP_BALLOON_CONTENT']?>"
                            data-pgs_n="<?=$arItem["GPS_N"]?>"
                            data-pgs_s="<?=$arItem["GPS_S"]?>"
                            data-pgs_n_2="<?=$arItem["UF_FROM_N"]?>"
                            data-pgs_s_2="<?=$arItem["UF_FROM_S"]?>"
                    >Схема проезда</a>
                <?endif;?>
            </div>
        <?endforeach?>
    </div>
<?endif?>
