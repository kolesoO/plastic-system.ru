<?php
namespace kDevelop\Help;

use CUtil as CUtilBase;

/**
 * Class Util
 */
class Util extends CUtilBase{

    private static function mb_strtr($str, $from, $to)
    {
        return str_replace(self::mb_str_split($from), self::mb_str_split($to), $str);
    }
    private static function mb_str_split($str) {
        return preg_split('~~u', $str, null, PREG_SPLIT_NO_EMPTY);
    }

    private static function ToUpper($str, $lang = false)
    {
        static $lower = array();
        static $upper = array();
        if(!defined("BX_CUSTOM_TO_UPPER_FUNC"))
        {
            if(defined("BX_UTF"))
            {
                return mb_strtoupper($str);
            }
            else
            {
                if($lang === false)
                    $lang = LANGUAGE_ID;
                if(!isset($lower[$lang]))
                {
                    $arMsg = IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/tools.php", $lang, true);
                    $lower[$lang] = $arMsg["ABC_LOWER"];
                    $upper[$lang] = $arMsg["ABC_UPPER"];
                }
                return mb_strtoupper(self::mb_strtr($str, $lower[$lang], $upper[$lang]));
            }
        }
        else
        {
            $func = BX_CUSTOM_TO_UPPER_FUNC;
            return $func($str);
        }
    }

    private static function ToLower($str, $lang = false)
    {
        static $lower = array();
        static $upper = array();
        if(!defined("BX_CUSTOM_TO_LOWER_FUNC"))
        {
            if(defined("BX_UTF"))
            {
                return mb_strtolower($str);
            }
            else
            {
                if($lang === false)
                    $lang = LANGUAGE_ID;
                if(!isset($lower[$lang]))
                {
                    $arMsg = IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/tools.php", $lang, true);
                    $lower[$lang] = $arMsg["ABC_LOWER"];
                    $upper[$lang] = $arMsg["ABC_UPPER"];
                }
                return mb_strtolower(self::mb_strtr($str, $upper[$lang], $lower[$lang]));
            }
        }
        else
        {
            $func = BX_CUSTOM_TO_LOWER_FUNC;
            return $func($str);
        }
    }



    public static function translit($str, $lang, $params = array())
    {
        static $search = array();

        if(!isset($search[$lang]))
        {
            $mess = IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/js_core_translit.php", $lang, true);
            $trans_from = explode(",", $mess["TRANS_FROM"]);
            $trans_to = explode(",", $mess["TRANS_TO"]);
            foreach($trans_from as $i => $from)
                $search[$lang][$from] = $trans_to[$i];
        }

        $defaultParams = array(
            "max_len" => 100,
            "change_case" => "L", // 'L' - toLower, 'U' - toUpper, false - do not change
            "replace_space" => '_',
            "replace_other" => '_',
            "delete_repeat_replace" => true,
            "safe_chars" => '',
        );
        foreach($defaultParams as $key => $value)
            if(!array_key_exists($key, $params))
                $params[$key] = $value;

        $len = mb_strlen($str);
        $str_new = '';
        $last_chr_new = '';

        for($i = 0; $i < $len; $i++)
        {
            $chr = mb_substr($str, $i, 1, "UTF-8");

            if(preg_match("/[a-zA-Z0-9]/".BX_UTF_PCRE_MODIFIER, $chr) || mb_strpos($params["safe_chars"], $chr)!==false)
            {
                $chr_new = $chr;
            }
            elseif(preg_match("/\\s/".BX_UTF_PCRE_MODIFIER, $chr))
            {
                if (
                    !$params["delete_repeat_replace"]
                    ||
                    ($i > 0 && $last_chr_new != $params["replace_space"])
                )
                    $chr_new = $params["replace_space"];
                else
                    $chr_new = '';
            }
            else
            {
                if($search[$lang][$chr])
                {
                    $chr_new = $search[$lang][$chr];
                }
                else
                {
                    if (
                        !$params["delete_repeat_replace"]
                        ||
                        ($i > 0 && $i != $len-1 && $last_chr_new != $params["replace_other"])
                    )
                        $chr_new = $params["replace_other"];
                    else
                        $chr_new = '';
                }
            }

            if(mb_strlen($chr_new))
            {
                if($params["change_case"] == "L" || $params["change_case"] == "l")
                    $chr_new = self::ToLower($chr_new);
                elseif($params["change_case"] == "U" || $params["change_case"] == "u")
                    $chr_new = self::ToUpper($chr_new);

                $str_new .= $chr_new;
                $last_chr_new = $chr_new;
            }

            if (mb_strlen($str_new) >= $params["max_len"])
                break;
        }
        $str_new = trim($str_new, $params['replace_space'] . $params['replace_other']);

        return $str_new;
    }
}
