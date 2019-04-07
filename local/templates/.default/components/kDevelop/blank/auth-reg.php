<div id="reg-auth" class="popup" data-animate>
    <div class="popup_wrapper">
        <div class="popup_content animate-start js-popup_content">
            <a href="#" class="popup_content-close" data-popup-close><i class="icon close"></i></a>
            <div class="js-tabs">
                <div class="content_tab">
                    <a href="#" class="content_tab-item link dashed" data-tab_target="#reg-content">Регистрация</a>
                    <a href="#" class="content_tab-item link dashed" data-tab_target="#auth-content">Вход</a>
                </div>
                <div data-tab_content>
                    <div id="reg-content" data-tab_item>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.register",
                            "",
                            Array(
                                "USER_PROPERTY_NAME" => "",
                                "SEF_MODE" => "Y",
                                "SHOW_FIELDS" => Array(),
                                "REQUIRED_FIELDS" => Array(),
                                "AUTH" => "Y",
                                "USE_BACKURL" => "Y",
                                "SUCCESS_PAGE" => "",
                                "SET_TITLE" => "N",
                                "USER_PROPERTY" => Array(),
                                "SEF_FOLDER" => SITE_DIR,
                                "VARIABLE_ALIASES" => Array()
                            )
                        );?>
                    </div>
                    <div id="auth-content" data-tab_item>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:system.auth.form",
                            "",
                            Array(
                                "REGISTER_URL" => $APPLICATION->GetCurPage(false),
                                "FORGOT_PASSWORD_URL" => "",
                                "PROFILE_URL" => "#",
                                "SHOW_ERRORS" => "Y"
                            )
                        );?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>