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
        "PAS_AND_CONF_PAS_NOT_EQ" => "Подтверждение пароля не совпадает с основным паролем",
        "ADD_TO_IBLOCK_ERROR" => "Ошибка при добавлении записи",
        "UPDATE_IN_IBLOCK_ERROR" => "Ошибка при обновлении записи",
        "IBLOCK_NOT_FOUND" => "Не найден инфоблок для записи",
        "ADD_TO_FAVORITE_ERROR" => "Ошибка при добавлении в избранное",
        "ADD_TO_FAVORITE_SUCCESS" => "Товар добавлен в избранное",
        "DELETE_FROM_FAVORITE_ERROR" => "Ошибка при удалении из избранного",
        "DELETE_FROM_FAVORITE_SUCCESS" => "Товар удален из избранного"
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