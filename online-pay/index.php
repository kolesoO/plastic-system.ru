<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("keywords", "");
$APPLICATION->SetPageProperty("description", "В этом разделе Вы можете произвести оплату с помощью банковских карт VISA или MasterCard.  ВАЖНО: После оформления заказа дождитесь подтверждения от менеджера компании, после чего производите оплату. В противном случае, до подтверждения заказа менеджером, компания не гарантирует наличие свободного остатка товара на складе.  После подтверждения, введите номер, дату и сумму счета, который Вам выслал менеджер на электронную &hellip;");
$APPLICATION->SetPageProperty("title", "Онлайн оплата - Складские лотки и пластиковая тара от компании ООО «Пластик Система»");
$APPLICATION->SetPageProperty("header_section-class", "section");
$APPLICATION->SetTitle("Онлайн оплата");
?>

<div class="block_wrapper">
    <div class="block_content">
        <div class="block_content-item col-lg-12 col-md-12 col-xs-24">
            <form id="payment-form" class="def_form" onsubmit="obAjax.initPayment(this, event)">
                <div class="animate_input js-animate_input">
                    <label for="number">Номер счета, например, 1366 от 12.10.2018</label>
                    <input id="number" type="text" name="order_number" required>
                </div>
                <div class="animate_input js-animate_input">
                    <label for="sum">Сумма, р.</label>
                    <input id="sum" type="text" name="order_price" required>
                </div>
                <div class="animate_input">
                    <input type="checkbox" required>
                    <span>Нажимая на кнопку "Оплатить", я соглашаюсь с правилами оплаты в онлайн системе данного сайта.</span>
                </div>
                <button class="form_button color col-lg-6" type="submit">Оплатить</button>
            </form>
        </div>
        <div class="block_content-item col-lg-12 col-md-12 col-xs-24">
            <p>В этом разделе Вы можете произвести оплату с помощью банковских карт VISA или MasterCard.</p>
            <p><b>ВАЖНО:</b> После оформления заказа <b>дождитесь подтверждения</b>от менеджера компании, после чего производите оплату. В противном случае, до подтверждения заказа менеджером, компания не гарантирует наличие свободного остатка товара на складе. После подтверждения, введите номер, дату и сумму счета, который Вам выслал менеджер на электронную почту.</p>
            <p>Денежные средства поступают моментально. Прием платежей производится через расчетный центр <a href="https://front.platron.ru/" class="link" target="_blank">Platron</a>.</p>
            <p><a href="/upload/docs/Publichnaya_oferta.docx" class="link" target="_blank">Публичная оферта</a></p>
        </div>
    </div>
</div>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');