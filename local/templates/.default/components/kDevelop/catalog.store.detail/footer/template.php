<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<div class="col-md-3">
    <b><a href="mailto:<?=$arResult["EMAIL"]?>"><?=$arResult["EMAIL"]?></a></b>
</div>
<div class="col-md-9">
    <span><a href="tel:<?=str_replace([" ", "-", "(", ")"], "", $arResult["PHONE"])?>"><b><?=$arResult["PHONE"]?></b></a> <?=$arResult["UF_SCHEDULE"]?></span>
</div>