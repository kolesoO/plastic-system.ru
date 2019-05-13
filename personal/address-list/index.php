<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
if (!$USER->isAuthorized()) {
    LocalRedirect("/", true, 301);
}
$APPLICATION->SetPageProperty("header_section-class", "section");
$APPLICATION->SetTitle("Личный кабинет");
?>

    <div class="block_wrapper">
        <div class="personal">
            <?$APPLICATION->IncludeComponent(
                "bitrix:menu",
                "left",
                [
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
                ]
            );?>
            <div class="personal_content">
                <?$APPLICATION->IncludeComponent(
                    "kDevelop:user.address",
                    "",
                    [
                        "USER_PROP_CODE" => "USER_ID",
                        "IBLOCK_ID" => IBLOCK_SERVICE_USER_ADDRESS,
                        "FIELD_CODE" => ["NAME"],
                        "PROPERTY_CODE" => ["PHONE", "EMAIL", "ADDRESS"],
                        "CAN_CHANGE" => "Y",
                        "CAN_CREATE" => "Y"
                    ]
                );?>
            </div>
        </div>
    </div>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');