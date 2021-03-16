<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<a href="tel:<?=str_replace([" ", "-", "(", ")"], "", $arResult["PHONE"])?>" class="<?=preg_replace("/^([^\(]*)\(([\d]+)\)([^\)]*)$/", "$2", $arResult["PHONE"])?>"><b><?=$arResult["PHONE"]?></b></a>
<span class="hidden-xs"><?=$arResult["UF_SCHEDULE"]?></span>
