<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arCurrentValues */
use Bitrix\Main\Loader;

if(!Loader::includeModule("iblock"))
    return;

$arIBlockType = $arIBlockType2 = CIBlockParameters::GetIBlockTypes();

$arIBlock = [];
$rsIBlock = CIBlock::GetList(
    ["sort" => "asc"],
    ["TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"]
);
while($arr=$rsIBlock->Fetch()){
    $arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}

$arComponentParameters = array(
    "GROUPS" => array(
        "BUTTONS" => array(
            "NAME" => GetMessage('YAMAP_SETTINGS'),
            "SORT" => "200",
        ),
    ),
    "PARAMETERS" => array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage('YAMAP_IBLOCK_TYPE'),
            "TYPE" => "LIST",
            "VALUES" => $arIBlockType,
            "REFRESH" => "N",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage('YAMAP_IBLOCK_ID'),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
            "REFRESH" => "N",
        ),
        "API_KEY" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage('YAMAP_API_KEY'),
            "TYPE" => "STRING",
            "REFRESH" => "N",
        ),
    ),
);