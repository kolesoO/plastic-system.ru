<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["STORES_COUNT"] > 0) :?>
    <div class="block_wrapper big">
        <div class="contacts_list">
            <?foreach ($arResult["STORES"] as $arItem) :
                $itemImg = is_array($arItem["DETAIL_IMG"]) ? $arItem["DETAIL_IMG"]["SRC"] : SITE_TEMPLATE_PATH."/images/no-image.png";
                ?>
                <div class="contacts_list-item">
                    <div class="contacts_list-img">
                        <img src="<?=$itemImg?>" alt="<?=$arItem["STORE_TITLE"]?>">
                    </div>
                    <div class="contacts_list-title"><?=$arItem["STORE_TITLE"]?></div>
                    <div class="contacts_list-desc">
                        <?if (strlen($arItem["ADDRESS"]) > 0) :?>
                            <span><?=$arItem["ADDRESS"]?></span>
                        <?endif?>
                        <?if (strlen($arItem["PHONE"]) > 0) :?>
                            <br><b><?=$arItem["PHONE"]?></b>
                        <?endif?>
                        <?if (strlen($arItem["EMAIL"]) > 0) :?>
                            <br><span><?=$arItem["EMAIL"]?></span>
                        <?endif?>
                        <?if (strlen($arItem["SCHEDULE"]) > 0) :?>
                            <br><span><?=$arItem["SCHEDULE"]?></span>
                        <?endif?>
                    </div>
                    <?if ($arItem['UF_SCHEME']) :?>
                        <div>
                            <a
                                    href="<?=$arItem['UF_SCHEME']['SRC']?>"
                                    download="<?=$arItem['UF_SCHEME']['FILE_NAME']?>"
                                    class="link dashed"
                            >Скачать схему проезда (<?=$arItem['UF_SCHEME']['EXTENSION']?>)</a>
                        </div>
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
    </div>
<?endif?>
