<?
namespace kDevelop\Help;

use \Bitrix\Main\Loader;

class Tools
{
    /**
     * @var string
     */
    private static $offerPrefixInUrl = "offer-";

    /**
     * @var string
     */
    private static $offerSefUrlTmp = "#SKU_CODE#";

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    public static function definders()
    {
        Loader::includeModule('iblock');
        Loader::includeModule('form');
        Loader::includeModule('highloadblock');

        $rsResult = \Bitrix\Iblock\IblockTable::getList([
            'select' => ['ID', 'IBLOCK_TYPE_ID', 'CODE']
        ]);
        while ($row = $rsResult->fetch()) {
            $CONSTANT = ToUpper(implode('_', ['IBLOCK', $row['IBLOCK_TYPE_ID'], $row['CODE']]));
            if (!defined($CONSTANT)) {
                define($CONSTANT, $row['ID']);
            }
        }

        $rsForms = \CForm::GetList($by = "s_id", $order = "desc", [], ($is_filtered = null));
        while ($arForm = $rsForms->Fetch()) {
            $CONSTANT = ToUpper(implode('_', ['WEB_FORM', $arForm['SID']]));
            if (!defined($CONSTANT)) {
                define($CONSTANT, $arForm['ID']);
            }
        }
    }

    /**
     *
     */
    public static function setDeviceType()
    {
        $obDetect = new Mobile_Detect();
        if ($obDetect->isTablet()) {
            $deviceType = "TABLET";
        } elseif ($obDetect->isMobile()) {
            $deviceType = "MOBILE";
        } else {
            $deviceType = "DESKTOP";
        }
        define('DEVICE_TYPE', $deviceType);
    }

    /**
     * @param $qnt
     * @param $msgPrefix
     * @return array
     */
    public static function getQntInfo($qnt, $msgPrefix)
    {
        $qnt = intval($qnt);
        $defValue = defined("CATALOG_BORDER_QNT") ? CATALOG_BORDER_QNT : 10;
        $arReturn = [
            "CLASS" => "red",
            "MSG_CODE" => $msgPrefix."_FEW"
        ];
        if ($qnt > $defValue) {
            $arReturn["CLASS"] = "green";
            $arReturn["MSG_CODE"] = $msgPrefix."_MANY";
        } else if ($qnt == 0) {
            $arReturn["CLASS"] = "white";
            $arReturn["MSG_CODE"] = $msgPrefix."_FOR_ORDER";
        }

        return $arReturn;
    }

    /**
     * @param $colorName
     * @return bool|mixed
     */
    public static function getOfferColor($colorName)
    {
        $arColors = [
            "Бронзовый" => "#cd7f32",
            "Жёлтый" => "#ffff00",
            "Зеленый" => "#008000",
            "Красный" => "#ff0000",
            "Прозрачный" => "transparent",
            "Синий" => "blue",
            "Бирюзовый" => "#40E0D0",
            "Коричневый" => "#A52A2A",
            "Серый" => "grey",
            "Голубой" => "#4195EA",
            "Черный" => "#424141",
            "Белый" => "#F5F6F9",
            "Бело-красный" => "#f19898",
            "Желтый" => "yellow",
            "Серый бак, красная крышка" => "#f3cfcf",
            "Оранжевый" => "orange"
        ];
        if (isset($arColors[$colorName])) {
            return $arColors[$colorName];
        }

        return false;
    }

    /**
     * @param $xmlId
     * @return bool|mixed
     */
    public static function getOfferStatusInfo($xmlId)
    {
        $arStatus = [
            "action" => [
                "BG_COLOR" => "#FF2429"
            ],
            "new" => [
                "BG_COLOR" => "#05A400"
            ]
        ];
        if (isset($arStatus[$xmlId])) {
            return $arStatus[$xmlId];
        }

        return false;
    }

    /**
     * @return string
     */
    public static function getOfferPrefixInUrl()
    {
        return self::$offerPrefixInUrl;
    }

    /**
     * @return string
     */
    public static function getOfferSefUrlTmp()
    {
        return self::$offerSefUrlTmp;
    }

    /**
     *
     */
    public static function defineAjax()
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"])) {
            if (strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
                include(__DIR__."/../../ajax/index.php");
            }
        }
    }
}