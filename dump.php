<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\kDevelop\Service\Logger::setFile("catalog-pictures.csv");
//\kDevelop\Service\Logger::saveCatalogPictures();
\kDevelop\Service\Logger::loadCatalogPictures();