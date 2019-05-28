<?
namespace kDevelop\Ajax;

class Favorite
{
    use MsgHandBook;

    private static $sessionKey = "FAVORITE";

    /**
     * @param $id
     * @return bool
     */
    public static function isAdded($id)
    {
        if (!$_SESSION[self::$sessionKey]) {
            return false;
        }

        return in_array($id, $_SESSION[self::$sessionKey]);
    }

    /**
     * @param array $arParams
     * @return array
     */
    public static function add(array $arParams)
    {
        $arReturn = [
            "js_callback" => "addToFavoriteCallBack",
            "is_added" => false,
            "msg" => self::getMsg("ADD_TO_FAVORITE_ERROR")
        ];
        $arParams["id"] = intval($arParams["id"]);
        if ($arParams["id"] > 0 && !self::isAdded($arParams["id"])) {
            $_SESSION[self::$sessionKey][] = $arParams["id"];
            $arReturn["is_added"] = true;
            $arReturn["msg"] = self::getMsg("ADD_TO_FAVORITE_SUCCESS");
        }

        return $arReturn;
    }

    /**
     * @param array $arParams
     * @return array
     */
    public static function delete(array $arParams)
    {
        $arReturn = [
            "js_callback" => "deleteFromFavoriteCallBack",
            "is_deleted" => false,
            "msg" => self::getMsg("DELETE_FROM_FAVORITE_ERROR")
        ];
        $arParams["id"] = intval($arParams["id"]);
        if ($arParams["id"] > 0 && self::isAdded($arParams["id"])) {
            foreach ($_SESSION[self::$sessionKey] as &$id) {
                if ($id == $arParams["id"]) {
                    unset($id);
                    break;
                }
            }
            unset($id);
            $arReturn["is_deleted"] = true;
            $arReturn["msg"] = self::getMsg("DELETE_FROM_FAVORITE_SUCCESS");
        }

        return $arReturn;
    }
}