<?
use Bitrix\Main\Loader,
    Bitrix\Sale\Location\LocationTable;

class CatalogDelivery extends CBitrixComponent
{
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
        $params["CACHE_TIME"] = isset($params["CACHE_TIME"]) ? $params["CACHE_TIME"] : 36000000;
        $params["LOCATION_ID"] = intval($params["LOCATION_ID"]);

        return $params;
    }

    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        if (!Loader::includeModule("sale")) return;

        //prepare filter
        $filter = [];
        if ($this->arParams["LOCATION_ID"] > 0) {
            $filter = ["ID" => $this->arParams["LOCATION_ID"]];
        } elseif (strlen($this->arParams["LOCATION_NAME"]) > 0) {
            $filter = ['NAME.NAME_UPPER' => $this->arParams["LOCATION_NAME"]];
        }
        //end

        if (count($filter) > 0) {
            if ($arLocation = LocationTable::getList(array(
                'select' => array("*", "NAME_RU" => "NAME.NAME"),
                'filter' => $filter,
            ))->fetch()) {
                $this->arResult["LOCATION"] = $arLocation;
                $this->arResult["ITEMS"] = $this->getDeliveryByLocation($this->arResult["LOCATION"]["ID"]);
                foreach ($this->arResult["ITEMS"] as &$arDelivery) {
                    if (!empty($arDelivery["STORE"])) {
                        $arDelivery["HAS_STORE"] = "Y";
                        $rsStore = \CCatalogStore::GetList(
                            ["SORT" => "ASC"],
                            [
                                "ACTIVE"=>"Y",
                                "ID" => unserialize($arDelivery["STORE"]),
                                "UF_CITY_NAME" => $this->arResult["LOCATION"]["NAME_RU"]
                            ],
                            false,
                            false,
                            ["*"]
                        );
                        while ($arStore = $rsStore->fetch()) {
                            $arDelivery["STORE_ITEMS"][] = $arStore;
                        }
                    }
                }
                unset($arDelivery);
            }
        }

        $this->includeComponentTemplate();
    }

    /**
     * @param $locationId
     * @return array
     */
    public function getDeliveryByLocation($locationId)
    {
        $return = [];
        $rsDelivery = CSaleDelivery::GetList(
            [],
            [
                "LID" => SITE_ID,
                "ACTIVE" => "Y",
                "LOCATION" => $locationId
            ]
        );
        while ($arDelivery = $rsDelivery->fetch()) {
            $return[] = $arDelivery;
        }

        return $return;
    }
}