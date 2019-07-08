<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("keywords", "");
$APPLICATION->SetPageProperty("description", "Способы доставки 1. Самовывоз со склада в городе: в Санкт-Петербурге по адресу: ул.Рабочая, д.16Г в Москве по адресу:  М.О., г. Дзержинский, ул. Алексеевская, д. 2 в Самаре по адресу: Заводское шоссе, д.17Д в Саратове по адресу: ул. Танкистов д. 37 в Ростове-на-Дону по адресу: ул. Менжинского, д.4Г в Краснодаре по адресу: ул.Новороссийская, д.210 во Владивостоке по адресу: ул. Русская &hellip;");
$APPLICATION->SetPageProperty("title", "Доставка и оплата - Складские лотки и пластиковая тара от компании ООО «Пластик Система»");
$APPLICATION->SetPageProperty("header_section-class", "section");
$APPLICATION->SetTitle("Доставка и оплата");
?>

<div class="block_wrapper big">
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        ".default",
        [
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/title-2.php"
        ],
        false
    );?>
    <hr>
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        ".default",
        [
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/pickup.php"
        ],
        false
    );?>
    <div flex-align="start">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            ".default",
            [
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/pickup-desc.php"
            ],
            false
        );?>
    </div>
    <hr>
    <div class="js-tabs">
        <div class="content_tab">
            <a href="#" class="content_tab-item" data-tab_target="#delivery_region-spb">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    ".default",
                    [
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/tabs/spb-title.php"
                    ],
                    false
                );?>
            </a>
            <a href="#" class="content_tab-item" data-tab_target="#delivery_region-msk">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    ".default",
                    [
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/tabs/msk-title.php"
                    ],
                    false
                );?>
            </a>
            <a href="#" class="content_tab-item" data-tab_target="#delivery_region-other">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    ".default",
                    [
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/tabs/other-title.php"
                    ],
                    false
                );?>
            </a>
        </div>
        <div data-tab_content>
            <div id="delivery_region-spb" data-tab_item>
                <div flex-align="start">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        ".default",
                        [
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/tabs/spb-content.php"
                        ],
                        false
                    );?>
                </div>
                <div style="overflow-x:auto">
                    <img src="/upload/content/delivery/spb.svg" style="width: 1209px;max-width: none;">
                </div>
            </div>
            <div id="delivery_region-msk" data-tab_item>
                <div flex-align="start">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        ".default",
                        [
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/tabs/msk-content.php"
                        ],
                        false
                    );?>
                </div>
                <div style="overflow-x:auto">
                    <img src="/upload/content/delivery/msk.svg" style="width: 1209px;max-width: none;">
                </div>
            </div>
            <div id="delivery_region-other" data-tab_item>
                <div flex-align="start">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        ".default",
                        [
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/tabs/other-content.php"
                        ],
                        false
                    );?>
                </div>
            </div>
        </div>
    </div>
    <?
    //Список регионов доставки
    /*$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        (DEVICE_TYPE == "DESKTOP" ? "delivery_region-list-desktop" : "delivery_region-list-mobile"),
        [
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "AJAX_MODE" => "N",
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => IBLOCK_CONTENT_DELIVERY_REGIONS,
            "NEWS_COUNT" => "30",
            "SORT_BY1" => "SORT",
            "SORT_ORDER1" => "ASC",
            "SORT_BY2" => "ID",
            "SORT_ORDER2" => "ASC",
            "FILTER_NAME" => "",
            "FIELD_CODE" => Array("ID", "NAME", "PREVIEW_TEXT", "DETAIL_TEXT"),
            "PROPERTY_CODE" => Array("COORDS", "POLYGON_COORDS", "PRICE", "POLYGON_COLOR"),
            "CHECK_DATES" => "N",
            "DETAIL_URL" => "",
            "PREVIEW_TRUNCATE_LEN" => "",
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "SET_TITLE" => "N",
            "SET_BROWSER_TITLE" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_LAST_MODIFIED" => "Y",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
            "PARENT_SECTION" => "",
            "PARENT_SECTION_CODE" => "",
            "INCLUDE_SUBSECTIONS" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "CACHE_FILTER" => "Y",
            "CACHE_GROUPS" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "Новости",
            "PAGER_SHOW_ALWAYS" => "Y",
            "PAGER_TEMPLATE" => "",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "Y",
            "PAGER_BASE_LINK_ENABLE" => "Y",
            "SET_STATUS_404" => "N",
            "SHOW_404" => "Y",
            "MESSAGE_404" => "",
            "PAGER_BASE_LINK" => "",
            "PAGER_PARAMS_NAME" => "arrPager",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_ADDITIONAL" => ""
        ]
    );*/
    //end
    $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        ".default",
        [
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/tabs/delivery-for-all-russia.php"
        ],
        false
    );
    ?>
</div>
<div class="block_wrapper big">
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        ".default",
        [
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/types-title.php"
        ],
        false
    );?>
    <hr>
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        ".default",
        [
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/types-content.php"
        ],
        false
    );?>
    <hr>
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        ".default",
        [
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_TEMPLATE_PATH . "/include/shipping/online-payment-info.php"
        ],
        false
    );
    //Список способов оплаты
    $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "payment-list",
        [
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "AJAX_MODE" => "N",
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => IBLOCK_CONTENT_PAYMENT,
            "NEWS_COUNT" => "30",
            "SORT_BY1" => "SORT",
            "SORT_ORDER1" => "ASC",
            "SORT_BY2" => "ID",
            "SORT_ORDER2" => "ASC",
            "FILTER_NAME" => "",
            "FIELD_CODE" => Array("ID", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_TEXT"),
            "PROPERTY_CODE" => Array(),
            "CHECK_DATES" => "N",
            "DETAIL_URL" => "",
            "PREVIEW_TRUNCATE_LEN" => "",
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "SET_TITLE" => "N",
            "SET_BROWSER_TITLE" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_LAST_MODIFIED" => "Y",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
            "PARENT_SECTION" => "",
            "PARENT_SECTION_CODE" => "",
            "INCLUDE_SUBSECTIONS" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "CACHE_FILTER" => "Y",
            "CACHE_GROUPS" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "Новости",
            "PAGER_SHOW_ALWAYS" => "Y",
            "PAGER_TEMPLATE" => "",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "Y",
            "PAGER_BASE_LINK_ENABLE" => "Y",
            "SET_STATUS_404" => "N",
            "SHOW_404" => "Y",
            "MESSAGE_404" => "",
            "PAGER_BASE_LINK" => "",
            "PAGER_PARAMS_NAME" => "arrPager",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "IMAGE_SIZE" => ["WIDTH" => 51, "HEIGHT" => 55]
        ]
    );
    //end
    ?>
</div>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');