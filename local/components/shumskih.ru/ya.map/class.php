<?

use Bitrix\Main\Localization\Loc;
use App\Repository\BitrixApiRepository\YaMapBitrixApiRepository;

class YaMap extends CBitrixComponent
{
    private function _checkModules()
    {
        if (!CModule::includeModule('iblock')) {
            throw new Exception(Loc::getMessage("MODULES_NOT_LOADED"));
        }
    }

    private function app()
    {
        global $APPLICATION;
        return $APPLICATION;
    }

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        try {
            $this->_checkModules();
        } catch (Exception $e) {
            $e->getMessage();
        }

        $this->arResult['API_KEY'] = $this->getApiKey();

        if (isset($_POST['saveForm'])) {
            $this->endResultCache();
            $this->arResult['SAVED'] = $this->save();
        }

        $this->includeComponentTemplate();
    }

    private function save()
    {
        $locality = $_POST['locality'];
        $street = $_POST['street'];
        $building = $_POST['building'];
        $coordinates = $_POST['coordinates'];

        try {
            $isSaved = YaMapBitrixApiRepository::save($this->arParams['IBLOCK_ID'], $locality, $street, $building, $coordinates);
        } catch (Exception $e) {
            $e->getMessage();
        }

        if (!$isSaved) return false;

        return true;
    }

    private function getApiKey() {
        return $this->arParams['API_KEY'];
    }
}
