<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Я.Карта - Форма добавления");
?><?$APPLICATION->IncludeComponent(
	"shumskih.ru:ya.map", 
	".default", 
	array(
		"ARTICLES_IBLOCK_ID" => "",
		"ARTICLES_IBLOCK_TYPE" => "news",
		"COMMENTS_COUNT" => "3",
		"IBLOCK_ID" => "17",
		"IBLOCK_TYPE" => "ya_map",
		"COMPONENT_TEMPLATE" => ".default",
		"API_KEY" => "9d61aa15-4d3a-4f36-b7fe-dc961af172b3"
	),
	false
);?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>