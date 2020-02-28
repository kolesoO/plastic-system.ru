<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 */
?>
<script id="basket-total-template" type="text/html">
    <div class="basket_coupon" data-entity="basket-checkout-aligner">
        <div class="basket_coupon-col">
            <?if ($arParams['HIDE_COUPON'] !== 'Y') :?>
                <div class="animate_input js-animate_input">
                    <label for="promocode-input"><?=Loc::getMessage('SBB_COUPON_ENTER')?></label>
                    <input id="promocode-input" type="text" data-entity="basket-coupon-input">
                </div>
                <div class="basket_coupon-text">
                    {{#COUPON_LIST}}
                        <div>
                            <b>{{COUPON}} - </b>
                            <span><?=Loc::getMessage('SBB_COUPON')?> {{JS_CHECK_CODE}}</span>
                            <a href="#" class="link dashed" data-entity="basket-coupon-delete" data-coupon="{{COUPON}}"><?=Loc::getMessage('SBB_DELETE')?></a>
                        </div>
                    {{/COUPON_LIST}}
                </div>
            <?endif?>
        </div>
        <div class="basket_coupon-col">
            <div class="basket_coupon-title"><?=Loc::getMessage('SBB_TOTAL')?>:</div>
            {{#DISCOUNT_PRICE_FORMATED}}
                <div class="basket_list-old_price">
                    <small><s>{{{PRICE_WITHOUT_DISCOUNT_FORMATED}}}</s></small>&nbsp;
                </div>
            {{/DISCOUNT_PRICE_FORMATED}}
            <div class="basket_list-price">
                <span data-entity="basket-total-price">{{{PRICE_FORMATED}}}</span>
                <small>с НДС</small>
            </div>
        </div>
    </div>
    <br>
    <div class="col-xs-24">
        <button type="submit" class="form_button color" data-entity="basket-checkout-button"><?=Loc::getMessage('SBB_ORDER')?></button>
    </div>
</script>