<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

if(CModule::IncludeModule("iblock"))
{

$IBLOCK_ID = 54;

$arOrder = Array("SORT"=>"DESC");   
$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_FILE");
$arFilter = Array("IBLOCK_ID"=>$IBLOCK_ID, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

    while($ob = $res->GetNextElement())
    {
    $arFields = $ob->GetFields();  
	 $arFields = $ob->GetFields();  
 $arProps = $ob->GetProperties();      
    $aMenuLinksExt[] = Array(
                $arFields['NAME'],
                CFile::GetPath($arProps["FILE"]["VALUE"]),
                Array(),
                 Array("header-menu-item" => "red"),
                ""
                );
    
    }       
    
}   

$aMenuLinks = array_merge($aMenuLinks,$aMenuLinksExt);
?>