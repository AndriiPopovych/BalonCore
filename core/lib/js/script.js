var site = getSite(),
    array = new Array();
$(document).ready(function () {


    $(".js-okeyreload").click(function () {
        location.reload();
    });

    /*   Видалення елементу   */

    $(".remove").click(function () {
        var th = $(this);
        var id = th.data('id'),
            table = th.data('table'),
            what = th.data('what'),
            data = th.data('data');

        array = {
            "id": id,
            "table": table,
            "what": what,
            "data": data
        };

        sendWhat('remove');

        function fail(data) {
            alert(data);
            //fn_openModalId("mod_event_true");
            if (table == "part_photoshots") {
                th.parent().parent().remove();
                $(".ph_article_control--prev-slide").click();
                fn_display_noneId('mod_preload');
            }
            else {
                location.reload();
            }
            /*if (data == "okey") {
                th.slideDown();
                th.parent().parent().remove();
            }*/
        }

        function success(data) {
            fn_openModalId("mod_event_false")
        }

        sendPost(array, success, fail);
    });


    /*   Додавання елементу   */

    $(".add").click(function () {
        var table = $(this).data('table');

        if ($(this).data('data')) {
            var data = $(this).data('data');
        }
        else {
            var data = 0;
        }

        array = {
            "table": table,
            "data": data
        };

        sendWhat('add');
        function fail(data) {
            fn_openModalId("mod_event_false");
        }

        function success(data) {
            //fn_openModalId("mod_event_true");
            location.reload();
        }

        sendPost(array, success, fail);
    });

    /*   Додавання елементу   */

    $(".visibility").click(function () {
        var table = $(this).data('table'),
            id = $(this).data('id'),
            visibility = $(this).data('visibility'),
            what = $(this).data('what');
        if (visibility == 0) visibility = 1;
        else visibility = 0;
        array = {
            "table": table,
            "id": id,
            "visibility": visibility,
            "what": what
        };
        sendWhat('edit_visibility');
        function fail(data) {
            fn_openModalId("mod_event_false");
        }

        function success(data) {
            //fn_openModalId("mod_event_true");
            location.reload();
        }

        sendPost(array, success, fail);
    });


    /*   Виклик вікна редагування елементу   */

    $(".icon-edit").click(function () {
        fn_openModalId('mod_preload');  //виклик анімації завантаження вікна

        var table = $(this).data('table'),
            id = $(this).data('id'),
            what = $(this).data('what'),
            href = location.href;
        sendWhat('get_edit_html', id);

        array = {
            "table": table,
            "id": id,
            "what": what,
            returnHref: href
        };

        function success(data) {
            fn_openModalId("mod_event_false");
        }

        function fail(data) {
            fn_openModalId('mod_edit');
            alert(data);
            $(".edit_form").html("<input type='hidden' name='what' value='" + what + "'>" + data);
            fn_modal_center('#mod_edit');

            var textarea_text = $("#editor").text();

            $("#editor").html(textarea_text);

            sendWhat('edit');
        }

        sendPost(array, success, fail);
    });


    $(".position").click(function () {
        var table = $(this).data('table'),
            newId = $(this).data('new'),
            what = $(this).data('what'),
            id = $(this).data('id');
        array = {
            "table": table,
            "new": newId,
            "id": id,
            "what":what
        };
        sendWhat('position');
        function fail(data) {
            alert (data);
            fn_openModalId("mod_event_false");
        }

        function success(data) {
            //fn_openModalId("mod_event_true");
            location.reload();
        }

        sendPost(array, success, fail);
    });

    $(".set_admin").click(function () {
        var role = $(this).data('role'),
            id = $(this).data('id');
        $.get(
            site + '/api/set/admin',
            {
                id: id,
                role: role
            }, function (data) {
                if (data) {
                    //alert (data);
                    $(this).parent().find(".set_admin").remove();
                    $(this).parent().html("Адмін");
                }
                location.reload();
                fn_display_noneId('mod_preload');
            });
    });

    //блокуємо відправку всіх форм, і відправляємо їх в фоновому режимі

    /*$(document).on('click', '.ok', function(e){
     e.preventDefault();
     if ( $('#editor').length ) {
     //$('#edit_form').find('[name="text_article"]').text(  );
     var htmldata = $("#editor").htmlcode()
     $("#editor").val(htmldata);
     }
     alert($('#edit_form').find('[name="text_article"]').text());
     alert($('.edit_form').serialize());
     $('#edit_form').submit();
     });
     */


    $('.js-control-form label').click(function () {
        $("#" + $(this).attr('for')).click();
        $(this).parent().find('.js-article_control').click();
    });

    //.is-none замінив
    $(".js-article_control").click(function () {
        fn_openModalId('mod_preload');
        var array = $(this).parent().serializeArray();
        //console.log(array);
        $.post(
            "http://plcb.me/core/index.php",
            {
                post: array
            },
            function (data) {
                fn_display_noneId("mod_preload");
                //fn_openModalId("mod_event_true");
                location.reload();
            }
        );
        return false;
    });

    $(".article_social__item").click(function () {
        $(this).find("img").click();
    })

    $(document).on('keypress', 'body', function (e) {
        if (e.keyCode == 13) {
            $('.js-okeyreload').click();
        }
    });

});


