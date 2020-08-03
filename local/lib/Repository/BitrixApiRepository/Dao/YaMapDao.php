<?php

namespace App\Repository\BitrixApiRepository\Dao;

use CIBlockElement;
use Exception;

class YaMapDao
{
    /**
     * @param int $iBlockId
     * @param string $locality
     * @param string $street
     * @param int $building
     * @param string $coordinates
     * @return bool
     * @throws Exception
     */
    public static function save(int $iBlockId, string $locality, string $street, int $building, string $coordinates): bool
    {
        $el = new CIBlockElement;

        $name = $locality . ', ' . $street . ', ' . $building;

        $props = [
            "ATTR_LOCALITY" => $locality,
            "ATTR_STREET"   => $street,
            "ATTR_BUILDING" => $building,
            "ATTR_MAP"      => $coordinates
        ];

        $arLoadProductArray = [
            "ACTIVE_FROM"       => date('d.m.Y H:i:s'),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"         => $iBlockId,
            "ACTIVE"            => "Y",
            "NAME"              => $name,
            "PROPERTY_VALUES"   => $props
        ];


        // метод Add() возвращает либо id либо false
        $result = $el->Add($arLoadProductArray);
        if (!$result) return false;

        return true;
    }
}