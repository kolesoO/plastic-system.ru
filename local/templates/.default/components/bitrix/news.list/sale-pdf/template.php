<?php

declare(strict_types=1);
?>

<?php foreach ($arResult['ITEMS'] as $item) :?>
    <a
        data-fancybox
        href="<?=CFile::GetPath($item['PROPERTIES']["FILE"]["VALUE"]);?>"
        target="_blank"
    >
        <i class="icon download"></i>
        <span class="small hidden-md hidden-xs red"><?php echo $item['NAME']?></span>
    </a>
<?php endforeach;?>
