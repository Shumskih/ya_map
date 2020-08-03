<?php

namespace App\Repository\BitrixApiRepository;

use CBitrixComponent;
use Exception;
use App\Repository\BitrixApiRepository\Dao\YaMapDao;

class YaMapBitrixApiRepository extends CBitrixComponent
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
        return YaMapDao::save($iBlockId, $locality, $street, $building, $coordinates);
    }
}