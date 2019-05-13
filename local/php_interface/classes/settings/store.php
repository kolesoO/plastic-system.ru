<?
namespace kDevelop\Settings;

class Store
{
    /**
     *
     */
    public static function setStore()
    {
        $storeId = "";
        $priceId = 0;
        $isStoreExists = false;
        $arStoreIdList = [];
        $arPriceIdList = [];
        if (isset($_COOKIE["store_id"]) && strlen($_COOKIE["store_id"]) > 0) {
            $storeId = $_COOKIE["store_id"];
        }
        if (\Bitrix\Main\Loader::includeModule("catalog")) {
            $rsStore = \CCatalogStore::GetList(
                ["SORT" => "ASC"],
                ["ACTIVE"=>"Y"],
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