<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $mobileColumns
 * @var array $arParams
 * @var string $templateFolder
 */
?>
<script id="basket-item-template" type="text/html">
    <div id="basket-item-{{ID}}" class="basket_list-item" data-entity="basket-item" data-id="{{ID}}">
        <a href="#" class="close_wrap" data-entity="basket-item-delete"><i class="icon close"></i></a>
        <div class="basket_list-wrap">
            <div class="basket_list-preview">
                <div class="basket_list-img">
                    <a href="javascript:void(0)">
                        <img src="{{{IMAGE_URL}}}{{^IMAGE_URL}}<?=SITE_TEMPLATE_PATH?>/images/no-image.png{{/IMAGE_URL}}" alt="{{NAME}}">
                    </a>
                </div>
                <a href="javascript:void(0)" class="basket_list-title">{{NAME}}</a>
            </div>
            <div class="basket_list-price_wrap">
                <div id="basket-item-sum-price-{{ID}}" class="basket_list-price">
                    <span>{{{SUM_PRICE_FORMATED}}}</span>
                    <small>с НДС</small>
                </div>
                <div class="basket_list-qnt">
                    <div class="cart_buy-qnt_wrap" data-entity="basket-item-quantity-block">
                        <a href="#" class="cart_buy-qnt" data-entity="basket-item-quantity-plus">+</a>
                        <input
                                id="basket-item-quantity-{{ID}}"
                                type="text"
                                class="cart_buy-qnt"
                                value="{{QUANTITY}}"
                                data-value="{{QUANTITY}}"
                                data-entity="basket-item-quantity-field"
                        >
                        <a href="#" class="cart_buy-qnt" data-entity="basket-item-quantity-minus">-</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>