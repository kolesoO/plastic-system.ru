<?php
namespace Impulsit\ExpansionSite;

use \Bitrix\Main;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader;
use \Impulsit\ExpansionSite\Handler;

class MetaTag
{
    /**
     * @brief Инициализация MetaTag
     * @param Type = Article - Статьи детальная
     * @param Type = ListItem - Статьи список
     * @param Type = ListItemLight - Статьи список упрощённый
     * @param Type = Product - Товар детальная
     * @param Type = ProductItem - Товар список
     * @param Type = Organization - Организация
     * @param $arResult = массив данных
     * @param $arParams = массив данных
     * @return Буферизированный контент
     **/
	public function init($type = "", $arResult = array(), $arParams = array())
	{
        if(!$type || !$arResult)
            return false;

        global $APPLICATION;

        if($type == "Article")
            $resMetaTag = self::MetaTagArticle($arResult,$arParams);
        else if($type == "ListItem")
            $resMetaTag = self::MetaTagListItem($arResult,$arParams);
        else if($type == "ListItemLight")
            $resMetaTag = self::MetaTagListItemLight($arResult,$arParams);
        else if($type == "Product")
            $resMetaTag = self::MetaTagProduct($arResult,$arParams);
        else if($type == "ProductItem")
            $resMetaTag = self::MetaTagProductItem($arResult,$arParams);
        else if($type == "ProductOffer")
            $resMetaTag = self::MetaTagProductOffer($arResult,$arParams);
        else if($type == "Organization")
            $resMetaTag = self::MetaTagOrganization($arResult);
        else if($type == "Pagenavigation")
            $resMetaTag = self::MetaPagenavigation($arResult);

        if($resMetaTag)
          $APPLICATION->AddViewContent('MetaTag',$resMetaTag);
	}

