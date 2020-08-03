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
            btn = document.querySelector('#formSubmitter'),
            coordinatesInput = document.querySelector('#coordinates'),
            address = 'Москва',
            myMap;

        locality.addEventListener('change', () => {
            console.log(buildGeocodeUrl(locality.value, street.value, building.value));
            address = buildGeocodeUrl(locality.value, street.value, building.value);
            if (address && myMap) {
                myMap.destroy();
                myMap = null;
                buildMap();
                buildGeocode();
            }
        });
        street.addEventListener('change', () => {
            console.log(buildGeocodeUrl(locality.value, street.value, building.value));
            address = buildGeocodeUrl(locality.value, street.value, building.value);
            if (address && myMap) {
                myMap.destroy();
                myMap = null;
                buildMap();
                buildGeocode();
            }
        });
        building.addEventListener('change', () => {
            console.log(buildGeocodeUrl(locality.value, street.value, building.value));
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
            let geolocation = ymaps.geolocation;
            myMap = new ymaps.Map('map', {
                center: [55, 34],
                zoom: 9
            });

            // Сравним положение, вычисленное по ip пользователя и
            // положение, вычисленное средствами браузера.
            geolocation.get({
                provider: 'yandex',
                mapStateAutoApply: true
            }).then(function (result) {
                let yandexLocationPlacemark = result.geoObjects.get(0);
                // Красным цветом пометим положение, вычисленное через ip.
                result.geoObjects.options.set('preset', 'islands#blueIcon');
                result.geoObjects.options.set('draggable', 'true');
                yandexLocationPlacemark.properties.set({
                    balloonContentBody: 'Мое местоположение'
                });
                myMap.geoObjects.add(result.geoObjects);
            });

            geolocation.get({
                provider: 'browser',
                mapStateAutoApply: true
            }).then(function (result) {
                let browserLocationPlacemark = result.geoObjects.get(0);
                // Синим цветом пометим положение, полученное через браузер.
                // Если браузер не поддерживает эту функциональность, метка не будет добавлена на карту.
                result.geoObjects.options.set('preset', 'islands#blueIcon');
                result.geoObjects.options.set('draggable', 'true');
                browserLocationPlacemark.options.set({
                    draggable: true
                });
                myMap.geoObjects.add(result.geoObjects);
            });
        }

        function buildGeocode() {
            // Поиск координат центра Нижнего Новгорода.
            ymaps.geocode(address, {
                /**
                 * Опции запроса
                 * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/geocode.xml
                 */
                // Сортировка результатов от центра окна карты.
                // boundedBy: myMap.getBounds(),
                // strictBounds: true,
                // Вместе с опцией boundedBy будет искать строго внутри области, указанной в boundedBy.
                // Если нужен только один результат, экономим трафик пользователей.
                results: 1
            })
                .then(function (res) {
                    // Выбираем первый результат геокодирования.
                    let placemark = res.geoObjects.get(0),
                        // Координаты геообъекта.
                        coords = placemark.geometry.getCoordinates(),
                        // Область видимости геообъекта.
                        bounds = placemark.properties.get('boundedBy');

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
                        console.log(coords);
                        let coordsForSave = [coords[0].toFixed(6), coords[1].toFixed(6)];
                        placemark.geometry.setCoordinates(coordsForSave);
                        coordinatesInput.value = coordsForSave;
                        console.log(coordsForSave);
                    }, placemark);


                });
        }
    }

    function buildGeocodeUrl(locality = '', street = '', building = '') {
        let l = locality, s = street, n = building;

        if (l >= 2, s > 0, n > 0) {
            let address = '';
            address += l + ', ' + s + ', ' + n;
            return address;
        }

        return null;
    }
</script>