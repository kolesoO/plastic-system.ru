<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<div class="block_wrapper">
    <div class="cart_info">
        <div class="cart_info-item col-lg-8">
            <h2 class="title-3">Технические характеристики</h2>
            <div class="cart_info-wrap">
                <?
                $arOldPropCode = [];
                foreach ($arParams["ELEMENT_PROPERTIES"] as $arProp) :
                    if (in_array($arProp["CODE"], $arParams["EXCLUDE_PROPS"]) || in_array($arProp["CODE"], $arOldPropCode)) continue;
                    ?>
                    <?if (is_string($arProp["VALUE"]) && strlen($arProp["VALUE"]) > 0) :
                        $arOldPropCode[] = $arProp["CODE"];
                        ?>
                        <div class="cart_info-wrap-item">
                            <span><?=$arProp["NAME"]?></span>
                            <span><?=$arProp["VALUE"]?></span>
                        </div>
                    <?endif?>
                <?endforeach?>
                <?foreach ($arParams["OFFER"]["PROPERTIES"] as $arProp) :
                    if (in_array($arProp["CODE"], $arParams["EXCLUDE_PROPS"]) || in_array($arProp["CODE"], $arOldPropCode)) continue;
                    ?>
                    <?if (is_string($arProp["VALUE"]) && strlen($arProp["VALUE"]) > 0) :
                        $arOldPropCode[] = $arProp["CODE"];
                        ?>
                        <div class="cart_info-wrap-item">
                            <span><?=$arProp["NAME"]?></span>
                            <span><?=$arProp["VALUE"]?></span>
                        </div>
                    <?endif?>
                <?endforeach?>
            </div>
        </div>
        <div class="cart_info-item col-lg-8">
            <?$APPLICATION->IncludeComponent(
                'kDevelop:catalog.store.amount',
                '.default',
                array(
                    'ELEMENT_ID' => $arParams['ELEMENT_ID'],
                    'STORE_PATH' => $arParams['STORE_PATH'],
                    'CACHE_TYPE' => 'A',
                    'CACHE_TIME' => '36000',
                    'USE_MIN_AMOUNT' =>  $arParams['USE_MIN_AMOUNT'],
                    'MIN_AMOUNT' => $arParams['MIN_AMOUNT'],
                    'STORES' => $arParams['STORES'],
                    'SHOW_EMPTY_STORE' => $arParams['SHOW_EMPTY_STORE'],
                    'SHOW_GENERAL_STORE_INFORMATION' => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
                    'USER_FIELDS' => $arParams['USER_FIELDS'],
                    'FIELDS' => $arParams['FIELDS']
                ),
                null,
                array('HIDE_ICONS' => 'Y')
            );?>
        </div>
        <div class="cart_info-item col-lg-8">
            <?
            $APPLICATION->IncludeComponent(
                'kDevelop:catalog.delivery',
                '.default',
                array(),
                null
            );?>
        </div>
    </div>
</div>
