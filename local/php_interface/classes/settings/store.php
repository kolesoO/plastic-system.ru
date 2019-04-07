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
        $isStoreExists = false;
        $arStoreIdList = [];
        if (isset($_COOKIE["store_id"]) && strlen($_COOKIE["store_id"]) > 0) {
            $storeId = $_COOKIE["store_id"];
        }
        if (\Bitrix\Main\Loader::includeModule("catalog")) {
            $rsStore = \CCatalogStore::GetList(
                ["SORT" => "ASC"],
                ["ACTIVE"=>"Y"],
                false,
                false,
                ["ID"]
            );
            while ($arStore = $rsStore->fetch()) {
                $arStoreIdList[] = $arStore["ID"];
                if ($arStore["ID"] == $storeId) {
                    $isStoreExists = true;
                    break;
                }
            }
        }
        if (count($arStoreIdList) > 0) {
            if (!$isStoreExists) {
                $storeId = $arStoreIdList[0];
            }
            define("STORE_ID", $storeId);
        }
    }
}