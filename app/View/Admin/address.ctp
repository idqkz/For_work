<div class='wrapper'>
	<?php

		echo $this->Form->create('Text');
		echo $this->Html->tag('h2', 'Адрес на карте');
		echo $this->Form->hidden('id');
		echo $this->Form->hidden('name');
		echo $this->Form->input('text', array('label' => 'Адрес'));
		echo $this->Html->tag('label', 'Кликните по карте, чтобы выбрать адрес');
		echo $this->Html->div(null, '', array('id' => 'map'));

		echo $this->Form->submit('Сохранить', array('class' => 'btn btn-success'));

		echo $this->Form->end();

	?>
</div>

<script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">
ymaps.ready(init);

function init() {
	if (document.getElementById('TextName').value != '') {
		coords  = document.getElementById('TextName').value.split(',');
	} else {
		var coords = [51.1278, 71.4307];	
	}
    var myPlacemark,
        myMap = new ymaps.Map('map', {
            center: coords,
            zoom: 12
        });

    // Слушаем клик на карте
    myMap.events.add('click', function (e) {
        var coords = e.get('coords');

        // Если метка уже создана – просто передвигаем ее
        if (myPlacemark) {
            myPlacemark.geometry.setCoordinates(coords);
        }
        // Если нет – создаем.
        else {
            myPlacemark = createPlacemark(coords);
            myMap.geoObjects.add(myPlacemark);
            // Слушаем событие окончания перетаскивания на метке.
            myPlacemark.events.add('dragend', function () {
                getAddress(myPlacemark.geometry.getCoordinates());
            });
        }
        getAddress(coords);
        form = document.getElementById('TextName');
        form.value = ([coords[0].toPrecision(6), coords[1].toPrecision(6)].join(', '));
    });

    // Создание метки
    function createPlacemark(coords) {
        return new ymaps.Placemark(coords, {
            iconContent: document.getElementById('TextText').value
        }, {
            preset: 'islands#violetStretchyIcon',
            draggable: true
        });
    }

    // Определяем адрес по координатам (обратное геокодирование)
    function getAddress(coords) {
        myPlacemark.properties.set('iconContent', 'поиск...');
        ymaps.geocode(coords).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);

            myPlacemark.properties
                .set({
                    iconContent: firstGeoObject.properties.get('name'),
                    balloonContent: firstGeoObject.properties.get('text')
                });
        });
    }

	if (document.getElementById('TextName').value != '') {
		coords  = document.getElementById('TextName').value.split(',');

	    myPlacemark = createPlacemark(coords);
            myMap.geoObjects.add(myPlacemark);
            // Слушаем событие окончания перетаскивания на метке.
            myPlacemark.events.add('dragend', function () {
                getAddress(myPlacemark.geometry.getCoordinates());
            });

	}


}
</script>