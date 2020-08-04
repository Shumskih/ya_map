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