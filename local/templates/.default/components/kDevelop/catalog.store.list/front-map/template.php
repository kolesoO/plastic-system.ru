<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["STORES_COUNT"] > 0) :?>
    <div class="banner_map-item">
        <div class="banner_map-map-wrap">
            <div
                    class="banner_map-map"
                    style="background-image: url('<?=SITE_TEMPLATE_PATH?>/images/map-bg.svg');width:1050px;height:453px"
            >
                <?foreach ($arResult["STORES"] as $arItem) :
                    if (!is_array($arItem["UF_CUSTOM_COORDS"]) || count($arItem["UF_CUSTOM_COORDS"]) == 0) continue;
                    ?>
                    <a href="#" class="banner_map-marker js-tool_tip" data-target="#tool_tip-<?=$arItem["ID"]?>" style="left:<?=$arItem["UF_CUSTOM_COORDS"][0]?>px;top:<?=$arItem["UF_CUSTOM_COORDS"][1]?>px;"><?=$arItem["STORE_TITLE"]?></a>
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
    </div>
<?endif?>
