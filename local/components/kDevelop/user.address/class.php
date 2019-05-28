<?
class UserAddress extends CBitrixComponent
{
    private static $userPropType = "UserID";

    private $defFieldCode = ["ID", "NAME", "IBLOCK_ID"];

    /**
     * SiteList constructor.
     * @param null $component
     */
    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    /**
     * @param $params
     * @return array
     */
    public function onPrepareComponentParams($params)
    {
        $params["SORT_BY"] = isset($params["SORT_BY"]) ? $params["SORT_BY"] : "ID";
        $params["SORT_ORDER"] = isset($params["SORT_ORDER"]) ? $params["SORT_ORDER"] : "ASC";
        if (!is_array($params["FIELD_CODE"])) {
            $params["FIELD_CODE"] = [];
        }
        $params["FIELD_CODE"] = array_unique(array_merge($params["FIELD_CODE"], $this->defFieldCode));
        $params["CACHE_TIME"] = isset($params["CACHE_TIME"]) ? $params["CACHE_TIME"] : 36000000;

        return $params;
    }

    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        global $USER;

        if (strlen($this->arParams["USER_PROP_CODE"]) == 0 || intval($this->arParams["IBLOCK_ID"]) == 0) return;
        if (!$this->checkUserField($this->arParams["USER_PROP_CODE"])) return;

        $hasProps = is_array($this->arParams["PROPERTY_CODE"]) && count($this->arParams["PROPERTY_CODE"]) > 0;
        $hasAllProps = false;
        if ($hasProps) {
            $hasAllProps = $this->arParams["PROPERTY_CODE"][0] == "*";
        }
        $rsItems = \CIblockElement::getList(
            [$this->arParams["SORT_BY"] => $this->arParams["SORT_ORDER"]],
            [
                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                "ACTIVE" => "Y",
                "PROPERTY_".$this->arParams["USER_PROP_CODE"] => $USER->GetId()
            ],
            false,
            false,
            $this->arParams["FIELD_CODE"]
        );
        while ($obItem = $rsItems->GetNextElement()) {
            $arItem = $obItem->getFields();
            if ($hasProps) {
                $arProps = $obItem->getProperties();
                foreach ($arProps as $arProp) {
                    if (in_array($arProp["CODE"], $this->arParams["PROPERTY_CODE"]) || $hasAllProps) {
                        $arItem["PROPERTIES"][$arProp["CODE"]] = $arProp;
                    }
                }
            }
            $this->arResult["ITEMS"][] = $arItem;
        }

        $this->includeComponentTemplate();
    }

    public static function add()
    {

    }

    /**
     * @param $iblockId
     * @param array $arSelect
     * @return array
     */
    public static function getFields($iblockId, array $arSelect = ["NAME"])
    {
        $arReturn = [];
        $arFields = \CIBlock::GetFields($iblockId);
        foreach ($arFields as $code => $arField) {
            if (in_array($code, $arSelect)) {
                $arField["CODE"] = $code;
                $arReturn[] = $arField;
            }
        }
        $rsProp = \Bitrix\Iblock\PropertyTable::getList([
            "filter" => ["IBLOCK_ID" => $iblockId, "ACTIVE" => "Y"],
            "select" => ["*"]
        ]);
        while ($arProp = $rsProp->fetch()) {
            $arProp["IS_PROPERTY"] = "Y";
            if ($arProp["USER_TYPE"] == self::$userPropType) {
                $arProp["USER_PROP"] = "Y";
            }
            $arReturn[] = $arProp;
        }

        return $arReturn;
    }

    /**
     * @return bool
     */
    public static function getUserField()
    {
        if ($arProp = \Bitrix\Iblock\PropertyTable::getList([
            "filter" => ["USER_TYPE" => self::$userPropType, "ACTIVE" => "Y"],
            "select" => ["*"]
        ])->fetch()) {
            return $arProp;
        }

        return false;
    }

    /**
     * @param $propCode
     * @return bool
     */
    private static function checkUserField($propCode)
    {
        if ($arProp = \Bitrix\Iblock\PropertyTable::getList([
            "filter" => ["CODE" => $propCode, "ACTIVE" => "Y"],
            "select" => ["*"]
        ])->fetch()) {
            return $arProp["USER_TYPE"] == self::$userPropType;
        }

        return false;
    }
}