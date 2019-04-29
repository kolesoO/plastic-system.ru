<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<form class="main_search" action="" method="get">
    <input type="text" class="main_search-input" name="q" placeholder="<?=GetMessage("CT_BSP_INPUT_PLACEHOLDER")?>" value="<?=$arResult["REQUEST"]["QUERY"]?>">
    <button type="submit">
        <i class="icon search"></i>
    </button>
</form>