<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["STORES_COUNT"] == 1) :?>
    <div class="dropdown header-location">
        <a href="#" class="link"><?=$arResult["CUR_STORE"]["STORE_TITLE"]?></a>
    </div>
<?elseif ($arResult["STORES_COUNT"] > 1) :?>
    <div class="dropdown header-location js-drop_down">
        <a href="#" class="dropdown-btn link js-drop_down-btn"><?=$arResult["CUR_STORE"]["STORE_TITLE"]?></a>
        <div class="header-location-content js-drop_down-content">
            <div class="header-location-inner">
                <?foreach ($arResult["STORES"] as $arItem) :?>
                    <a
                            href="//<?=$arItem['SITE']['URL']?>"
                            class="header-location-link link"
                    ><?=$arItem["STORE_TITLE"]?></a>
                <?endforeach?>
            </div>
        </div>
    </div>
<?endif?>
