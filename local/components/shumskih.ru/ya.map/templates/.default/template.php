<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$apiKey = $arResult['API_KEY'];
$this->addExternalJs("https://api-maps.yandex.ru/2.1/?apikey=$apiKey&lang=ru_RU");
?>

<div class="d-flex justify-content-center align-center mt-3 mb-5">
    <div id="map" style="width: 600px; height: 400px"></div>
</div>
<div class="container w-50">
    <? if ($arResult['SAVED']): ?>
        <div class="alert alert-success" role="alert"><?= Loc::getMessage("ADDRESS_SAVED") ?></div>
    <? endif; ?>
    <form action="" method="POST">
        <input type="hidden" name="coordinates" value="test" id="coordinates">
        <div class="form-group">
            <label for="locality"><?= Loc::getMessage("LOCALITY") ?></label>
            <input class="form-control form-control-lg" type="text" name="locality"
                   placeholder="<?= Loc::getMessage("LOCALITY") ?>" id="locality" autocomplete="on">
        </div>
        <div class="form-group">
            <label for="street"><?= Loc::getMessage("STREET") ?></label>
            <input class="form-control form-control-lg" type="text" name="street"
                   placeholder="<?= Loc::getMessage("STREET") ?>" id="street" autocomplete="on">
        </div>
        <div class="form-group">
            <label for="house-number"><?= Loc::getMessage("BUILDING") ?></label>
            <input class="form-control form-control-lg" type="number" name="building"
                   placeholder="<?= Loc::getMessage("BUILDING") ?>" id="building" autocomplete="on">
        </div>
        <button type="submit" name="saveForm" class="btn btn-outline-success mt-2"
                id="formSubmitter"><?= Loc::getMessage("SAVE") ?></button>
    </form>
</div>