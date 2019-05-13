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
                    "bitrix:sale.personal.order.list",
                    (DEVICE_TYPE == "MOBILE" ? ".default-mobile" : ".default"),
                    Array(
                        "STATUS_COLOR_N" => "green",
                        "STATUS_COLOR_P" => "yellow",
                        "STATUS_COLOR_F" => "gray",
                        "STATUS_COLOR_PSEUDO_CANCELLED" => "red",
                        "PATH_TO_DETAIL" => "order_detail.php?ID=#ID#",
                        "PATH_TO_COPY" => "basket.php",
                        "PATH_TO_CANCEL" => "order_cancel.php?ID=#ID#",
                        "PATH_TO_BASKET" => "basket.php",
                        "PATH_TO_PAYMENT" => "payment.php",
                        "ORDERS_PER_PAGE" => 20,
                        "ID" => "",
                        "SET_TITLE" => "N",
                        "SAVE_IN_SESSION" => "Y",
                        "NAV_TEMPLATE" => "",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600",
                        "CACHE_GROUPS" => "Y",
                        "HISTORIC_STATUSES" => "F",
                        "ACTIVE_DATE_FORMAT" => "d.m.Y"
                    )
                );?>
            </div>
        </div>
    </div>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');