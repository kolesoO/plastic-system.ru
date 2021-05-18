<?
namespace kDevelop\Settings;

use Bitrix\Main\Loader;
use CCatalogGroup;
use CCatalogStore;
use CCurrency;

class Store
{
    /**
     *
     */
    public static function setStore()
    {
        if (!Loader::includeModule("catalog")) return;

        [$storeId, $priceId, $currencyId] = self::getStoreInfo([
            'ACTIVE' => 'Y',
            'SITE_ID' => SITE_ID,
        ]);

        if (!$currencyId) {
            $currencyId = self::getDefaultCurrencyId();
        }

        define("STORE_ID", $storeId);
        define("CURRENCY_ID", $currencyId);
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
            ["ID", "UF_PRICE_ID", "UF_CURRENCY"]
        );

        if ($arStore = $rsStore->fetch()) {
            return [$arStore['ID'], $arStore["UF_PRICE_ID"], $arStore["UF_CURRENCY"]];
        }

        return self::getStoreInfo([
            'ACTIVE' => 'Y',
            'DEFAULT' => 'Y',
        ]);
    }

    private static function getDefaultCurrencyId(): ?string
    {
        $rs = CCurrency::GetList(($by="name"), ($order="asc"));

        while ($currency = $rs->fetch()) {
            if ($currency['BASE'] === 'Y') {
                return $currency['CURRENCY'];
            }
        }

        return null;
    }
}
