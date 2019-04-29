<?
namespace kDevelop\Ajax;

trait MsgHandBook
{
    private static $defMsg = "Неизвестная ошибка";

    private static $arErrorsList = [
        "CALL_METHOD_ERROR" => "Невозможно вызвать метод",
        "ITEMS_NOT_FOUND" => "Товары не найдены",
        "PRICE_TYPE_NOT_FOUND" => "Не найден тип цены",
        "ITEMS_NOT_AVAILABLE" => "Товар доступен только под заказ",
        "ADD_TO_BASKET_SUCCESS" => "Товар добавлен в корзину",
        "ADD_TO_BASKET_ERROR" => "Ошибка при добавлении в корзину",
        "MODULE_SALE_NOT_INSTALLED" => "Модуль \"Sale\" не установлен",
        "REG_FIELDS_EMPTY" => "Необходимо заполнить все обязательные поля",
        "PAS_AND_CONF_PAS_NOT_EQ" => "Подтверждение пароля не совпадает с основным паролем"
    ];

    /**
     * @param $code
     * @param string $suffix
     * @param string $msg
     * @return string
     */
    protected static function getMsg($code, $suffix = "", $msg = "")
    {
        if (strlen($msg) == 0) {
            $msg = isset(self::$arErrorsList[$code]) ? self::$arErrorsList[$code] : self::$defMsg;
        }
        if (strlen($suffix) > 0) {
            $msg .= " (".$suffix.")";
        }

        return $msg;
    }
}