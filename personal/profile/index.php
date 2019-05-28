<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
if (!$USER->isAuthorized()) {
    LocalRedirect("/", true, 301);
}
$APPLICATION->SetPageProperty("header_section-class", "section");
$APPLICATION->SetTitle("Учетная запись");
?>

<div class="block_wrapper">
    <div class="personal">
        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "left",
            Array(
                "ROOT_MENU_TYPE" => "left",
                "MAX_LEVEL" => "1",
                "CHILD_MENU_TYPE" => "left",
                "USE_EXT" => "Y",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "Y",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MENU_CACHE_GET_VARS" => ""
            )
        );?>
        <div class="personal_content">
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.profile",
                "",
                Array(
                    "USER_PROPERTY_NAME" => "",
                    "SET_TITLE" => "N",
                    "AJAX_MODE" => "Y",
                    "USER_PROPERTY" => Array(),
                    "SEND_INFO" => "Y",
                    "CHECK_RIGHTS" => "Y",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N"
                )
            );?>
        </div>
    </div>
</div>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');