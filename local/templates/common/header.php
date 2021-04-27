<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @global CMain $APPLICATION */
/** @global CMain $USER */

CJSCore::Init(array('ajax'));

$rsAsset = \Bitrix\Main\Page\Asset::getInstance();
$strCurPage = $APPLICATION->GetCurPage(false);
$isMainPage = $strCurPage == SITE_DIR;
$isCatalogInner = CSite::InDir("/product-category") && $strCurPage != "/product-category/";

//css
$rsAsset->addCss(SITE_TEMPLATE_PATH.'/fonts/circle/index.css');
$rsAsset->addCss(SITE_TEMPLATE_PATH.'/css/icons.min.css');
$rsAsset->addCss(SITE_TEMPLATE_PATH.'/css/main.min.css');
$rsAsset->addCss(SITE_TEMPLATE_PATH.'/css/jquery.fancybox.min.css');
if (DEVICE_TYPE == "MOBILE") {
    $rsAsset->addCss(SITE_TEMPLATE_PATH.'/css/mobile.min.css');
} else {
    $rsAsset->addCss(SITE_TEMPLATE_PATH.'/css/desktop.min.css');
    $rsAsset->addCss(SITE_TEMPLATE_PATH.'/css/tablet.min.css');
}
//end

//js
//$rsAsset->addString('<!--[if lt IE 9]><script data-skip-moving="true" src="./js/html5shiv.min.js"></script><![endif]-->');
//$rsAsset->addString('<!--[if lt IE 9]><script data-skip-moving="true" src="./js/css3-mediaqueries.js"></script><![endif]-->');
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/jquery1.12.4.js');
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/jquery-ui.1.12.1.js');
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/slick.js');
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/modules/slider/script.js');
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/modules/animate-input/script.js');
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/modules/tabs/script.js');
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/functions.js');
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/scripts.min.js');
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/ajax.js');
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/jquery.fancybox.min.js');
//end
?>

<!DOCTYPE html>
<html>
<head>
    <title><?$APPLICATION->ShowTitle()?></title>

    <?if (DEVICE_TYPE == "MOBILE") :?>
        <meta name="viewport" content="width=375, user-scalable=0">
    <?elseif (DEVICE_TYPE == "TABLET") :?>
        <meta name="viewport" content="width=768, user-scalable=0">
    <?else:?>
        <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0, user-scalable=0">
    <?endif?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">

    <!--Favicon-->
    <link rel="shortcut icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/favicon-32x32.png">
    <link rel="apple-touch-icon" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/favicon-180x180.png">
    <link rel="apple-touch-icon" sizes="192x192" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="270x270" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/favicon-270x270.png">
    <!--link rel="manifest" href="<?=SITE_TEMPLATE_PATH?>/manifest.json"-->
    <meta name="msapplication-TileColor" content="#fff">
    <meta name="msapplication-TileImage" content="<?=SITE_TEMPLATE_PATH?>/images/favicons/favicon-180x180.png"/>
    <meta name="theme-color" content="#fff">
    <!--end-->

    <?#css impuls-it
    $APPLICATION->SetAdditionalCSS("/local/templates/common/css/edit.css");?>

    <script type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?162",t.onload=function(){VK.Retargeting.Init("VK-RTRG-409642-hoIrs"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-409642-hoIrs" style="position:fixed; left:-999px;" alt=""/></noscript>

    <?/** --- OpenGraph + Schema.org --- **/
    $APPLICATION->ShowViewContent('MetaTag');?>

    <?$APPLICATION->ShowHead();?>
