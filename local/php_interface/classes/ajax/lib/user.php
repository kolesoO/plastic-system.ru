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
        global $APPLICATION;

        $arReturn = ["js_callback" => "userRegisterCallBack"];

        if ($arFields["PASSWORD"] != $arFields["CONFIRM_PASSWORD"]) {
            $arReturn["error_msg"][] = self::getMsg("PAS_AND_CONF_PAS_NOT_EQ");

            return $arReturn;
        }

        if (
            \COption::GetOptionString("main", "captcha_registration", "N") == "Y"
            && !$APPLICATION->CaptchaCheckCode($arFields["captcha_word"], $arFields["captcha_sid"])
        ) {
            $arReturn["error_msg"][] = self::getMsg("REGISTER_WRONG_CAPTCHA");
            $arReturn["captcha_code"] = htmlspecialcharsbx($APPLICATION->CaptchaGetCode());
            $arReturn["captcha_img"] = "/bitrix/tools/captcha.php?captcha_sid=" . $arReturn["captcha_code"];

            return $arReturn;
        }

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

        return $arReturn;
    }
}
