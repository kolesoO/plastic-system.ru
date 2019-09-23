<?
namespace kDevelop\Ajax;

class Address
{
    const LIMIT = 10;

    const REMOTE_DOMAIN = "https://kladr-api.ru/";

    const DEF_CONTENT_TYPE = \Kladr\ObjectType::City;

    /**
     * @param array $arParams
     * @return array
     */
    public static function getList(array $arParams)
    {
        $return = [];
        $cityID = 0;
        $rsApi = new \Kladr\Api("51dfe5d42fb2b43e3300006e", "86a2c2a06f1b2451a87d05512cc2c3edfdf41969", self::REMOTE_DOMAIN);
        $query = new \Kladr\Query();

        if (isset($arParams["parent"]) && strlen($arParams["parent"]) > 0) {
            $query->ContentName = $arParams["parent"];
            $query->ContentType = \Kladr\ObjectType::City;
            $query->WithParent = true;
            $query->Limit = 1;
            if($return = $rsApi->QueryToArray($query)){
                if (isset($return[1])) {
                    $cityID = intval($return[1]["id"]);
                }
            }
        }

        $return = [];
        $query->ContentName = $arParams["query"];
        $query->ContentType = isset($arParams["type"]) ? $arParams["type"] : self::DEF_CONTENT_TYPE;
        $query->Limit = self::LIMIT;
        $query->WithParent = false;
        if ($cityID > 0) {
            $query->ParentType = \Kladr\ObjectType::City;
            $query->ParentId = $cityID;
            $query->WithParent = true;
        }

        $result = $rsApi->QueryToArray($query);
        foreach ($result as $item) {
            if (strlen($item["type"]) == 0) continue;
            $return[] = $item;
        }

        return $return;
    }
}