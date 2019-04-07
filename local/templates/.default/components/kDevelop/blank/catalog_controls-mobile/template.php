<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="list_controls clearfix">
    <a href="#" class="form_button color" data-popup-open="#catalog-filter-popup">Показать фильтры</a>
    <div class="list_controls-right">
        <?foreach ($arParams["SORT"] as $arSortItem) :
            $class = "dropdown-btn link";
            if ($arSortItem["NO_LAST_SORT"] == "Y") {
                $class = "link";
            } elseif($_GET[$arSortItem["CODE"]] == "asc") {
                $class .= " active";
            }
            ?>
            <a href="<?=$arSortItem["URL"]?>" class="<?=$class?>">
                <span><?=$arSortItem["TITLE"]?></span>
            </a>
        <?endforeach?>
    </div>
</div>