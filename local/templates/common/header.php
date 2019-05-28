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
$rsAsset->addCss(SITE_TEMPLATE_PATH.'/css/icons.css');
$rsAsset->addCss(SITE_TEMPLATE_PATH.'/css/main.css');
if (DEVICE_TYPE == "MOBILE") {
    $rsAsset->addCss(SITE_TEMPLATE_PATH.'/css/mobile.css');
} elseif (DEVICE_TYPE == "TABLET") {
    $rsAsset->addCss(SITE_TEMPLATE_PATH.'/css/tablet.css');
} else {
    $rsAsset->addCss(SITE_TEMPLATE_PATH.'/css/desktop.css');
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
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/scripts.js');
$rsAsset->addJs(SITE_TEMPLATE_PATH.'/js/ajax.js');
//end
?>

<!DOCTYPE html>
<html>
<head>
    <title><?$APPLICATION->ShowTitle()?></title>

    <?if (DEVICE_TYPE == "MOBILE") :?>
        <meta name="viewport" content="width=375, user-scalable=no">
    <?else:?>
        <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1.0">
    <?endif?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">

    <!--Favicon-->
    <link rel="shortcut icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/favicon.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/favicon-96x96.png">
    <link rel="apple-touch-icon" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/favicon.ico">
    <link rel="apple-touch-icon" sizes="57x57" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=SITE_TEMPLATE_PATH?>/images/favicons/apple-touch-icon-180x180.png">
    <link rel="manifest" href="<?=SITE_TEMPLATE_PATH?>/manifest.json">
    <link name="msapplication-TileColor" content="#fff">
    <link name="theme-color" content="#fff">
    <!--end-->

    <?$APPLICATION->ShowHead();?>
</head>
<body>
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
                        "CUR_ID" => STORE_ID
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
                    <div class="header-logo header-col col-lg-6 col-md-12 col-xs-4">
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
                    <a href="<?=SITE_DIR?>" class="header-logo header-col col-lg-6 col-md-12 col-xs-4">
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
                <div class="header-col col-lg-3 col-md-4 col-xs-6">
                    <a href="#" data-popup-open=".js-catalog-menu" flex-align="center">
                        <?if (DEVICE_TYPE == "DESKTOP") :?>
                            <div class="burger"><hr><hr><hr></div>
                        <?endif?>
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
                <div class="header-col header-search col-lg-6 col-md-2 col-xs-3">
                    <?if (DEVICE_TYPE == "DESKTOP") :?>
                        <form method="get" action="/search/" class="header-search-form">
                            <input class="header-search-input" type="text" placeholder="Поиск продукции" name="q">
                            <button type="submit">
                                <i class="icon search"></i>
                            </button>
                        </form>
                    <?else:?>
                        <div class="header-search-wrap">
                            <a href="#" data-popup-open="#header-search-popup">
                                <i class="icon search"></i>
                            </a>
                        </div>
                    <?endif?>
                </div>
                <?if (!CSite::InDir("/cart") && !CSite::InDir("/checkout")) :?>
                    <div class="header-col col-lg-2 col-md-2 col-xs-3">
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
                <div class="header-col col-lg-2 col-md-2 col-xs-3">
                    <a href="/favorite/">
                        <i class="icon favorite"></i>
                    </a>
                </div>
                <div class="header-col col-lg-5 col-md-2 col-xs-3">
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
            </div>
        </div>
    </header>
    <?if (!$isMainPage) :?>
        <section class="<?$APPLICATION->ShowProperty("header_section-class")?>" style="<?$APPLICATION->ShowProperty("header_section-style")?>">
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
                <?if (HIDE_H1 != "Y") :?>
                    <div class="title-1"><?$APPLICATION->ShowTitle(false)?></div>
                <?endif?>
    <?endif?>