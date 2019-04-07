<?
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

        return $params;
    }

    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }
}