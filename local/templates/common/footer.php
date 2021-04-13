<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

    <?if (!$isMainPage || !defined("NOT_CLOSE_SECTION_IN_FOOTER")) :?>
        </div></div></section>
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
                        "bitrix:menu",
                        "links",
                        Array(
                            "ROOT_MENU_TYPE" => "bottom",
                            "MAX_LEVEL" => "1",
                            "CHILD_MENU_TYPE" => "bottom",
                            "USE_EXT" => "Y",
                            "DELAY" => "N",
                            "ALLOW_MULTI_SELECT" => "Y",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "MENU_CACHE_GET_VARS" => ""
                        )
                    );?>
                    <br><br>
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

        <div class="footer__menu-list">
            <?#Меню 1
            $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "footer1",
                Array(
                    "ALLOW_MULTI_SELECT" => "N",
                    "CHILD_MENU_TYPE" => "",
                    "DELAY" => "N",
                    "MAX_LEVEL" => "1",
                    "MENU_CACHE_GET_VARS" => array(),
                    "MENU_CACHE_TIME" => "360000",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_USE_GROUPS" => "N",
                    "MENU_CACHE_USE_USERS" => "N",
                    "CACHE_SELECTED_ITEMS" => "N",
                    "ROOT_MENU_TYPE" => "footer1",
                    "USE_EXT" => "Y"
                )
            );

            #Меню 2
            $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "footer2",
                Array(
                    "ALLOW_MULTI_SELECT" => "N",
                    "CHILD_MENU_TYPE" => "",
                    "DELAY" => "N",
                    "MAX_LEVEL" => "1",
                    "MENU_CACHE_GET_VARS" => array(),
                    "MENU_CACHE_TIME" => "360000",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_USE_GROUPS" => "N",
                    "MENU_CACHE_USE_USERS" => "N",
                    "CACHE_SELECTED_ITEMS" => "N",
                    "ROOT_MENU_TYPE" => "footer2",
                    "USE_EXT" => "Y"
                )
            );?>

            <?/*#Меню каталога
            $APPLICATION->IncludeComponent(
                "bitrix:catalog.section.list",
                "footer_menu",
                Array(
                    "ADD_SECTIONS_CHAIN" => "N",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "N",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "A",
                    "COUNT_ELEMENTS" => "N",
                    "COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
                    "FILTER_NAME" => "",
                    "IBLOCK_ID" => "40",
                    "IBLOCK_TYPE" => "catalog",
                    "SECTION_CODE" => "",
                    "SECTION_FIELDS" => array(),
                    "SECTION_ID" => "",
                    "SECTION_URL" => "",
                    "SECTION_USER_FIELDS" => array(),
                    "SHOW_PARENT_NAME" => "Y",
                    "TOP_DEPTH" => "1",
                    "VIEW_MODE" => "LINE"
                )
            );*/?>

            <div class="footer__menu-item">
                <div class="footer__pay">
                    <?#Оплата
                    $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => "/local/templates/common/inc/footer__pay.php"), false);?>
                </div>
            </div>
            <div class="footer__menu-item">
                <?#Оплата
                $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => "/local/templates/common/inc/footer__company-info.php"), false);?>
            </div>    
        </div>
    </footer>
    <?
    //Всплывающее меню - "Каталог продукции"
    if (DEVICE_TYPE == "MOBILE") :?>
        <?$APPLICATION->IncludeComponent(
            "bitrix:catalog.section.list",
            "popup-mobile",
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
                "IMAGE_SIZE" => ["WIDTH" => "", "HEIGHT" => ""]
            ]
        );?>
    <?elseif (DEVICE_TYPE == "TABLET") :?>
        <div class="popup full_popup js-catalog-menu" data-animate>
            <div class="popup_wrapper">
                <div class="popup_content full_popup animate-start js-popup_content">
                    <a href="#" class="popup_content-close" data-popup-close><i class="icon close"></i></a>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:catalog.section.list",
                        "popup-tablet",
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
                            "IMAGE_SIZE" => ["WIDTH" => "", "HEIGHT" => ""]
                        ]
                    );?>
                </div>
            </div>
        </div>
    <?
    //end
    endif?>

    <?if (DEVICE_TYPE != "DESKTOP") {
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

    //store road map
    $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        ".default",
        [
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_TEMPLATE_PATH . "/include/footer/store-road-map.php"
        ],
        false
    );
    //end
    ?>

    <?if (!isset($_COOKIE['cookie_policy']) || $_COOKIE['cookie_policy'] != 'true') :?>
        <div id="popup-cookie" class="popup_content popup_cookie">
            <a href="#" class="popup_content-close js-toggle-class" data-target="#popup-cookie" data-class_delete="active">
                <i class="icon close"></i>
            </a>
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                ".default",
                [
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => SITE_TEMPLATE_PATH . "/include/footer/cookie-policy.php"
                ],
                false
            );?>
        </div>
    <?endif?>
    <script type='text/javascript'>
        (function(){ var widget_id = '107881';
            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();
    </script>

    <!— Facebook Pixel Code —>
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '541634183193466');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=541634183193466&ev=PageVie.."/></noscript>
    <!— End Facebook Pixel Code —>

</body>
</html>
