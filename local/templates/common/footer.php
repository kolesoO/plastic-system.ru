<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

    <?if (!$isMainPage || (defined("NOT_CLOSE_SECTION_IN_FOOTER") && NOT_CLOSE_SECTION_IN_FOOTER != "Y")) :?>
        </div></section>
    <?endif?>

    <footer class="footer js-footer">
        <div class="footer-part">
            <div class="container">
                <div class="col-md-9">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        ".default",
                        [
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_TEMPLATE_PATH . "/include/footer/copyright.php"
                        ],
                        false
                    );?>
                </div>
                <div class="col-md-3">
                    <div class="social_list">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            ".default",
                            [
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_TEMPLATE_PATH . "/include/footer/socials.php"
                            ],
                            false
                        );?>
                    </div>
                </div>
                <?$APPLICATION->IncludeComponent(
                    "kDevelop:catalog.store.detail",
                    "footer",
                    Array(
                        "STORE" => STORE_ID,
                        "MAP_TYPE" => "0",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600",
                        "CACHE_NOTES" => "",
                        "SET_TITLE" => "N"
                    )
                );?>
            </div>
        </div>
        <div class="footer-part">
            <div class="container">
                <?if (DEVICE_TYPE != "MOBILE") {
                    //Список складов
                    $APPLICATION->IncludeComponent(
                        "kDevelop:catalog.store.list",
                        "footer",
                        [
                            "PHONE" => "Y",
                            "EMAIL" => "Y",
                            "SCHEDULE" => "Y",
                            "PATH_TO_ELEMENT" => "store/#store_id#",
                            "MAP_TYPE" => "0",
                            "SET_TITLE" => "N",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "36000000",
                            "SORT_BY" => "SORT",
                            "SORT_ORDER" => "ASC"
                        ]
                    );
                    //end
                }?>
                <div class="footer-part-item">
                    <div class="footer-desc">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            ".default",
                            [
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_TEMPLATE_PATH . "/include/footer/info.php"
                            ],
                            false
                        );?>
                    </div>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        ".default",
                        [
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_TEMPLATE_PATH . "/include/footer/developers.php"
                        ],
                        false
                    );?>
                </div>
            </div>
        </div>
    </footer>
    <?
    //Всплывающее меню - "Каталог продукции"
    if (DEVICE_TYPE != "MOBILE") {
        $tmp = "popup";
        $arImageSize = ["WIDTH" => "", "HEIGHT" => ""];
    } else {
        $tmp = "popup-mobile";
        $arImageSize = ["WIDTH" => "", "HEIGHT" => ""];
    }
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section.list",
        $tmp,
        [
            "VIEW_MODE" => "TEXT",
            "SHOW_PARENT_NAME" => "Y",
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => IBLOCK_CATALOG_CATALOG,
            "SECTION_ID" => "",
            "SECTION_CODE" => "",
            "SECTION_URL" => "",
            "COUNT_ELEMENTS" => "Y",
            "TOP_DEPTH" => "2",
            "SECTION_FIELDS" => "",
            "SECTION_USER_FIELDS" => "",
            "ADD_SECTIONS_CHAIN" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_NOTES" => "",
            "CACHE_GROUPS" => "Y",
            "IMAGE_SIZE" => $arImageSize
        ]
    );
    //end

    if (DEVICE_TYPE != "DESKTOP") {
        //всплывающее основное меню для планшетов и моб усройств
        $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "top-popup",
            [
                "ROOT_MENU_TYPE" => "top",
                "MAX_LEVEL" => "1",
                "CHILD_MENU_TYPE" => "top",
                "USE_EXT" => "Y",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "Y",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MENU_CACHE_GET_VARS" => ""
            ]
        );
        //end

        //поиск
        $APPLICATION->IncludeComponent(
            "kDevelop:blank",
            "search-popup",
            []
        );
        //end
    }

    //регистрация/авторизация
    if (!$USER->IsAuthorized()) {
        $APPLICATION->IncludeComponent(
            "kDevelop:blank",
            "auth-reg",
            []
        );
    }
    //end
    ?>
</body>
</html>