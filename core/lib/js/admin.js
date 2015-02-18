$(document).ready(function(){
    var body = $('body');

	/************************************************************************************
	*************************************************************************************/
    positionControlElem();

	/*************************************/	
//	CKEDITOR.replace('editor1');
	/*************************************/
    //трішки приховуємо неактивні блоки
    $('.icon-eye-close').each(function(){
        $(this).parent().parent().addClass('is-no_active');
    });

    /************************************************************************************
     *************************************************************************************/


    ///////////////////////////////////////////////////////////////
    // MAP
    ///////////////////////////////////////////////////////////////
    var mainMap = $('#main-map');

    if (mainMap.length) {

        var select_id_event;

        map.addControl({
            position: 'top_right',
            content: 'Знайти місце за адресою',
            style: {
                margin: '5px',
                padding: '1px 6px',
                border: 'solid 1px #717B87',
                background: '#fff'
            },
            events: {
                click: function(){
                    $('.main-map__search_mod').addClass('visible');
                    $('.main-map__search_mod__field').focus();
                }
            }
        });

        map.setContextMenu({
            control: 'map',
            options: [{
                title: 'Додати маркер',
                name: 'addMarker',
                action: function(e) {
                    map.addMarker({
                        lat: e.latLng.lat(),
                        lng: e.latLng.lng(),
                        click: function() {
                            MapNewMarker(e.latLng.lat(), e.latLng.lng());
                        }
                    });
                }
            }]
        });

        body.on('click', '.js-map_marker_edit', function(){
            var modal = $('#mod_addPlace');
            select_id_event = $(this).attr('data-id');

            closeModalId('mod_viewPlace');

            $.ajax({
                type: 'GET',
                url: 'http://plcb.me/api/get/places/',
                data: {id:select_id_event},
                success: function(data) {
                    if (data != 'null') {
                        data = JSON.parse(data);

                        modal.find('input[name="place_name"]').val(data[0].place_title);
                        modal.find('.croppedImg').eq(0).attr('src', '/lib/img/places/' + data[0].image);
                        modal.find('.croppedImg').eq(1).attr('src', '/lib/img/places/' + data[0].image1);
                        modal.find('input[name="place_filter"]').val(data[0].filter);
                        modal.find('input[name="place_rating"]').val(data[0].rating);
                        if (data[0].special == 1) {
                            modal.find('input[name="red"]').prop('checked', true);
                        }
                        else {
                            modal.find('input[name="red"]').prop('checked', false);
                        }
                        modal.find('textarea[name="description"]').val( $("#place_editor").htmlcode(data[0].place_description) );
                        coordinates.lng = data[0].lon;
                        coordinates.lat = data[0].lat;
                    }
                }
            });

            fn_openModalId('mod_addPlace');
            fn_modal_center('#mod_addPlace');

            $('.js-btn_add_place').addClass('is-none');
            $('.js-btn_edit_place').removeClass('is-none');

        });

        body.on('click', '.js-btn_add_place', function(e) {
            e.preventDefault();
            $('.is-none').unbind();
            AddPlace();
        });

        body.on('click', '.js-btn_edit_place', function(e) {
            e.preventDefault();
            $('.is-none').unbind();
            type_red = 'edit';
            AddPlace(); //!!!
            return false;
        });

        var type_red;
        function AddPlace() {
            var send,
                red = 0,
                modal = $('#mod_addPlace');

            if (modal.find('input[name="red"]').prop('checked')) red = 1;

            modal.find('textarea[name="description"]').val( $("#place_editor").htmlcode() );

            send =  "place_title=" + modal.find('input[name="place_name"]').val()            + "&" +
                "image="       + modal.find('.croppedImg').eq(0).attr('src').split('/')[4]   + "&" +
                "image1="      + modal.find('.croppedImg').eq(1).attr('src').split('/')[4]   + "&" +
                "filter="      + modal.find('input[name="place_filter"]').val()              + "&" +
                "rating="      + modal.find('input[name="place_rating"]').val()              + "&" +
                "special="     + red                                                         + "&" +
                "lon="         + coordinates.lng                                             + "&" +
                "lat="         + coordinates.lat                                             + "&" +
                "place_description=" + modal.find('textarea[name="description"]').val()      + "&" +
                "address="     + GmapStreet(coordinates.lat, coordinates.lng);

            if (type_red == 'edit') {
                send += "&id="     + select_id_event;
            }
            $.ajax({
                type: 'GET',
                url: 'http://plcb.me/api/set/places/',
                data: send,
                success: function(data) {
                    if (data) closeModalId('mod_event_false');

                    RemoveMainMap();
                    LoadMapEvent();
                    modViewPlace.removeClass('is-visible');
                }
            });

            if (type_red == 'edit') {
                $('.js-btn_add_place').removeClass('is-none');
                $('.js-btn_edit_place').addClass('is-none');
            }

        }

        function GmapStreet(lat, lon) {
            var street, num, result;

            $.ajax({
                url: 'http://maps.google.com/maps/api/geocode/json?latlng=' + lat + ',' + lon,
                type: 'POST',
                async: false,
                success: function (data) {
                    street = data.results[0].address_components[2].short_name;
                    num = data.results[0].address_components[0].short_name;
                }
            });

            result = street + ', ' + num;

            return result;
        }

        body.on('click', '.js-map_marker_delete', function(){
            var id = $(this).attr('data-id');

            var deleteMarker = confirm('Видалити місце?');

            if (deleteMarker) {
                $.ajax({
                    url: 'http://plcb.me/api/delete/places?id=' + id,
                    type: 'GET',
                    async: false,
                    success: function (data) {
                        if (!data) {
                        }
                        else {
                            closeModalId('mod_event_false');
                        }
                        closeModalId('mod_viewPlace');
                        RemoveMainMap();
                        LoadMapEvent();
                    }
                });
            }

        });

        $('.js-mod_field_filter').focus(function(){
            $('.place_filter__list').addClass('is-visible');
        });

        $('.js-mod_field_filter').blur(function(){
            setTimeout(function() {
                $('.place_filter__list').removeClass('is-visible');
            },150);
        });

        body.on('click', '.js-mod_filter', function(){
            var text = $(this).attr('data-filter');
            $('.js-mod_field_filter').val(text);
        });


        $('.js-btn-search_address').click(function(){
            MapSearchAddress();
        });

        $('.js-map_search_form').submit(function(e){
            e.preventDefault();

            MapSearchAddress();

            return false;
        });

        function MapSearchAddress() {
            GMaps.geocode({
                address: $('.js-map_search_field').val(),
                callback: function(results, status) {
                    if (status == 'OK') {
                        var latlng = results[0].geometry.location;
                        map.setCenter(latlng.lat(), latlng.lng());
                        map.addMarker({
                            lat: latlng.lat(),
                            lng: latlng.lng(),
                            click: function() {
                                MapNewMarker(latlng.lat(), latlng.lng());
                            }
                        });
                    }
                }
            });
        }

        function MapNewMarker(lat, lng) {
            coordinates.lat = lat;
            coordinates.lng = lng;

            fn_openModalId('mod_addPlace');

            type_red = 'add';

            $('.js-btn_add_place').removeClass('is-none');
            $('.js-btn_edit_place').addClass('is-none');
        }

    }

    //////////////
    ///////////////////////////////////////////////////////////////
    // Редагування таблиці
    ///////////////////////////////////////////////////////////////

    //якщо зайшов як адмін
    control = '' +
        '<div class="control_block min right">' +
        '<i class="icon-edit js-mod js-mod_calendarEventEdit js-calendar_event_edit"></i>' +
        '<i class="icon-remove js-mod js-mod_calendarEventDelete js-calendar_event_delete"></i>' +
        '</div>';

    var control_b_open = '<div class="control_block">';
    var control_b_close = '</div>';

    var control_add_column = '<i class="icon-plus js-add_table_column" title="Додати стопчик"></i>';
    var control_add_row =    '<i class="icon-plus js-add_table_row" title="Додати рядок"></i>';
    var control_remove_row =     '<i class="icon-remove js-remove_table_row" title="Видалити поточний рядок"></i>';
    var control_remove_column =  '<i class="icon-remove js-remove_table_column" title="Видалити поточну колонку"></i>';

    var b_table = $('.price_products__table');
    if (b_table.length) editTable();

    //розрахунок в якому магазині ціна на товар більша
    body.on('blur', '.price_products__table td', function(){
        var   container = $(this).parent()
            , container_count_elem
            , price_arr = new Array()
            , index_max_el
            , max_el
            , i = 0;

        if ( $(this).text() == '' ) {
            $(this).find('span').text('0.00')
        }

        container_count_elem = container.find('td').length;

        while (i < container_count_elem) {
            price_arr[i] = container.find('td').eq(i).text();
            price_arr[i] = Number(price_arr[i].replace(/\,/g,"."));
            i++;
        }
        price_arr.shift();
        max_el = Math.max.apply(Math, price_arr);

        index_max_el = price_arr.indexOf(max_el) + 1; //+1 так як довжна масиву змінилась
        $(container).find('td').removeClass('color-red');
        $(container).find('td').eq(index_max_el).addClass('color-red');
    });



    //визначення знаходження натиснутої кнопки "Змінити фото"
    var index_tr;
    body.on('click', '.js-add_photo_product', function(){
        index_tr = $(this).parent().parent().index();
    });

    //копіювання src з модалки в таблицю
    body.on('click', '.js-add_table_img', function() {
        var src = $('#crop_img_box3 .croppedImg').attr('src').split('/')[4];
        $('.price_products__table tbody tr').eq(index_tr).find('img').attr('src', '/lib/img/article_table/' + src);
    });

    //додавання нової колонки до таблиці
    body.on('click', '.js-add_table_column', function() {
        b_table.find('th').last().after('<th><span>Нова колонка</span></th>');
        b_table.find('tbody > tr').append('<td><span>0.0</span></td>');

        editTable();
    });

    //додавання нового рядка до таблиці
    body.on('click', '.js-add_table_row', function() {
        add_table_row('add');
    });

    //функція додавання нового рядка до таблиці
    function add_table_row(type) {
        var count_column = b_table.find('tr:first-child > th').length;
        var td = '<td>' +
                     '<img src="" alt="product-picture" />' +
                     '<span>Продукт</span>' +
                 '</td>';

        while (count_column > 1) {
            td += '<td><span>0.0</span></td>';
            count_column--;
        }

        if (type == 'new') {
            b_table.find('tbody').append('<tr>' + td + '</tr>');
        }

        else if (type == 'add') {
            var row_index = $(this).parent().parent().parent().index();

            b_table.find('tbody > tr').eq(row_index).after('<tr>' + td + '</tr>');
        }

        editTable();
    }

    //видалення рядка з таблиці
    body.on('click', '.js-remove_table_row', function() {
        $(this).parent().parent().parent().remove();

        if ( $('.price_products__table tr').length == 1 ) {
            add_table_row('new');
        }
    });
    // видалення колонки з таблиці
    body.on('click', '.js-remove_table_column', function() {
        var column_index = $(this).parent().parent().index();

        b_table.find('th').eq(column_index).remove();

        $('tbody > tr').each(function(){
            $(this).find('td').eq(column_index).remove();
        });
    });

    //функція додає кнопки редагування в таблицю
    function editTable() {

        $('.price_products__title').attr('contenteditable', 'true');
        $('.price_products__table span').attr('contenteditable', 'true');

        clear_table();

        $('.price_products__title').before(control_b_open + control_add_column + control_b_close);
        $('.price_products__table th').append(control_b_open + control_remove_column + control_b_close);
        $('.price_products__table th').first().find('.control_block').remove();

        $('tbody > tr').each(function(){
            $(this).find('td').first().append(control_b_open + control_add_row + control_b_close);
            $(this).find('td').first().find('img').after('<button class="btn btn--black btn--big js-mod js-mod_addPhoto js-add_photo_product">Змінити картинку</button>');
            $(this).find('td').last().append(control_b_open + control_remove_row + control_b_close);
        });
        $('.price_products__table i').attr('contenteditable', 'false');

        positionControlElem(); //admin.js
    }

    //очищення кнопок редагування з таблиці
    function clear_table() {

        $('.price_products .control_block').remove();
        $('.price_products .js-add_photo_product').remove();
    }


    function clearFullTable() {
        $('.price_products__title').removeAttr('contenteditable');
        $('.price_products__table span').removeAttr('contenteditable');

        $('.price_products .control_block').remove();
        $('.price_products .js-add_photo_product').remove();
    }


    $('.js-save_table').click(function (e) {
        e.preventDefault();

        clearFullTable();

        var id = $(this).attr('data-id');
        var tableContent = $(this).parent().parent().html();

        //console.log(tableContent)


        $.ajax({
            type: 'POST',
            url: 'http://plcb.me/core/index.php/',
            data: {
                what : "editTable",
                id : id,
                table : tableContent
            },
            async: false,
            success: function (data) {
                if (!data) {

                }
                else {

                }
            }
        });

        fn_openModalId('mod_eventTrue');
        editTable();
    });

    body.on('click', '.js-add_table_img', function () {
        var imgUrl = $('#crop_img_box3 .croppedImg').attr('src').split('/');
        imgUrl = imgUrl[3] + '/' + imgUrl[4];

        $.ajax({
            type: 'GET',
            url: 'http://plcb.me/api/set/table_photo',
            data: { tmp_name : imgUrl },
            async: false,
            success: function (data) {
                if (!data) {
                }
                else {

                }
            }
        });
    });

});



function positionControlElem() {
    $('.control_block').each(function (i, elem) {
        var bl = $(this).parent();
        var pos = bl.css('position');

        if (pos == 'absolute') {
            bl.addClass('p-abs');
        }
        else if (pos == 'fixed') {
            bl.addClass('p-fix');
        }
        else {
            bl.addClass('p-rel');
        }
    });
}




function MapEvetCntrl(data, i) {
    text = '<div class="control_block right">' +
              '<i class="icon-edit js-map_marker_edit" data-id="' + data[i].id_places +'"></i>' +
              '<i class="icon-remove js-map_marker_delete" data-id="' + data[i].id_places +'"></i>' +
           '</div>';
    return text;
}