    /**
     * @brief Для детальной страницы 
     * @param $arResult = массив данных
     * @return Разметка
     **/
	public function MetaTagArticle($arResult = array(),$arParams = array())
	{
        if(!$arResult)
            return false;

        #Если данные о элементах отсутствуют
        if(
            !$arResult["DETAIL_TEXT"] && 
            !$arResult["PREVIEW_TEXT"] && 
            !$arResult["PROPERTIES"] && 
            intval($arResult["ID"]) > 0
        )
        {
            #Получить список элементов
            $arFilter = array(
                "ACTIVE" => "Y",
                "ID" => (int)$arResult["ID"]
            );
            if($arResult["IBLOCK_ID"])
                $arFilter["IBLOCK_ID"] = (int)$arResult["IBLOCK_ID"];
            else if($arParams["IBLOCK_ID"])
                $arFilter["IBLOCK_ID"] = (int)$arParams["IBLOCK_ID"];

            $arElementData = self::getElementData($arFilter);
            $arElementData = current($arElementData);

            $arResult = array_merge((array)$arResult, (array)$arElementData);
        }

        #Если это компонент bitrix:news.detail
        if(intval($arParams["ELEMENT_ID"]) > 0)
        {
            if (strpos($arResult["DETAIL_PAGE_URL"], '%'))
            {
                $arResult["DETAIL_PAGE_URL"] = str_replace("%2F","/",$arResult["DETAIL_PAGE_URL"] );
            }
            else
            {
                global $APPLICATION;

                $arResult["DETAIL_PAGE_URL"] = $APPLICATION->GetCurDir();
            }
        }

        #Получить все настройки модуля
        $modParams = Handler::getOptions();

        $serverName = SITE_SERVER_NAME;
        $protocol = (\CMain::IsHTTPS()) ? "https://" : "http://"; 
        $seoDetailPage = $protocol.$serverName.$arResult["DETAIL_PAGE_URL"];
        $seoTitle = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_META_TITLE']) ? $arResult['IPROPERTY_VALUES']['ELEMENT_META_TITLE'] : $arResult['NAME'];
        $datePublished = ( ($arResult["DATE_CREATE"]) ? $arResult["DATE_CREATE"] : $arResult["TIMESTAMP_X"] );
        $datePublished = \FormatDate("Y-m-d", strtotime($datePublished) );
        $dateModified = $arResult["TIMESTAMP_X"];
        $dateModified = \FormatDate("Y-m-d", strtotime($dateModified) );
        $siteLogo = $protocol.$serverName.$modParams["META_TAG"]["LOGO"];

        if(mb_strlen($arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"]) > 0)
            $seoDescription = $arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"];
        else if(mb_strlen($arResult["DETAIL_TEXT"]) > 0)
            $seoDescription = \TruncateText(strip_tags($arResult["~DETAIL_TEXT"]), 150);
        else if(mb_strlen($arResult["PREVIEW_TEXT"]) > 0)
            $seoDescription = \TruncateText(strip_tags($arResult["~PREVIEW_TEXT"]), 150);
        else if(mb_strlen($arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"]) > 0)
            $seoDescription = $arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"];
        else
            $seoDescription = $arResult["NAME"];

        $seoDescription = preg_replace("/\s{2,}/"," ",$seoDescription);

        if( $arResult["PREVIEW_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["PREVIEW_PICTURE"]["SRC"];
        }
        else if( $arResult["DETAIL_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["DETAIL_PICTURE"]["SRC"];
        }
        else if( $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0] )
        {
            $arMorePhoto = \CFile::GetFileArray(   $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0]  ); 
            $seoImage = $protocol.$serverName.$arMorePhoto["SRC"];
        }
        else
        {
            $seoImage = $siteLogo;
        }

        $jsMetaTag = array(
            "@context" => "https://schema.org",
            "@type" => "Article",
            "mainEntityOfPage" => array(
                "@type" => "WebPage",
                "@id" => $seoDetailPage
            ),
            "author" => $serverName,
            "url" => $seoDetailPage,
            "datePublished" => $datePublished,
            "dateModified" => $dateModified,
            "headline" => $seoTitle,
            "image" => array(
                "@type" => "ImageObject",
                "url" => $seoImage,
                "height" => "200",
                "width" => "200"
            ),
            "articleBody" => $seoDescription,
            "publisher" => array(
                "@type" => "Organization",
                "name" => $serverName,
                "logo" => array(
                    "@type" => "ImageObject",
                    "url" => $siteLogo,
                    "height" => "100",
                    "width" => "200"
                )
            )
        );

        $jsMetaTag = json_encode($jsMetaTag,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
 
        $html = '';
        $html .= '<meta property="og:title" content="'.$seoTitle.'" />'."\r\n";
        $html .= '<meta property="og:description" content="'.$seoDescription.'" />'."\r\n";
        $html .= '<meta property="og:type" content="article" />'."\r\n";
        $html .= '<meta property="og:url" content="'.$seoDetailPage.'" />'."\r\n";
        $html .= '<meta property="og:image" content="'.$seoImage.'" />'."\r\n";
        $html .= '<meta property="og:image:type" content="image/jpeg" />';
        $html .= '<script type="application/ld+json">'.$jsMetaTag.'</script>';

        return $html;
    }

    /**
     * @brief Для страницы со списком статей
     * @param $arResult = массив данных
     * @return Разметка
     **/
	public function MetaTagListItem($arResult = array(),$arParams = array())
	{
        if(!$arResult)
            return false;

        global $APPLICATION;

        #Если данные о разделе отсутствуют
        if(!$arResult["SECTION"] && $arParams["PARENT_SECTION"] && $arParams["IBLOCK_ID"])
            $arResult["SECTION"]["PATH"][0] = self::getSectionData(array("IBLOCK_ID"=>(int)$arParams["IBLOCK_ID"],"ID"=>(int)$arParams["PARENT_SECTION"]));

        #Если данные о элементах отсутствуют
        if(!$arResult["ITEMS"] && $arResult["ID"])
        {
            #Получить список элементов
            $arFilter = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => (int)$arResult["ID"]
            );

            if($arParams["PARENT_SECTION"])
            {
                $arFilter["INCLUDE_SUBSECTIONS"] = "Y";
                $arFilter["SECTION_ID"] = $arParams["PARENT_SECTION"];
            }

            if($arResult["ELEMENTS"])
                $arFilter["ID"] = $arResult["ELEMENTS"];

            $arResult["ITEMS"] = self::getElementData($arFilter);
        }

        #Получить все настройки модуля
        $modParams = Handler::getOptions();

        $serverName = SITE_SERVER_NAME;
        $protocol = (\CMain::IsHTTPS()) ? "https://" : "http://"; 
        $currentPage = $APPLICATION->GetCurPage();
        $seoDetailPage = $protocol.$serverName.$currentPage;
        $seoTitle = !empty($arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']) ? $arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] : $arResult['NAME'];
        $siteLogo = $protocol.$serverName.$modParams["META_TAG"]["LOGO"];

        if(mb_strlen($arResult["SECTION"]["PATH"][0]["DESCRIPTION"]) > 0)
            $seoDescription = \TruncateText(strip_tags($arResult["SECTION"]["PATH"][0]["DESCRIPTION"]), 150);
        else if(mb_strlen($arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]) > 0)
            $seoDescription = \TruncateText(strip_tags($arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]), 150);
        else
            $seoDescription = $arResult["NAME"];

        $seoDescription = preg_replace("/\s{2,}/"," ",$seoDescription);

        if( $arResult["PREVIEW_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["PREVIEW_PICTURE"]["SRC"];
        }
        else if( $arResult["DETAIL_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["DETAIL_PICTURE"]["SRC"];
        }
        else if( $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0] )
        {
            $arMorePhoto = \CFile::GetFileArray(   $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0]  ); 
            $seoImage = $protocol.$serverName.$arMorePhoto["SRC"];
        }
        else
        {
            $seoImage = $siteLogo;
        }

        if($arResult["ITEMS"])
        {
            $jsMetaTag = array(
                "@context" => "https://schema.org",
                "@type" => "ItemList",
                "url" => $seoDetailPage,
                "name" => $seoTitle,
                "numberOfItems" => $arResult["NAV_RESULT"]->NavRecordCount,
                "itemListElement" => array()
            );

            $shemaOrgCount = 1;
            if($arResult["NAV_RESULT"])
            {
                $shemaOrgCount = $arResult["NAV_RESULT"]->NavPageSize * $arResult["NAV_RESULT"]->NavPageNomer - 1;
            }
            else
            {
                global $APPLICATION;
                $pageParam = $APPLICATION->GetCurPageParam();

                $parts = parse_url($pageParam);
                parse_str($parts['query'], $query);
                foreach( $query as $key => $arItem )
                {
                    if(strpos($key,"PAGEN_") !== false) 
                    {
                        $pageKey = $key;
                        break;
                    }
                }

                if($query[$pageKey] > 1)
                {
                    $pageCount = ( ($arParams["NEWS_COUNT"]) ? $arParams["NEWS_COUNT"] : 5 );
                    $shemaOrgCount = $pageCount * $query[$pageKey] - ($pageCount - 1);
                }
            }

            foreach($arResult["ITEMS"] as $arItem)
            {
                $seoItenPage = $protocol.$serverName.$arItem["DETAIL_PAGE_URL"];
                $seoItemTitle = !empty($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME'];
                $dateItemPublished = ( ($arItem["DATE_CREATE"]) ? $arItem["DATE_CREATE"] : $arItem["TIMESTAMP_X"] );
                $dateItemPublished = \FormatDate("Y-m-d", strtotime($dateItemPublished) );
                $dateItemModified = $arItem["TIMESTAMP_X"];
                $dateItemModified = \FormatDate("Y-m-d", strtotime($dateItemModified) );
                if( $arItem["PREVIEW_PICTURE"]["SRC"] )
                {
                    $seoItemImage = $protocol.$serverName.$arItem["PREVIEW_PICTURE"]["SRC"];
                }
                else if( $arItem["DETAIL_PICTURE"]["SRC"] )
                {
                    $seoItemImage = $protocol.$serverName.$arItem["DETAIL_PICTURE"]["SRC"];
                }
                else if( $arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0] )
                {
                    $arMorePhoto = \CFile::GetFileArray($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0]); 
                    $seoItemImage = $protocol.$serverName.$arMorePhoto["SRC"];
                }
                else
                {
                    $seoItemImage = $siteLogo;
                }

                if(mb_strlen($arItem['PREVIEW_TEXT']) > 0)
                    $seoItemDescription = \TruncateText(strip_tags($arItem['~PREVIEW_TEXT']), 150);
                else if(mb_strlen($arItem['IPROPERTY_VALUES']['ELEMENT_META_DESCRIPTION']) > 0)
                    $seoItemDescription = \TruncateText(strip_tags($arItem['IPROPERTY_VALUES']['ELEMENT_META_DESCRIPTION']), 150);
                else
                    $seoItemDescription = $arItem["NAME"];

                $seoItemDescription = preg_replace("/\s{2,}/"," ",$seoItemDescription);

                $jsMetaTag["itemListElement"][] = array(
                    "@type" => "ListItem",
                    "position" => $shemaOrgCount,
                    "item" => array(
                        "@context" => "https://schema.org",
                        "@type" => "Article",
                        "mainEntityOfPage" => array(
                            "@type" => "WebPage",
                            "@id" => $seoItenPage
                        ),
                        "author" => $serverName,
                        "url" => $seoItenPage,
                        "datePublished" => $dateItemPublished,
                        "dateModified" => $dateItemModified,
                        "headline" => $seoItemTitle,
                        "image" => array(
                            "@type" => "ImageObject" ,
                            "url" => $seoItemImage,
                            "height" => "200",
                            "width" => "200"
                        ),
                        "articleBody" => $seoItemDescription,
                        "publisher" => array(
                            "@type" => "Organization ",
                            "name" => $serverName,
                            "logo" => array(
                                "@type" => "ImageObject",
                                "url" => $siteLogo,
                                "height" => "100",
                                "width" => "200"
                            )
                        )
                    )
                );

                $shemaOrgCount++;
            }

            $jsMetaTag = json_encode($jsMetaTag,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $html = '';
        $html .= '<meta property="og:title" content="'.$seoTitle.'" />'."\r\n";
        $html .= '<meta property="og:description" content="'.$seoDescription.'" />'."\r\n";
        $html .= '<meta property="og:type" content="article" />'."\r\n";
        $html .= '<meta property="og:url" content="'.$seoDetailPage.'" />'."\r\n";
        $html .= '<meta property="og:image" content="'.$seoImage.'" />'."\r\n";
        $html .= '<meta property="og:image:type" content="image/jpeg" />';

        if($arResult["ITEMS"])
            $html .= '<script type="application/ld+json">'.$jsMetaTag.'</script>';

        return $html;
    }

    /**
     * @brief Для страницы со списком статей на минималках
     * @param $arResult = массив данных
     * @return Разметка
     **/
	public function MetaTagListItemLight($arResult = array(),$arParams = array())
	{
        if(!$arResult)
            return false;

        global $APPLICATION;

        #Если данные о разделе отсутствуют
        if(!$arResult["SECTION"] && $arParams["PARENT_SECTION"] && $arParams["IBLOCK_ID"])
            $arResult["SECTION"]["PATH"][0] = self::getSectionData(array("IBLOCK_ID"=>(int)$arParams["IBLOCK_ID"],"ID"=>(int)$arParams["PARENT_SECTION"]));

        #Если данные о элементах отсутствуют
        if(!$arResult["ITEMS"] && $arResult["ID"])
        {
            #Получить список элементов
            $arFilter = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => (int)$arResult["ID"]
            );

            if($arParams["PARENT_SECTION"])
            {
                $arFilter["INCLUDE_SUBSECTIONS"] = "Y";
                $arFilter["SECTION_ID"] = $arParams["PARENT_SECTION"];
            }

            if($arResult["ELEMENTS"])
                $arFilter["ID"] = $arResult["ELEMENTS"];

            $arResult["ITEMS"] = self::getElementData($arFilter);
        }

        #Получить все настройки модуля
        $modParams = Handler::getOptions();

        $serverName = SITE_SERVER_NAME;
        $protocol = (\CMain::IsHTTPS()) ? "https://" : "http://"; 
        $currentPage = $APPLICATION->GetCurPage();
        $seoDetailPage = $protocol.$serverName.$currentPage;
        $seoTitle = !empty($arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']) ? $arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] : $arResult['NAME'];
        $siteLogo = $protocol.$serverName.$modParams["META_TAG"]["LOGO"];

        if(mb_strlen($arResult["SECTION"]["PATH"][0]["DESCRIPTION"]) > 0)
            $seoDescription = \TruncateText(strip_tags($arResult["SECTION"]["PATH"][0]["DESCRIPTION"]), 150);
        else if(mb_strlen($arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]) > 0)
            $seoDescription = \TruncateText(strip_tags($arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]), 150);
        else
            $seoDescription = $arResult["NAME"];

        $seoDescription = preg_replace("/\s{2,}/"," ",$seoDescription);

        if( $arResult["PREVIEW_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["PREVIEW_PICTURE"]["SRC"];
        }
        else if( $arResult["DETAIL_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["DETAIL_PICTURE"]["SRC"];
        }
        else if( $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0] )
        {
            $arMorePhoto = \CFile::GetFileArray(   $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0]  ); 
            $seoImage = $protocol.$serverName.$arMorePhoto["SRC"];
        }
        else
        {
            $seoImage = $siteLogo;
        }

        if($arResult["ITEMS"])
        {
            $jsMetaTag = array(
                "@context" => "https://schema.org",
                "@type" => "ItemList",
                "url" => $seoDetailPage,
                "name" => $seoTitle,
                "numberOfItems" => $arResult["NAV_RESULT"]->NavRecordCount,
                "itemListElement" => array()
            );

            $shemaOrgCount = 1;
            if($arResult["NAV_RESULT"])
            {
                $shemaOrgCount = $arResult["NAV_RESULT"]->NavPageSize * $arResult["NAV_RESULT"]->NavPageNomer - 1;
            }
            else
            {
                global $APPLICATION;
                $pageParam = $APPLICATION->GetCurPageParam();

                $parts = parse_url($pageParam);
                parse_str($parts['query'], $query);
                foreach( $query as $key => $arItem )
                {
                    if(strpos($key,"PAGEN_") !== false) 
                    {
                        $pageKey = $key;
                        break;
                    }
                }

                if($query[$pageKey] > 1)
                {
                    $pageCount = ( ($arParams["NEWS_COUNT"]) ? $arParams["NEWS_COUNT"] : 5 );
                    $shemaOrgCount = $pageCount * $query[$pageKey] - ($pageCount - 1);
                }
            }

            foreach($arResult["ITEMS"] as $arItem)
            {
                $seoItenPage = $protocol.$serverName.$arItem["DETAIL_PAGE_URL"];
                $seoItemTitle = !empty($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME'];
                $dateItemPublished = ( ($arItem["DATE_CREATE"]) ? $arItem["DATE_CREATE"] : $arItem["TIMESTAMP_X"] );
                $dateItemPublished = \FormatDate("Y-m-d", strtotime($dateItemPublished) );
                $dateItemModified = $arItem["TIMESTAMP_X"];
                $dateItemModified = \FormatDate("Y-m-d", strtotime($dateItemModified) );
                if( $arItem["PREVIEW_PICTURE"]["SRC"] )
                {
                    $seoItemImage = $protocol.$serverName.$arItem["PREVIEW_PICTURE"]["SRC"];
                }
                else if( $arItem["DETAIL_PICTURE"]["SRC"] )
                {
                    $seoItemImage = $protocol.$serverName.$arItem["DETAIL_PICTURE"]["SRC"];
                }
                else if( $arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0] )
                {
                    $arMorePhoto = \CFile::GetFileArray($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0]); 
                    $seoItemImage = $protocol.$serverName.$arMorePhoto["SRC"];
                }
                else
                {
                    $seoItemImage = $siteLogo;
                }

                $jsMetaTag["itemListElement"][] = array(
                  "@type" => "ListItem",
                  "image" => $seoItemImage,
                  "url" => $seoItenPage,
                  "name" => $seoItemTitle,
                  "position" => $shemaOrgCount
                );

                $shemaOrgCount++;
            }

            $jsMetaTag = json_encode($jsMetaTag,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $html = '';
        $html .= '<meta property="og:title" content="'.$seoTitle.'" />'."\r\n";
        $html .= '<meta property="og:description" content="'.$seoDescription.'" />'."\r\n";
        $html .= '<meta property="og:type" content="article" />'."\r\n";
        $html .= '<meta property="og:url" content="'.$seoDetailPage.'" />'."\r\n";
        $html .= '<meta property="og:image" content="'.$seoImage.'" />'."\r\n";
        $html .= '<meta property="og:image:type" content="image/jpeg" />';

        if($arResult["ITEMS"])
            $html .= '<script type="application/ld+json">'.$jsMetaTag.'</script>';

        return $html;
    }

    /**
     * @brief Для детальной страницы продукта
     * @param $arResult = массив данных
     * @return Разметка
     **/
	public function MetaTagProduct($arResult = array(),$arParams = array())
	{
        if(!$arResult)
            return false;

        #Если данные о элементах отсутствуют
        if(
            (!$arResult["DETAIL_TEXT"] && !$arResult["PREVIEW_TEXT"] && !$arResult["PROPERTIES"]) ||
            !$arResult['MIN_PRICE'] && intval($arResult["ID"]) > 0
        )
        {
            #Получить список элементов
            $arFilter = array(
                "ACTIVE" => "Y",
                "ID" => (int)$arResult["ID"]
            );
            if($arResult["IBLOCK_ID"])
            {
                $arFilter["IBLOCK_ID"] = (int)$arResult["IBLOCK_ID"];
            }
            else if($arParams["IBLOCK_ID"])
            {
                $arFilter["IBLOCK_ID"] = (int)$arParams["IBLOCK_ID"];
            }

            $arElementData = self::getElementData($arFilter);
            $arElementData = current($arElementData);
            $arElementData["MIN_PRICE"] = $arElementData["ITEM_PRICES"];
            $arElementData["MIN_PRICE"]["VALUE"] = $arElementData["ITEM_PRICES"]["BASE_PRICE"];
            $arElementData["MIN_PRICE"]["DISCOUNT_VALUE"] = $arElementData["ITEM_PRICES"]["BASE_PRICE"];

            $arResult = array_merge((array)$arResult, (array)$arElementData);
        }

        #Получить все настройки модуля
        $modParams = Handler::getOptions();

        $serverName = SITE_SERVER_NAME;
        $protocol = (\CMain::IsHTTPS()) ? "https://" : "http://"; 
        $seoDetailPage = $protocol.$serverName.$arResult["DETAIL_PAGE_URL"];
        $seoTitle = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_META_TITLE']) ? $arResult['IPROPERTY_VALUES']['ELEMENT_META_TITLE'] : $arResult['NAME'];
        $siteLogo = $protocol.$serverName.$modParams["META_TAG"]["LOGO"];

        if(mb_strlen($arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"]) > 0)
            $seoDescription = $arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"];
        else if(mb_strlen($arResult["DETAIL_TEXT"]) > 0)
            $seoDescription = \TruncateText(strip_tags($arResult["~DETAIL_TEXT"]), 150);
        else if(mb_strlen($arResult["PREVIEW_TEXT"]) > 0)
            $seoDescription = \TruncateText(strip_tags($arResult["~PREVIEW_TEXT"]), 150);
        else if(mb_strlen($arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"]) > 0)
            $seoDescription = $arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"];
        else
            $seoDescription = $arResult["NAME"];

        $seoDescription = preg_replace("/\s{2,}/"," ",$seoDescription);

        if( $arResult["PREVIEW_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["PREVIEW_PICTURE"]["SRC"];
        }
        else if( $arResult["DETAIL_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["DETAIL_PICTURE"]["SRC"];
        }
        else if( $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0] )
        {
            $arMorePhoto = \CFile::GetFileArray(   $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0]  ); 
            $seoImage = $protocol.$serverName.$arMorePhoto["SRC"];
        }
        else
        {
            $seoImage = $siteLogo;
        }
        
        $jsMetaTag = array();

        if($arResult['MIN_PRICE']['DISCOUNT_VALUE'])
        {
            $jsMetaTag = array(
                "@context" => "https://schema.org",
                "@type" => "Product",
                "description" => $seoDescription, 
                "name" => $seoTitle,
                "image" => array(
                    "@type" => "ImageObject",
                    "url" => $seoImage,
                    "height" => "200",
                    "width" => "200"
                ),
                "offers" => array(
                    "@type" => "Offer",
                    "availability" => "http://schema.org/InStock",
                    "price" => $arResult['MIN_PRICE']['DISCOUNT_VALUE'],
                    "priceCurrency" => "RUB"
                )
            );
        }

        #Проверка рейтинга у товара
        if( $arResult["PROPERTIES"]["vote_count"]["VALUE"] > 0 && $arResult["PROPERTIES"]["rating"]["VALUE"] > 0 )
        {
            $jsMetaTag["aggregateRating"] = array(
                "@type" => "AggregateRating",
                "ratingCount" => $arResult["PROPERTIES"]["vote_count"]["VALUE"],
                "bestRating" => "5",
                "ratingValue" => $arResult["PROPERTIES"]["rating"]["VALUE"]
            );
        }

        #Проверка рейтинга у товара
        if($arResult["PROPERTIES"]["vote_count"]["VALUE"] > 0 && $arResult["PROPERTIES"]["rating"]["VALUE"] > 0)
        {
            $jsMetaTag["aggregateRating"] = array(
                "@type" => "AggregateRating",
                "ratingCount" => $arResult["PROPERTIES"]["vote_count"]["VALUE"],
                "bestRating" => "5",
                "ratingValue" => $arResult["PROPERTIES"]["rating"]["VALUE"]
            );
        }

        $jsMetaTag = json_encode($jsMetaTag,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
 
        $html = '';
        $html .= '<meta property="og:title" content="'.$seoTitle.'" />'."\r\n";
        $html .= '<meta property="og:description" content="'.$seoDescription.'" />'."\r\n";
        $html .= '<meta property="og:type" content="product" />'."\r\n";
        $html .= '<meta property="og:url" content="'.$seoDetailPage.'" />'."\r\n";
        $html .= '<meta property="og:image" content="'.$seoImage.'" />'."\r\n";
        $html .= '<meta property="og:image:type" content="image/jpeg" />';
        $html .= '<script type="application/ld+json">'.$jsMetaTag.'</script>';

        return $html;
    }

    /**
     * @brief Для страницы список продуктов
     * @param $arResult = массив данных
     * @return Разметка
     **/
	public function MetaTagProductItem($arResult = array(),$arParams = array())
	{
        if(!$arResult)
            return false;

        global $APPLICATION;

        #Если данные о разделе отсутствуют
        if(!$arResult["SECTION"] && $arResult["ID"] && $arParams["IBLOCK_ID"])
            $arResult["SECTION"]["PATH"][0] = self::getSectionData(array("IBLOCK_ID"=>(int)$arParams["IBLOCK_ID"],"ID"=>(int)$arResult["ID"]));

        #Если данные о элементах отсутствуют
        if(!$arResult["ITEMS"] && $arResult["ID"])
        {
            #Получить список элементов
            $arFilter = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => (int)$arParams["IBLOCK_ID"]
            );
            if($arResult["ID"])
            {
                $arFilter["INCLUDE_SUBSECTIONS"] = "Y";
                $arFilter["SECTION_ID"] = $arResult["ID"];
            }
            else if($arParams["SECTION_ID"])
            {
                $arFilter["INCLUDE_SUBSECTIONS"] = "Y";
                $arFilter["SECTION_ID"] = $arParams["SECTION_ID"];
            }
            
            if($arResult["NAV_RESULT_EPILOG"]["ELEMENTS_ID"])
            {
                $arFilter["ID"] = $arResult["NAV_RESULT_EPILOG"]["ELEMENTS_ID"];
            }

            $arResult["ITEMS"] = self::getElementData($arFilter);
        }

        #Получить все настройки модуля
        $modParams = Handler::getOptions();

        $serverName = SITE_SERVER_NAME;
        $protocol = (\CMain::IsHTTPS()) ? "https://" : "http://"; 
        $currentPage = $APPLICATION->GetCurPage();
        $seoDetailPage = $protocol.$serverName.$currentPage;
        $seoTitle = !empty($arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']) ? $arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] : $arResult['NAME'];
        $siteLogo = $protocol.$serverName.$modParams["META_TAG"]["LOGO"];

        if(mb_strlen($arResult["SECTION"]["PATH"][0]["DESCRIPTION"]) > 0)
            $seoDescription = \TruncateText(strip_tags($arResult["SECTION"]["PATH"][0]["DESCRIPTION"]), 150);
        else if(mb_strlen($arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]) > 0)
            $seoDescription = \TruncateText(strip_tags($arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]), 150);
        else
            $seoDescription = $arResult["NAME"];

        $seoDescription = preg_replace("/\s{2,}/"," ",$seoDescription);

        if( $arResult["PREVIEW_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["PREVIEW_PICTURE"]["SRC"];
        }
        else if( $arResult["DETAIL_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["DETAIL_PICTURE"]["SRC"];
        }
        else if( $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0] )
        {
            $arMorePhoto = \CFile::GetFileArray($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0]); 
            $seoImage = $protocol.$serverName.$arMorePhoto["SRC"];
        }
        else
        {
            $seoImage = $siteLogo;
        }

        if($arResult["ITEMS"])
        {
            $jsMetaTag = array(
                "@context" => "https://schema.org",
                "@type" => "ListItem",
                "position" => 1,
                "item" => array()
            );

            $shemaOrgCount = $arResult["NAV_RESULT"]->NavPageSize * $arResult["NAV_RESULT"]->NavPageNomer - 1;
            foreach($arResult["ITEMS"] as $arItem)
            {
                $seoItenPage = $protocol.$serverName.$arItem["DETAIL_PAGE_URL"];
                $seoItemTitle = !empty($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME'];

                if( $arItem["PREVIEW_PICTURE"]["SRC"] )
                {
                    $seoItemImage = $protocol.$serverName.$arItem["PREVIEW_PICTURE"]["SRC"];
                }
                else if( $arItem["DETAIL_PICTURE"]["SRC"] )
                {
                    $seoItemImage = $protocol.$serverName.$arItem["DETAIL_PICTURE"]["SRC"];
                }
                else if( $arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0] )
                {
                    $arMorePhoto = \CFile::GetFileArray($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0]); 
                    $seoItemImage = $protocol.$serverName.$arMorePhoto["SRC"];
                }
                else
                {
                    $seoItemImage = $siteLogo;
                }

            	$haveOffers = !empty($arItem['OFFERS']);
            	if ($haveOffers)
            		$actualItem = isset($arItem['OFFERS'][$arItem['OFFERS_SELECTED']]) ? $arItem['OFFERS'][$arItem['OFFERS_SELECTED']] : reset($arItem['OFFERS']);
            	else
            		$actualItem = $arItem;

                $jsMetaTag["item"][] = array(
                    "@type" => "Product",
                    "url" => $seoItenPage,
                    "name" => $seoItemTitle,
                    "image" => array(
                        "@type" => "ImageObject" ,
                        "url" => $seoItemImage,
                        "height" => "200",
                        "width" => "200"
                    ),
                    "offers" => array(
                       "@type" => "Offer",
                       "price" => $actualItem['ITEM_PRICES']["BASE_PRICE"],
                       "priceCurrency" => $actualItem['ITEM_PRICES']["CURRENCY"]
                    )
                );
            }

            $jsMetaTag = json_encode($jsMetaTag,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $html = '';
        $html .= '<meta property="og:title" content="'.$seoTitle.'" />'."\r\n";
        $html .= '<meta property="og:description" content="'.$seoDescription.'" />'."\r\n";
        $html .= '<meta property="og:type" content="product" />'."\r\n";
        $html .= '<meta property="og:url" content="'.$seoDetailPage.'" />'."\r\n";
        $html .= '<meta property="og:image" content="'.$seoImage.'" />'."\r\n";
        $html .= '<meta property="og:image:type" content="image/jpeg" />';

        if($arResult["ITEMS"])
            $html .= '<script type="application/ld+json">'.$jsMetaTag.'</script>';

        return $html;
    }

    /**
     * @brief Для страницы список продуктов
     * @param $arResult = массив данных
     * @return Разметка
     **/
	public function MetaTagProductOffer($arResult = array(),$arParams = array())
	{
        if(!$arResult)
            return false;

        global $APPLICATION;

        #Если данные о разделе отсутствуют
        if(!$arResult["SECTION"] && $arResult["ID"] && $arParams["IBLOCK_ID"])
            $arResult["SECTION"]["PATH"][0] = self::getSectionData(array("IBLOCK_ID"=>(int)$arParams["IBLOCK_ID"],"ID"=>(int)$arResult["ID"]));

        #Если данные о элементах отсутствуют
        if(!$arResult["ITEMS"] && $arResult["ID"])
        {
            #Получить список элементов
            $arFilter = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => (int)$arParams["IBLOCK_ID"]
            );
            if($arResult["ID"])
            {
                $arFilter["INCLUDE_SUBSECTIONS"] = "Y";
                $arFilter["SECTION_ID"] = $arResult["ID"];
            }
            else if($arParams["SECTION_ID"])
            {
                $arFilter["INCLUDE_SUBSECTIONS"] = "Y";
                $arFilter["SECTION_ID"] = $arParams["SECTION_ID"];
            }
            
            if($arResult["NAV_RESULT_EPILOG"]["ELEMENTS_ID"])
            {
                $arFilter["ID"] = $arResult["NAV_RESULT_EPILOG"]["ELEMENTS_ID"];
            }

            $arResult["ITEMS"] = self::getElementData($arFilter);
        }

        #Получить все настройки модуля
        $modParams = Handler::getOptions();

        $serverName = SITE_SERVER_NAME;
        $protocol = (\CMain::IsHTTPS()) ? "https://" : "http://"; 
        $currentPage = $APPLICATION->GetCurPage();
        $seoDetailPage = $protocol.$serverName.$currentPage;
        $seoTitle = !empty($arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']) ? $arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] : $arResult['NAME'];
        $siteLogo = $protocol.$serverName.$modParams["META_TAG"]["LOGO"];

        if(mb_strlen($arResult["SECTION"]["PATH"][0]["DESCRIPTION"]) > 0)
            $seoDescription = \TruncateText(strip_tags($arResult["SECTION"]["PATH"][0]["DESCRIPTION"]), 150);
        else if(mb_strlen($arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]) > 0)
            $seoDescription = \TruncateText(strip_tags($arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]), 150);
        else
            $seoDescription = $arResult["NAME"];

        $seoDescription = preg_replace("/\s{2,}/"," ",$seoDescription);

        if( $arResult["PREVIEW_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["PREVIEW_PICTURE"]["SRC"];
        }
        else if( $arResult["DETAIL_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["DETAIL_PICTURE"]["SRC"];
        }
        else if( $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0] )
        {
            $arMorePhoto = \CFile::GetFileArray($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0]); 
            $seoImage = $protocol.$serverName.$arMorePhoto["SRC"];
        }
        else
        {
            $seoImage = $siteLogo;
        }

        if($arResult["ITEMS"])
        {
            $jsMetaTag = array();

            $shemaOrgCount = $arResult["NAV_RESULT"]->NavPageSize * $arResult["NAV_RESULT"]->NavPageNomer - 1;
            foreach($arResult["ITEMS"] as $arItem)
            {
                $seoItenPage = $protocol.$serverName.$arItem["DETAIL_PAGE_URL"];
                $seoItemTitle = !empty($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME'];

                if( $arItem["PREVIEW_PICTURE"]["SRC"] )
                {
                    $seoItemImage = $protocol.$serverName.$arItem["PREVIEW_PICTURE"]["SRC"];
                }
                else if( $arItem["DETAIL_PICTURE"]["SRC"] )
                {
                    $seoItemImage = $protocol.$serverName.$arItem["DETAIL_PICTURE"]["SRC"];
                }
                else if( $arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0] )
                {
                    $arMorePhoto = \CFile::GetFileArray($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0]); 
                    $seoItemImage = $protocol.$serverName.$arMorePhoto["SRC"];
                }
                else
                {
                    $seoItemImage = $siteLogo;
                }

            	$haveOffers = !empty($arItem['OFFERS']);
            	if ($haveOffers)
            		$actualItem = isset($arItem['OFFERS'][$arItem['OFFERS_SELECTED']]) ? $arItem['OFFERS'][$arItem['OFFERS_SELECTED']] : reset($arItem['OFFERS']);
            	else
            		$actualItem = $arItem;

                if(!$actualItem['ITEM_PRICES']["BASE_PRICE"])
                    continue;

                $jsMetaTag[] = array(
                    "@context" => "https://schema.org",
                    "@type" => "Offer",
                    "image" => $seoItenPage,
                    "name" => $seoItemTitle,
                    "price" => $actualItem['ITEM_PRICES']["BASE_PRICE"],
                    "priceCurrency" => $actualItem['ITEM_PRICES']["CURRENCY"],
                    "url" => $seoItemImage
                );
            }

            $jsMetaTag = json_encode($jsMetaTag,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $html = '';
        $html .= '<meta property="og:title" content="'.$seoTitle.'" />'."\r\n";
        $html .= '<meta property="og:description" content="'.$seoDescription.'" />'."\r\n";
        $html .= '<meta property="og:type" content="product" />'."\r\n";
        $html .= '<meta property="og:url" content="'.$seoDetailPage.'" />'."\r\n";
        $html .= '<meta property="og:image" content="'.$seoImage.'" />'."\r\n";
        $html .= '<meta property="og:image:type" content="image/jpeg" />';

        if($arResult["ITEMS"])
            $html .= '<script type="application/ld+json">'.$jsMetaTag.'</script>';

        return $html;
    }

    /**
     * @brief Для страницы Контакты
     * @param $arResult = массив данных
     * @return Разметка
     **/
	public function MetaTagOrganization($arResult = array())
	{
        if(!$arResult)
            return false;

        #Получить все настройки модуля
        $modParams = Handler::getOptions();

        $serverName = SITE_SERVER_NAME;
        $protocol = (\CMain::IsHTTPS()) ? "https://" : "http://"; 
        $seoDetailPage = $protocol.$serverName.$arResult["DETAIL_PAGE_URL"];
        $seoTitle = !empty($arResult["NAME"]) ? $arResult["NAME"] : $serverName;
        $siteLogo = $protocol.$serverName.$modParams["META_TAG"]["LOGO"];
        $seoImage = $siteLogo;
        $seoDescription = "";

        if( $arResult["PREVIEW_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["PREVIEW_PICTURE"]["SRC"];
        }
        else if( $arResult["DETAIL_PICTURE"]["SRC"] )
        {
            $seoImage = $protocol.$serverName.$arResult["DETAIL_PICTURE"]["SRC"];
        }
        else if( $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0] )
        {
            $arMorePhoto = \CFile::GetFileArray(   $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0]  ); 
            $seoImage = $protocol.$serverName.$arMorePhoto["SRC"];
        }
        else
        {
            $seoImage = $siteLogo;
        }

        $jsMetaTag = array(
        	"@context" => array(
                "@vocab" => "https://schema.org/"
            ),
        	"@graph" => array()
        );

       foreach($arResult as $arItem)
       {
            $arOrganization = array();

            $arOrganization["@type"] = "LocalBusiness";
            $arOrganization["name"] = ( ($arItem["NAME"]) ? $arItem["NAME"] : $serverName );
            $arOrganization["image"] = $siteLogo;
            $arOrganization["description"] = ( ($arItem["DESCRIPTION"]) ? $arItem["DESCRIPTION"] : $serverName );
            $seoDescription .= $arItem["DESCRIPTION"].' / ';

            if($arItem["PRICE_RANGE"])
                $arOrganization["priceRange"] = $arItem["PRICE_RANGE"];

            if($arItem["FACRBOOK"])
                $arOrganization["sameAs"] = array($arItem["FACRBOOK"]);

            if($arItem["GEO_LATITUDE"] && $arItem["GEO_LONGITUDE"])
            {
                $arOrganization["geo"] = array(
    	            "@type" => "GeoCoordinates",
	                "latitude" => $arItem["GEO_LATITUDE"],
	                "longitude" => $arItem["GEO_LONGITUDE"],
                );
            }

            $arOrganization["address"] = array("@type" => "PostalAddress");
            
            if($arItem["STREET_ADDRESS"])
                $arOrganization["address"]["streetAddress"] = $arItem["STREET_ADDRESS"];

            if($arItem["ADDRESS_LOCALITY"])
                $arOrganization["address"]["addressLocality"] = $arItem["ADDRESS_LOCALITY"];

            if($arItem["POSTAL_CODE"])
                $arOrganization["address"]["postalCode"] = $arItem["POSTAL_CODE"];

            if($arItem["TELEPHONE"])
                $arOrganization["address"]["telephone"] = $arItem["TELEPHONE"];

            if($arItem["FAX_NUMBER"])
                $arOrganization["address"]["faxNumber"] = $arItem["FAX_NUMBER"];

            if($arItem["EMAIL"])
                $arOrganization["address"]["email"] = $arItem["EMAIL"];

            $jsMetaTag["@graph"][] = $arOrganization;
       }

        $jsMetaTag = json_encode($jsMetaTag,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $html = '';
        $html .= '<meta property="og:title" content="'.$seoTitle.'" />'."\r\n";
        $html .= '<meta property="og:description" content="'.trim($seoDescription).'" />'."\r\n";
        $html .= '<meta property="og:type" content="article" />'."\r\n";
        $html .= '<meta property="og:url" content="'.$seoDetailPage.'" />'."\r\n";
        $html .= '<meta property="og:image" content="'.$seoImage.'" />'."\r\n";
        $html .= '<meta property="og:image:type" content="image/jpeg" />';

        if($arResult)
            $html .= '<script type="application/ld+json">'.$jsMetaTag.'</script>';

        return $html;
    }

    /**
     * @brief Получить данные о разделе
     * @param $arResult = массив данных
     * @return Разметка
     **/
	public function getSectionData($arFilter = array())
	{
        if(!$arFilter && !$arFilter["IBLOCK_ID"])
            return;

        $arOrder = array();
        $bIncCnt = false;
        $arSelect = array(
            "ID",
            "DATE_CREATE",
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
            "NAME",
            "CODE",
            "PICTURE",
            "DETAIL_PICTURE",
            "DESCRIPTION",
            "LIST_PAGE_URL"
        );

        $dbSectionList = \CIBlockSection::GetList($arOrder,$arFilter,$bIncCnt,$arSelect);
        if($arSection = $dbSectionList->Fetch())
        {
            #SEO настройки
        	$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arSection["IBLOCK_ID"], $arSection["ID"]);
        	$arSection["IPROPERTY_VALUES"] = $ipropValues->getValues();

            if($arSection["DETAIL_PICTURE"])
                $arSection["DETAIL_PICTURE"] = \CFile::GetFileArray($arSection["DETAIL_PICTURE"]);
            else if($arSection["PICTURE"])
                $arSection["PREVIEW_PICTURE"] = \CFile::GetFileArray($arSection["PICTURE"]);
        }

        return $arSection;
    }

    /**
     * @brief Получить данные о разделе
     * @param $arResult = массив данных
     * @return Разметка
     **/
	public function getElementData($arFilter = array())
	{
        if(!$arFilter && !$arFilter["IBLOCK_ID"])
            return;

        #Определим тип инфолока (элемент, товар)
        $iblockTypeCatalog = \CCatalog::GetByID($arFilter["IBLOCK_ID"]);

        if($iblockTypeCatalog)
        {
            #Тип цен
            $priceTypeFirst["ID"] = "2";
            $dbPriceType = \CCatalogGroup::GetList(array("SORT" => "ASC"),array());
            while ($arPriceType = $dbPriceType->Fetch())
            { 
                if( $arPriceType["BASE"] == "Y" ){ $priceTypeFirst = $arPriceType; break; }
            }
        }

        $arOrder = array("ID"=>"ASC");
        $arNavParams = array("nPageSize"=>"12");
        $arSelect = array(
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
            "ID",
            "NAME",
            "PREVIEW_PICTURE",
            "DETAIL_PICTURE",
            "DETAIL_PAGE_URL",
            "DETAIL_TEXT",
            "PREVIEW_TEXT"
        );

        $obElementList = \CIBlockElement::GetList($arOrder,$arFilter,false,$arNavParams,$arSelect);

    	while($obItem = $obElementList->GetNextElement())
        {
    		$arFields = $obItem->GetFields();
            $arFields["PROPERTIES"] = $obItem->GetProperties(array("ID" => "ASC"),array("ID" => "MORE_PHOTO"));

            #SEO настройки
    		$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arFields["IBLOCK_ID"], $arFields["ID"]);
    		$arFields["IPROPERTY_VALUES"] = $ipropValues->getValues();

            if($arFields["PREVIEW_PICTURE"])
                $arFields["PREVIEW_PICTURE"] = \CFile::GetFileArray($arFields["PREVIEW_PICTURE"]);
            else if($arFields["DETAIL_PICTURE"])
                $arFields["DETAIL_PICTURE"] = \CFile::GetFileArray($arFields["DETAIL_PICTURE"]);

            if($iblockTypeCatalog)
            {
                #Цена
                $arPrice = \Bitrix\Catalog\PriceTable::getList(
                    array(
                        'select'  => array("*"),
                        'filter'  => array("CATALOG_GROUP_ID"=>$priceTypeFirst["ID"], "PRODUCT_ID"=>$arFields["ID"]),
                        'order'   => array("ID" => "ASC")
                    )
                )->Fetch();

                #Расёт цены с учётом скидок
				$calculatePrice = \CCatalogProduct::GetOptimalPrice($arFields["ID"],1,array(1,2,3),'N',array($arPrice),false,array());
                $calculatePrice["RESULT_PRICE"]["BASE_PRICE_FORMAT"] = FormatCurrency($calculatePrice["RESULT_PRICE"]['BASE_PRICE'], $calculatePrice["RESULT_PRICE"]["CURRENCY"]);
                $calculatePrice["RESULT_PRICE"]["DISCOUNT_PRICE_FORMAT"] = FormatCurrency($calculatePrice["RESULT_PRICE"]['DISCOUNT_PRICE'], $calculatePrice["RESULT_PRICE"]["CURRENCY"]);

                $arFields['ITEM_PRICES'] = $calculatePrice["RESULT_PRICE"];
            }

    		$arResult[] = $arFields;
    	}

        return $arResult;
    }

    /**
     * @brief Пагинация MetaTag
     * @param $arResult["NAV_RESULT_EPILOG"] = массив данных о пагинации
     * @return Разметка
     **/
	public function MetaPagenavigation($arResult = array())
	{
        if(!$arResult)
            return false;

        global $APPLICATION;

        $parts = parse_url($APPLICATION->GetCurPageParam());
        parse_str($parts['query'], $query);
        foreach( $query as $key => $arItem )
        {
            if(strpos($key,"PAGEN_") !== false) 
            {
                $pageKey = $key;
                break;
            }
        }
        if($pageKey)
        {
            $navNum = explode("_", $pageKey);
            $navNum = $navNum[1];
        }

        if(strpos($APPLICATION->GetCurUri(), '?PAGEN') == true)
        {
            if($arResult["NAV_RESULT_EPILOG"]["PAGEN"] < 2 )
            {
                if($arResult["SECTION_PAGE_URL"])
                {
                    header( "Location: ".$arResult["SECTION_PAGE_URL"] ,TRUE,301 );
                }
                else
                {
                    $CURRENT_PAGE = $APPLICATION->GetCurPage();
                    header( "Location: ".$CURRENT_PAGE ,TRUE,301 );
                }
            }
            else if( $arResult["NAV_RESULT_EPILOG"]["PAGEN"] > $arResult["NAV_RESULT_EPILOG"]["NavPageCount"] )
            {
                $CURRENT_PAGE = $APPLICATION->GetCurPage();
                header( "Location: ".$CURRENT_PAGE."?PAGEN_".$arResult["NAV_RESULT_EPILOG"]["NavNum"]."=".$arResult["NAV_RESULT_EPILOG"]["NavPageCount"] ,TRUE,301 );
            }
        }

        if(
            intval($query[$pageKey]) > 1 &&
            $arResult['NAV_RESULT_EPILOG']['NavNum'] == $navNum
        )
        {
            $html = '<link rel="canonical" href="'.$arResult["SECTION_PAGE_URL"].'" />';

            if($arResult['NAV_RESULT_EPILOG']['NavPageNomer'] == 2)
                $html .= '<link rel="prev" href="'.$arResult["SECTION_PAGE_URL"].'">';
            else if($arResult['NAV_RESULT_EPILOG']['NavPageNomer'] > 2)
                $html .= '<link rel="prev" href="'.$arResult["SECTION_PAGE_URL"]. '?'.$pageKey.'='.($arResult['NAV_RESULT_EPILOG']['NavPageNomer'] - 1).'">';

            if($arResult['NAV_RESULT_EPILOG']['PAGEN'] < $arResult['NAV_RESULT_EPILOG']['NavPageCount'])
                $html .= '<link rel="next" href="'.$arResult["SECTION_PAGE_URL"].'?'.$pageKey.'='.($arResult['NAV_RESULT_EPILOG']['NavPageNomer'] + 1).'">';

            return $html;
        }
    }
}