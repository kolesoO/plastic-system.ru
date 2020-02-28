<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["STORES_COUNT"] > 0) :?>
    <div class="block_wrapper big">
        <!--div class="block_content">
            <div id="contacts-map" class="y_map"></div>
            <div class="banner_map-map-wrap">
                <div class="banner_map-map" style="background-image: url('<?=SITE_TEMPLATE_PATH?>/images/map-bg.svg');width:1050px;height:453px;margin:0 auto">
                    <?foreach ($arResult["STORES"] as $arItem) :
                        if (!is_array($arItem["UF_CUSTOM_COORDS"]) || count($arItem["UF_CUSTOM_COORDS"]) == 0) continue;
                        ?>
                        <a href="#" class="banner_map-marker js-tool_tip" data-target="#tool_tip-<?=$arItem["ID"]?>" style="left:<?=$arItem["UF_CUSTOM_COORDS"][0]?>px;top:<?=$arItem["UF_CUSTOM_COORDS"][1]?>px;"><?=$arItem["UF_CITY_NAME"]?></a>
                        <div id="tool_tip-<?=$arItem["ID"]?>" class="banner_map-balloon js-tool_tip-content">
                            <div class="banner_map-balloon_title"><?=$arItem["STORE_TITLE"]?></div>
                            <p>
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
                            </p>
                        </div>
                    <?endforeach?>
                </div>
            </div>
        </div-->
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
                    <?if ($arItem['VIEW_MAP'] === true && isset($arItem["UF_FROM_N"]) && isset($arItem["UF_FROM_S"])) :?>
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
                    <?endif?>
                </div>
            <?endforeach?>
        </div>
    </div>
<?endif?>
