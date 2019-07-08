<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["STORES_COUNT"] > 0) :?>
    <div class="block_wrapper big">
        <div class="block_content">
            <div id="contacts-map" class="y_map"></div>
        </div>
        <div class="contacts_list">
            <?foreach ($arResult["STORES"] as $arItem) :?>
                <div class="contacts_list-item">
                    <div class="contacts_list-img">
                        <?if (is_array($arItem["DETAIL_IMG"])) :?>
                            <img src="<?=$arItem["DETAIL_IMG"]["SRC"]?>" alt="<?=$arItem["STORE_TITLE"]?>">
                        <?else:?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/no-image.png" alt="<?=$arItem["STORE_TITLE"]?>">
                        <?endif?>
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
                    <?if (is_array($arItem["UF_SCHEME"])) :?>
                        <a href="<?=$arItem["UF_SCHEME"]["SRC"]?>" target="_blank" class="link dashed">Схема проезда, <?=$arItem["UF_SCHEME"]["EXTENSION"]?></a>
                    <?endif?>
                </div>
            <?endforeach?>
        </div>
    </div>
<?endif?>