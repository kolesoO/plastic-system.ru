<?
namespace kDevelop\Settings;

use \Rover\GeoIp\Location;

class Store
{
    /**
     *
     */
    public static function setStore()
    {
        if (!\Bitrix\Main\Loader::includeModule("catalog")) return;

        $storeId = "";
        $priceId = 0;
        $isStoreExists = false;
        $arStoreIdList = [];
        $arPriceIdList = [];
        $filter = ["ACTIVE"=>"Y"];
        if (isset($_COOKIE["store_id"]) && strlen($_COOKIE["store_id"]) > 0) {
            $storeId = $_COOKIE["store_id"];
        } elseif (\Bitrix\Main\Loader::includeModule("rover.geoip")) {
            $location = Location::getInstance(Location::getCurIp());
            $filter[] = [
                "LOGIC" => "OR",
                ["UF_CITY_NAME" => $location->getCityName()],
                ["!UF_CITY_NAME" => false]
            ];
        }

        $rsStore = \CCatalogStore::GetList(
            ["SORT" => "ASC"],
            $filter,
            false,
            false,
            ["ID", "UF_PRICE_ID", "ADDRESS"]
        );
        while ($arStore = $rsStore->fetch()) {
            $arStoreIdList[] = $arStore["ID"];
            $arPriceIdList[] = $arStore["UF_PRICE_ID"];
            if ($arStore["ID"] == $storeId) {
                $isStoreExists = true;
                $priceId = $arStore["UF_PRICE_ID"];
                break;
            }
        }

        if (count($arStoreIdList) > 0) {
            if (!$isStoreExists) {
                $storeId = $arStoreIdList[0];
                $priceId = $arPriceIdList[0];
            }
            define("STORE_ID", $storeId);
            self::setPrice($priceId);
        }
    }

    /**
     * @param $id
     */
    public static function setPrice($id)
    {
        if ($arPrice = \CCatalogGroup::GetList(
            [],
            ["ID" => $id],
            false,
            false,
            []
        )->fetch()) {
            define("PRICE_CODE", $arPrice["NAME"]);
        }
    }
}