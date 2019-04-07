<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<div class="block_wrapper">
    <div class="cart_info">
        <div class="cart_info-item col-xs-24">
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
    </div>
</div>
<div class="block_wrapper">
    <div class="cart_info">
        <div class="cart_info-item col-xs-24">
            <div class="title-3">Наличие</div>
            <div class="cart_info-wrap">
                <div class="cart_info-wrap-item">
                    <span>Санкт-Петербург</span>
                    <div class="table_list-status red">Мало</div>
                </div>
                <div class="cart_info-wrap-item">
                    <span>Москва</span>
                    <div class="table_list-status green">Много</div>
                </div>
                <div class="cart_info-wrap-item">
                    <span>Самара</span>
                    <div class="table_list-status">Под заказ</div>
                </div>
                <div class="cart_info-wrap-item">
                    <span>Санкт-Петербург</span>
                    <div class="table_list-status red">Мало</div>
                </div>
                <div class="cart_info-wrap-item">
                    <span>Москва</span>
                    <div class="table_list-status green">Много</div>
                </div>
                <div class="cart_info-wrap-item">
                    <span>Самара</span>
                    <div class="table_list-status">Под заказ</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="block_wrapper">
    <div class="cart_info">
        <div class="cart_info-item col-xs-24">
            <div class="title-3">Доставка</div>
            <div class="def_form">
                <div class="animate_input js-animate_input">
                    <label for="city">Город</label>
                    <input id="city" type="text" value="Санкт-Петербург">
                </div>
                <div class="animate_input js-animate_input">
                    <label for="address">Адрес</label>
                    <input id="address" type="text" value="Авиаторов 17">
                </div>
                <button type="submit" class="form_button">Пересчитать</button>
            </div>
            <br>
            <div class="title-3 text">600 ₽</div>
            <a href="#" class="link">
                <span>Подробнее о доставке и оплате</span>
                <i class="icon arrow-right"></i>
            </a>
            <br><br>
            <div class="title-3 text">Самовывоз</div>
            <span>Сегодня: Старо-Паново, ул.Рабочая, д. 16Г</span>
        </div>
    </div>
</div>