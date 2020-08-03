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

<script>
    ymaps.ready(init);

    function init() {
        let locality = document.querySelector('#locality'),
            street = document.querySelector('#street'),
            building = document.querySelector('#building'),
            coordinatesInput = document.querySelector('#coordinates'),
            address = 'Москва',
            myMap;

        locality.addEventListener('change', () => {
            address = buildGeocodeUrl(locality.value, street.value, building.value);
            if (address && myMap) {
                myMap.destroy();
                myMap = null;
                buildMap();
                buildGeocode();
            }
        });
        street.addEventListener('change', () => {
            address = buildGeocodeUrl(locality.value, street.value, building.value);
            if (address && myMap) {
                myMap.destroy();
                myMap = null;
                buildMap();
                buildGeocode();
            }
        });
        building.addEventListener('change', () => {
            address = buildGeocodeUrl(locality.value, street.value, building.value);
            if (address && myMap) {
                myMap.destroy();
                myMap = null;
                buildMap();
                buildGeocode();
            }
        });

        if (!myMap) {
            buildMap();
            buildGeocode();
        }

        function buildMap() {
            myMap = new ymaps.Map('map', {
                center: [55, 34],
                zoom: 9
            });
        }

        function buildGeocode() {
            // Поиск координат
            ymaps.geocode(address, {
                results: 1
            })
                .then(function (res) {
                    // Выбираем первый результат геокодирования.
                    let placemark = res.geoObjects.get(0),
                        // Координаты геообъекта.
                        coords = placemark.geometry.getCoordinates(),
                        // Область видимости геообъекта.
                        bounds = placemark.properties.get('boundedBy');

                    // Заполняет координатами сурытое поле формы
                    coordinatesInput.value = coords;

                    placemark.options.set('preset', 'islands#blueIcon');
                    placemark.options.set('draggable', 'true');
                    // Получаем строку с адресом и выводим в иконке геообъекта.
                    placemark.properties.set('iconCaption', placemark.getAddressLine());

                    // Добавляем первый найденный геообъект на карту.
                    myMap.geoObjects.add(placemark);
                    // Масштабируем карту на область видимости геообъекта.
                    myMap.setBounds(bounds, {
                        // Проверяем наличие тайлов на данном масштабе.
                        checkZoomRange: true
                    });

                    placemark.events.add("dragend", function (e) {
                        coords = this.geometry.getCoordinates();
                        let coordsForSave = [coords[0].toFixed(6), coords[1].toFixed(6)];
                        placemark.geometry.setCoordinates(coordsForSave);
                        coordinatesInput.value = coordsForSave;
                    }, placemark);


                });
        }
    }

    /*
    Строит строку запроса, состоящую из названия населённого пункта, названия улицы, номера дома.
    Например: Саратов, Ставропольская, 67
    */
    function buildGeocodeUrl(locality = '', street = '', building = '') {
        if (locality.length >= 2) {
            let address = '';
            address += locality + ', ' + street + ', ' + building;

            return address;
        }

        return null;
    }
</script>