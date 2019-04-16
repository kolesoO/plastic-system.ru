<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<div class="block_wrapper">
    <div class="cart_info">
        <div class="cart_info-item col-md-12">
            <div class="title-3">Технические характеристики</div>
            <div class="cart_info-wrap">
                <?foreach ($arParams["PROPERTY_CODE"] as $code) :?>
                    <?if (isset($arParams["ELEMENT_PROPERTIES"][$code]) && strlen($arParams["ELEMENT_PROPERTIES"][$code]["VALUE"]) > 0) :?>
                        <div class="cart_info-wrap-item">
                            <span><?=$arParams["ELEMENT_PROPERTIES"][$code]["NAME"]?></span>
                            <span><?=$arParams["ELEMENT_PROPERTIES"][$code]["VALUE"]?></span>
                        </div>
                    <?endif?>
                <?endforeach?>
            </div>
        </div>
        <div class="cart_info-item col-md-12">
            <?$APPLICATION->IncludeComponent(
                'bitrix:catalog.store.amount',
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
                $component,
                array('HIDE_ICONS' => 'Y')
            );?>
        </div>
    </div>
</div>
<div class="block_wrapper">
    <div class="cart_info">
        <div class="cart_info-item col-md-24">
            <?$APPLICATION->IncludeComponent(
                'kDevelop:catalog.delivery',
                '.default',
                array(),
                null
            );?>
        </div>
    </div>
</div>