</head>
<body>
    <!-- Rating@Mail.ru counter -->
    <script type="text/javascript">
        var _tmr = window._tmr || (window._tmr = []);
        _tmr.push({id: "3061325", type: "pageView", start: (new Date()).getTime()});
        (function (d, w, id) {
            if (d.getElementById(id)) return;
            var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
            ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
            var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
            if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
        })(document, window, "topmailru-code");
    </script>
    <noscript><div><img src="//top-fwz1.mail.ru/counter?id=3061325;js=na" style="border:0;position:absolute;left:-9999px;" alt=""/></div></noscript>
    <!-- end -->

    <!-- ya counter -->
    <script type="text/javascript">
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(12030070, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true,
            ecommerce:"dataLayer"
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/12030070" style="position:absolute; left:-9999px;" alt=""/></div></noscript>
    <!-- end -->

    <?if ($USER->IsAdmin()) :?>
        <div id="panel"><?$APPLICATION->ShowPanel();?></div>
    <?endif?>
    <header class="header">
        <div class="header-part">
            <div class="container">
                <?if (DEVICE_TYPE == "DESKTOP") :?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "top",
                        Array(
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
                        )
                    );?>
                    <div>
                        <?$APPLICATION->IncludeComponent(
                            "kDevelop:catalog.store.detail",
                            "email",
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
                <?else:?>
                    <div class="header-menu">
                        <a href="#" flex-align="center" class="js-toggle" data-target="#header-menu-popup" data-class="active">
                            <div class="burger menu"><hr><hr><hr></div>
                            <?if (DEVICE_TYPE != "MOBILE") :?>
                                <span>Меню</span>
                            <?endif?>
                        </a>
                    </div>
                <?endif?>
                <?
                //Список складов
                $APPLICATION->IncludeComponent(
                    "kDevelop:catalog.store.list",
                    "header",
                    Array(
                        "PHONE" => "Y",
                        "EMAIL" => "Y",
                        "SCHEDULE" => "Y",
                        "PATH_TO_ELEMENT" => "store/#store_id#",
                        "MAP_TYPE" => "0",
                        "SET_TITLE" => "N",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000",
                        "CUR_ID" => STORE_ID,
                        "SORT_BY" => "SORT",
                        "SORT_ORDER" => "ASC"
                    )
                );
                //end
                ?>
                <div class="header-contacts">
                    <?$APPLICATION->IncludeComponent(
                        "kDevelop:catalog.store.detail",
                        "header",
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
        </div>
        <div class="header-part js-fixed">
            <div class="container">
                <?if ($isMainPage) :?>
                    <div class="header-logo header-col col-lg-4 col-md-9 col-xs-3">
                        <img src="<?=SITE_TEMPLATE_PATH?>/images/logo.svg">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            ".default",
                            [
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_TEMPLATE_PATH . "/include/header/logo.php"
                            ],
                            false
                        );?>
                    </div>
                <?else:?>
                    <a href="<?=SITE_DIR?>" class="header-logo header-col col-lg-5 col-md-9 col-xs-3">
                        <img src="<?=SITE_TEMPLATE_PATH?>/images/logo.svg">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            ".default",
                            [
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_TEMPLATE_PATH . "/include/header/logo.php"
                            ],
                            false
                        );?>
                    </a>
                <?endif?>
                <?if (DEVICE_TYPE == "DESKTOP") :?>
                    <a href="#" flex-align="center" class="header-col col-lg-3 js-toggle" data-target=".js-catalog-menu" data-class="active">
                        <div class="burger"><hr><hr><hr></div>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            ".default",
                            [
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_TEMPLATE_PATH . "/include/header/catalog-title.php"
                            ],
                            false
                        );?>
                    </a>
                <?else:?>
                    <div class="header-col col-md-4 col-xs-6">
                        <a href="#" data-popup-open=".js-catalog-menu" flex-align="center">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                ".default",
                                [
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_TEMPLATE_PATH . "/include/header/catalog-title.php"
                                ],
                                false
                            );?>
                        </a>
                    </div>
                <?endif?>
                <div class="header-col header-search col-lg-5 col-md-2 col-xs-3">
                    <?if (DEVICE_TYPE == "DESKTOP") :?>
                        <form method="get" action="/search/" class="header-search-form">
                            <input class="header-search-input" type="text" placeholder="Поиск продукции" name="q">
                            <button type="submit">
                                <i class="icon search"></i>
                            </button>
                        </form>
                    <?else:?>
                        <a href="/search/">
                            <i class="icon search"></i>
                        </a>
                    <?endif?>
                </div>
                <?if (!CSite::InDir("/cart") && !CSite::InDir("/checkout")) :?>
                    <div class="header-col col-lg-1 col-md-2 col-xs-3">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:sale.basket.basket.line",
                            "",
                            Array(
                                "HIDE_ON_BASKET_PAGES" => "Y",
                                "PATH_TO_BASKET" => SITE_DIR."cart/",
                                "PATH_TO_ORDER" => SITE_DIR."checkout/",
                                "PATH_TO_PERSONAL" => SITE_DIR."personal/",
                                "PATH_TO_PROFILE" => SITE_DIR."personal/",
                                "PATH_TO_REGISTER" => SITE_DIR."login/",
                                "POSITION_FIXED" => "Y",
                                "POSITION_HORIZONTAL" => "right",
                                "POSITION_VERTICAL" => "top",
                                "SHOW_AUTHOR" => "N",
                                "SHOW_DELAY" => "N",
                                "SHOW_EMPTY_VALUES" => "N",
                                "SHOW_IMAGE" => "N",
                                "SHOW_NOTAVAIL" => "N",
                                "SHOW_NUM_PRODUCTS" => "Y",
                                "SHOW_PERSONAL_LINK" => "N",
                                "SHOW_PRICE" => "N",
                                "SHOW_PRODUCTS" => "Y",
                                "SHOW_SUMMARY" => "N",
                                "SHOW_TOTAL_PRICE" => "N"
                            )
                        );?>
                    </div>
                <?endif?>
                <div class="header-col col-lg-1 col-md-2 col-xs-3">
                    <a href="/favorite/">
                        <i id="favorite-wrapper" class="icon favorite">
                            <?if (\kDevelop\Ajax\Favorite::getCount() > 0) :?>
                                <span class="icon-inner"><?=\kDevelop\Ajax\Favorite::getCount()?></span>
                            <?endif?>
                        </i>
                    </a>
                </div>
                <div class="header-col col-lg-4 col-md-2 col-xs-3">
                    <?if (!$USER->IsAuthorized()) :?>
                        <a href="#" data-popup-open="#reg-auth">
                            <i class="icon personal"></i>
                            <?if (DEVICE_TYPE == "DESKTOP") :?>
                                <span>Вход | Регистрация</span>
                            <?endif?>
                        </a>
                    <?else:?>
                        <a href="/personal/">
                            <i class="icon personal"></i>
                            <?if (DEVICE_TYPE == "DESKTOP") :?>
                                <span>Личный кабинет</span>
                            <?endif?>
                        </a>
                    <?endif?>
                </div>
                <div class="header-col col-lg-3 col-md-2 col-xs-3">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        ".default",
                        [
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_TEMPLATE_PATH . "/include/header/download-catalog.php"
                        ],
                        false
                    );?>


                </div>

