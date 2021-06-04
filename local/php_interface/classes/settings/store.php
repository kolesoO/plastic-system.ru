<?
namespace kDevelop\Settings;

use Bitrix\Catalog\Product\Price\Calculation;
use Bitrix\Main\Loader;
use CCatalogGroup;
use CCatalogStore;
use CCurrency;

class Store
{
    public static function setStore()
    {
        if (!Loader::includeModule("catalog")) return;

        [$storeId, $priceId, $currencyId] = self::getFirstStoreInfo([
            'ACTIVE' => 'Y',
            'SITE_ID' => SITE_ID,
        ]);

        if (!$currencyId) {
            $currencyId = self::getDefaultCurrencyId();
        }

        define("STORE_ID", $storeId);
        define("CURRENCY_ID", $currencyId);

        Calculation::setConfig(['CURRENCY' => CURRENCY_ID]);

        self::setPrice($priceId);
    }

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
     * @param array $filter
     * @return array[]
     */
    public static function getStoresInfo(array $filter): array
    {
        $result = [];

        $rsStore = CCatalogStore::GetList(
            ["SORT" => "ASC"],
            $filter,
            false,
            false,
            ["ID", "UF_PRICE_ID", "UF_CURRENCY", "SITE_ID"]
        );

        while ($arStore = $rsStore->fetch()) {
            $result[] = [
                $arStore['ID'],
                $arStore["UF_PRICE_ID"],
                $arStore["UF_CURRENCY"],
                $arStore['SITE_ID'],
            ];
        }

        return $result;
    }

    public static function getDefaultCurrencyId(): ?string
    {
        $rs = CCurrency::GetList(($by="name"), ($order="asc"));

        while ($currency = $rs->fetch()) {
            if ($currency['BASE'] === 'Y') {
                return $currency['CURRENCY'];
            }
        }

        return null;
    }

    /**
     * @param mixed[] $filter
     * @return mixed[]
     */
    private static function getFirstStoreInfo(array $filter): array
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

        return self::getFirstStoreInfo([
            'ACTIVE' => 'Y',
            'DEFAULT' => 'Y',
        ]);
    }
}
