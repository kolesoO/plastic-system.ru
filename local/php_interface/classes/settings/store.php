<?
namespace kDevelop\Settings;

use Bitrix\Main\Loader;
use CCatalogGroup;
use CCatalogStore;
use \Rover\GeoIp\Location;

class Store
{
    /**
     *
     */
    public static function setStore()
    {
        if (!Loader::includeModule("catalog")) return;

        [$storeId, $priceId] = self::getStoreInfo([
            'ACTIVE' => 'Y',
            'SITE_ID' => SITE_ID,
        ]);

        define("STORE_ID", $storeId);
        self::setPrice($priceId);
    }

    /**
     * @param $id
     */
    public static function setPrice($id)
    {
        if ($arPrice = CCatalogGroup::GetList(
            [],
            ["ID" => $id],
            false,
            false,
            []
        )->fetch()) {
            define("PRICE_CODE", $arPrice["NAME"]);
            define("PRICE_ID", $arPrice["ID"]);
        }
    }

    /**
     * @return mixed[]
     */
    private static function getStoreInfo(array $filter): array
    {
        $rsStore = CCatalogStore::GetList(
            ["SORT" => "ASC"],
            $filter,
            false,
            false,
            ["ID", "UF_PRICE_ID"]
        );

        if ($arStore = $rsStore->fetch()) {
            return [$arStore['ID'], $arStore["UF_PRICE_ID"]];
        }

        return self::getStoreInfo([
            'ACTIVE' => 'Y',
            'DEFAULT' => 'Y',
        ]);
    }
}