/**
 ***function sendPost***
 Вхідні параметри:
 @array - масив значень, які бууть передаватись
 success() - функція, яка буде викликатись у разі вдалого виконання запросу
 (вдалий запрос - коли не прийшла помилка,окрім випадку,
 коли повертається html)
 fail() - функція, яка буде викликатись у разі не вдалого виконання запросу
 Функція відправляє запрос на admin/index.php, який в залежності від
 значення $_SESSION['what'] (яке устанавлюється за допомогою функції sendWhat)
 буде викликати певну функцію з admin/cmp_system/Admin/Admin.ph

 */


function sendPost(array, success, fail) {
    fn_openModalId('mod_preload');  //виклик анімації завантаження вікна
    $.post(
        site + '/core/index.php',
        {
            array: array
        }, function (data) {
            //fn_openModalId('mod_edit');
            fn_display_noneId('mod_preload');
            if (data) {
                fail(data);
            }
            else {
                success(data);
            }
        });
};


/*
 ***function sendWhat***
 Вхідні параметри:
 what - яка дія далі буде виконуватись. Ця дія має існувати в файлі-медіаторі admin/index.php
 id - id елемента.
 Функція відправляє запрос на admin/lib/php/system.php
 Де змінній $_SESSION['what'] присвоюється значення вхідного параметру @what
 Коли потрібно змінній $_SESSION['id'] присвоюэться значення вхідного параметру @id

 */

function sendWhat(what, id) {
    $.post(site + '/core/lib/php/system.php',
        {
            what: what,
            id: id
        },
        function a(data) {
            //alert (data);
        }
    );
    return true;
};


/**

 ***function getSite()***
 Функція повертає ім’я сайту. В разі якщо сайт знаходиться на локальному сервері -
 дописує "localhost"

 */

function getSite() {

    var a = new String(window.location);
    var str = [];
    str = a.split('/');
    if (str[2] == 'localhost') {
        str = "http://" + str[2] + "/" + str[3] + "/";
    }
    else {
        str = "http://" + str[2] + "/";
    }
    return str;
}




function fn_display_noneId(nameModalId) {
    var el = '#' + nameModalId;

    $(el).css('display', 'none');
}

//ширина скролла
//створюємо елемент з прокруткою
function fn_scroll () {
    var div = document.createElement('div');

    div.style.overflowY = 'scroll';
    div.style.width =  '50px';
    div.style.height = '50px';

    // при display:none размеры нельзя узнать
    // нужно, чтобы элемент был видим,
    // visibility:hidden - можно, т.к. сохраняет геометрию
    div.style.visibility = 'hidden';

    document.body.appendChild(div);
    var scrollWidth = div.offsetWidth - div.clientWidth;
    document.body.removeChild(div);
    return scrollWidth;
}


//вирівнювання модального вікна по вертикалі
function fn_modal_center(name) {
    var elem = $(name);
    var elem_height = elem.outerHeight(),
        window_height = $(window).height(),
        document_height = $(document).height(),
        pos_scroll = document.body.scrollTop,
        elem_top;

    //якщо висота елемнта більша за вікно браузера
    //20 - це для красоти, щоб завжди були відступи
    if ( elem_height < window_height - 20 ) {
        elem_top = (window_height - elem_height) / 2;
        elem.css('margin', elem_top + 'px auto 0');
    }
    else {
        elem.css('margin', '10px auto 0');
    }
}

//відкриття модального вікна, пошук назви вікна "js_mod_" + name
//відкриває віно з id = "mod_" + name
function fn_openModal(elem) {
    var class_list = $(elem).attr('class').split(/\s+/);

    for (var i = 0; i < class_list.length; i++) {
        var reg_search = class_list[i].search(/js-mod_\w+/),
            reg_match = class_list[i].match(/js-mod_\w+/);
        if ( !reg_search ) {
            var name_window = new String(reg_match).split('_');
            var el = 'mod_' + name_window[1];

            fn_openModalId(el);
        }
    }
}



/*	непогано б допілить
 $(window).scroll(function(){
 $('.modal_window').css('top', document.body.scrollTop);
 })
 */
function fn_openModalId(name_modalId) {
    var el = '#' + name_modalId;

    $('.wrap').addClass('is-blur');
    fn_modal_center(el);
    var w_scroll = fn_scroll();
    $('body').css({
        'overflow': 'hidden',
        'margin-right': w_scroll
    });
    $(el).css('display','table');
    $(el).parent().addClass('visible');
    $(el).addClass('visible');

    if ( $(el).hasClass('is-blocked') ) {
        blocked = true;
    }
}

function closeModal(_this) {
    $('.wrap').removeClass('is-blur');
    _this.parent()
        .removeClass('visible')
        .css('display', 'none');
    _this.parent()
        .parent()
        .removeClass('visible');
    $('body').css({
        'overflow': 'auto',
        'margin-right': 0
    });
}
function closeModalId(id) {
    $('.wrap').removeClass('is-blur');
    $('#' + id)
        .removeClass('visible')
        .css('display', 'none');
    $('#' + id)
        .parent()
        .removeClass('visible');
    $('body').css({
        'overflow': 'auto',
        'margin-right': 0
    });
}
function closeModalOk(_this) {
    $('.wrap').removeClass('is-blur');
    _this
        .parent()
        .parent()
        .removeClass('visible')
        .css('display', 'none');
    _this
        .parent()
        .parent()
        .parent()
        .removeClass('visible');
    $('body').css({
        'overflow': 'auto',
        'margin-right': 0
    });
}














