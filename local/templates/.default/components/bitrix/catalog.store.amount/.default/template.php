<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
?>

<div class="title-3">Наличие</div>
<div class="cart_info-wrap">
    <?foreach ($arResult["STORES"] as $arStore) :
        $arQntInfo = \kDevelop\Help\Tools::getQntInfo($arStore["REAL_AMOUNT"], "CSA");
        $class = "table_list-status";
        if (strlen($arQntInfo["CLASS"]) > 0) {
            $class .= " ".$arQntInfo["CLASS"];
        }
        ?>
        <div class="cart_info-wrap-item">
            <span><?=$arStore["TITLE"]?></span>
            <div class="<?=$class?>"><?=Loc::getMessage($arQntInfo["MSG_CODE"])?></div>
        </div>
    <?endforeach?>
</div>