<div class="header-col col-lg-3 col-md-2 hidden-xs">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        ".default",
                        [
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_TEMPLATE_PATH . "/inc/header/sale.php"
                        ],
                        false
                    );?>


                </div>
            </div>
            <?
            //Всплывающее меню - "Каталог продукции"
            if (DEVICE_TYPE == "DESKTOP") :?>
                <div class="popup_liener header_catalog-popup col-lg-24 js-catalog-menu">
                    <div class="popup_content full_popup js-popup_content">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:catalog.section.list",
                            "popup",
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
                                "IMAGE_SIZE" => ["WIDTH" => 62, "HEIGHT" => 45]
                            ]
                        );?>
                    </div>
                </div>
                <?
                //end
            endif?>
        </div>
    </header>
    <?if (!$isMainPage) :?>
        <section
                class="<?$APPLICATION->ShowProperty("header_section-class")?>"
                style="<?$APPLICATION->ShowProperty("header_section-style")?>"
        >
            <div class="container">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:breadcrumb",
                    "",
                    [
                        "START_FROM" => "0",
                        "PATH" => "",
                        "SITE_ID" => SITE_ID
                    ]
                );?>
                <div id="<?$APPLICATION->ShowProperty("content-id")?>">
                    <?if (HIDE_H1 != "Y") :?>
                        <h1 class="title-1"><?$APPLICATION->ShowTitle(false)?></h1>
                    <?endif?>
    <?endif?>
