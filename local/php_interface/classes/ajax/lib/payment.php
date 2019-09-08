<?php
namespace kDevelop\Ajax;

class Payment
{
    use MsgHandBook;

    /**
     * @param $arParams
     * @return array
     */
    public static function initPayment($arParams)
    {
        include_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/classes/service/platron/config.php');
        include_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/classes/service/platron/PG_Signature.php');

        $arReturn = [
            "js_callback" => "initPaymentCallBack",
            "msg" => self::getMsg("INIT_PAYMENT_ERROR")
        ];

        if (strlen($arParams["order_number"]) > 0 && intval($arParams["order_price"]) > 0) {
            $arrReq = array();
            $arrReq['pg_merchant_id'] = MERCHANT_ID;// Идентификатор магазина
            $arrReq['pg_amount']      = $arParams["order_price"];		// Сумма заказа
            $arrReq['pg_description'] = 'Оплата заказа #' . $arParams["order_number"]; // Описание заказа (показывается в Платёжной системе)
            $arrReq['pg_salt'] = rand(21,43433);
            $arrReq['pg_sig'] = \kDevelop\Service\Platron\PG_Signature::make('payment.php', $arrReq, MERCHANT_KEY);

            $arReturn["url"] = "https://www.platron.ru/payment.php?" . http_build_query($arrReq);
            $arReturn["msg"] = self::getMsg("INIT_PAYMENT_SUCCESS");
        }

        return $arReturn;
    }
}