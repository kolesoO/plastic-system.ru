<?
namespace kDevelop\Ajax;

class User
{
    use MsgHandBook;

    /**
     * @param $arFields
     * @return array
     */
    public static function userRegister($arFields)
    {
        $arReturn = ["js_callback" => "userRegisterCallBack"];
        if ($arFields["PASSWORD"] == $arFields["CONFIRM_PASSWORD"]) {
            $rsUser = new \CUser();
            //Группа
            $arFields["GROUP_ID"] = explode(",", \COption::GetOptionString("main", "new_user_registration_def_group"));
            //end
            //Прочие данные
            $arFields["ACTIVE"] = "Y";
            $arFields["EMAIL"] = $arFields["LOGIN"];
            //end
            $arReturn["USER_ID"] = intval($rsUser->Add($arFields));
            if($arReturn["USER_ID"] == 0) {
                $arReturn["error_msg"][] = self::getMsg("", "", $rsUser->LAST_ERROR);
            } else {
                $rsUser->Authorize($arReturn["USER_ID"]);
            }
        } else {
            $arReturn["error_msg"][] = self::getMsg("PAS_AND_CONF_PAS_NOT_EQ");
        }

        return $arReturn;
    }
}