<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<div class="list_controls clearfix">
    <a href="#" class="list_controls-item active js-toggle-class" data-class_delete="list" data-target="#catalog-list">
        <i class="icon tile"></i>
        <span>плиткой</span>
    </a>
    <a href="#" class="list_controls-item js-toggle-class" data-class_add="list" data-target="#catalog-list">
        <i class="icon list"></i>
        <span>списком</span>
    </a>
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