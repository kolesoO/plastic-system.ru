<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("keywords", "");
$APPLICATION->SetPageProperty("description", "Санкт-Петербург Адрес склада и офиса продаж: Санкт-Петербург, ул. Рабочая 16Г Телефон: +7 (812) 313-75-50 E-mail: mail@plsm.ru Режим работы: Пн. — Чт.: 9:00 — 17:30, Пт.— до 17:00, Сб. — Вс.: выходной Москва Адрес склада: МО, г. Дзержинский, ул. Алексеевская, д. 2 Телефон: +7 (495) 162-75-50 E-mail: msk@plsm.ru Режим работы: Пн. — Чт.: 9:00 — 17:30, Пт.— до 17:00, Сб. — Вс.: [&hellip;]");
$APPLICATION->SetPageProperty("title", "Контакты - Складские лотки и пластиковая тара от компании ООО «Пластик Система»");
$APPLICATION->SetPageProperty("header_section-class", "section");
$APPLICATION->SetTitle("Контакты");

//Список складов
$APPLICATION->IncludeComponent(
    "kDevelop:catalog.store.list",
    "contacts",
    Array(
        "PHONE" => "Y",
        "EMAIL" => "Y",
        "SCHEDULE" => "Y",
        "PATH_TO_ELEMENT" => "store/#store_id#",
        "MAP_TYPE" => "0",
        "SET_TITLE" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "IMAGE_SIZE" => ["WIDTH" => 290, "HEIGHT" => 168]
    )
);
//end
?>

    <div class="block_wrapper big">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            ".default",
            [
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_TEMPLATE_PATH . "/include/contacts/requisites.php"
            ],
            false
        );?>
    </div>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');