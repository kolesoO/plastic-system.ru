<?
namespace kDevelop\Ajax;

class Component
{
    private static $rsClass = null;

    /**
     * @param array $arParams
     * @return mixed
     */
    public static function includeClass(array $arParams)
    {
        $className = \CBitrixComponent::includeComponentClass($arParams["comp_name"]);
        self::$rsClass = new $className();
        if (is_callable([self::class, $arParams["method_name"]], true, $callableName)) {
            return $callableName($arParams);
        }
    }

    /**
     * @param array $arParams
     * @return array
     */
    public static function getUserAddressForm(array $arParams)
    {
        global $APPLICATION;

        $arFields = [];
        $arItem = null;
        $arSelect = ["NAME"];

        //fields
        if (is_callable([self::$rsClass, "getFields"], true, $callableName)) {
            $arFields = $callableName(IBLOCK_SERVICE_USER_ADDRESS, $arSelect);
        }
        //end

        //element
        if (intval($arParams["id"]) > 0) {
            $rsElem = \CIblockElement::GetList(
                [],
                ["IBLOCK_ID" => IBLOCK_SERVICE_USER_ADDRESS, "ID" => $arParams["id"]],
                false,
                false,
                array_unique(array_merge($arSelect, ["ID", "IBLOCK_ID"]))
            );
            if ($obElem = $rsElem->GetNextElement()) {
                $arItem = $obElem->getFields();
                $arItem["PROPERTIES"] = $obElem->getProperties();
            }
        }
        //end

        ob_start();
        $APPLICATION->IncludeComponent(
            "kDevelop:blank",
            "user.address-form",
            [
                "FIELDS" => $arFields,
                "ITEM" => $arItem,
                "WRAP_ID" => $arParams["target_id"],
                "IBLOCK_ID" => IBLOCK_SERVICE_USER_ADDRESS
            ],
            null,
            ['HIDE_ICONS' => 'Y']
        );
        $return = ob_get_contents();
        ob_end_clean();

        return [
            "js_callback" => "getUserAddressFormCallBack",
            "html" => $return
        ];
    }

    /**
     * @param array $arParams
     * @return array
     */
    public static function getUserAddressData(array $arParams)
    {
        global $APPLICATION;

        $arReturn = ["js_callback" => (isset($arParams["js_callback"]) ? $arParams["js_callback"] : "getUserAddressDataCallBack")];
        $arItem = [];
        $arSelect = ["NAME"];
        $rsElem = \CIblockElement::GetList(
            [],
            ["IBLOCK_ID" => IBLOCK_SERVICE_USER_ADDRESS, "ID" => $arParams["id"]],
            false,
            false,
            array_unique(array_merge($arSelect, ["ID", "IBLOCK_ID"]))
        );
        if ($obElem = $rsElem->GetNextElement()) {
            $arItem = $obElem->getFields();
            $arItem["PROPERTIES"] = $obElem->getProperties();
            $arItem["WRAP_ID"] = $arParams["target_id"];
            $arItem["CAN_CHANGE"] = "Y";
        }

        if ($arParams["is_json"]) {
            $arReturn = array_merge($arReturn, $arItem);
        } else {
            ob_start();
            $APPLICATION->IncludeComponent(
                "kDevelop:blank",
                "user.address-item",
                $arItem,
                null,
                ['HIDE_ICONS' => 'Y']
            );
            $return = ob_get_contents();
            ob_end_clean();
            $arReturn["html"] = $return;
        }

        return $arReturn;
    }

    /**
     * @return array
     */
    public static function getUserAddressList()
    {
        global $APPLICATION;

        ob_start();
        $APPLICATION->IncludeComponent(
            "kDevelop:user.address",
            "",
            [
                "USER_PROP_CODE" => "USER_ID",
                "IBLOCK_ID" => IBLOCK_SERVICE_USER_ADDRESS,
                "FIELD_CODE" => ["NAME"],
                "PROPERTY_CODE" => ["PHONE", "EMAIL", "ADDRESS"],
                "CAN_SELECT" => "Y"
            ]
        );
        $return = ob_get_contents();
        ob_end_clean();

        return [
            "js_callback" => "getUserAddressListCallBack",
            "html" => $return
        ];
    }

    /**
     * @param array $arParams
     * @return array
     */
    public static function getDelivery(array $arParams)
    {
        global $APPLICATION;

        ob_start();
        $APPLICATION->IncludeComponent(
            "kDevelop:catalog.delivery",
            "",
            [
                "LOCATION_NAME" => $arParams["form_data"]["city"],
                "LOCATION_ADDRESS" => $arParams["form_data"]["address"]
            ]
        );
        $return = ob_get_contents();
        ob_end_clean();

        return [
            "js_callback" => "getDeliveryCallBack",
            "html" => $return
        ];
    }
}