function reporteDimanico(idReporte) {
    let panel_resumen_content = "#panel_resumen_content";
    let report_content = "#report_content";
    let panel_title = "#panel_title";
    let panel_photos_files = "#panel_photos_files";
    let panel_photos = "#panel_photos";
    let panel_resumen = "#panel_resumen";
    let id_reporte = idReporte;

    const classes = {
        0: 'ui-sortable-handle',
        1: 'ui-sortable',
        2: 'ui-resizable',
        3: 'ui-resizable.resizing',
        4: 'ui-sortable-handle-2',
        5: 'ui-sortable-2',
        6: 'ui-resizable-2',
        7: 'ui-resizable.resizing-2'
    };

    function saveJson(conf_reporte) {
        $.ajax({
            data: {
                id_reporte: id_reporte,
                json: JSON.stringify(conf_reporte),
                //json: JSON.stringify(conf_reporte)
            },
            //dataType: 'jsonp',
            url: "./index.php?controller=LlenadosReporte&action=saveConfig",
            method: "POST",
            success: function (response) {
            }
        })
    }

    $.ajax({
        data: {
            id_reporte: id_reporte,
        },
        url: "./index.php?controller=LlenadosReporte&action=getConfig",
        method: "POST",
        success: function (response) {
            try {
                let json = $.parseJSON(response);

                localStorage.setItem('items_conf', JSON.stringify(json));

                /* :::::::::::::::::Asignar Configuracion de la BD toggle de Guardar Configuración::::::::::::::::*/
                let toggle_status = json.toggle;

                if (toggle_status) {
                    $('#toggle').prop('checked', toggle_status[0].status).change();
                }

                /* :::::::::::::::::Asignar Configuracion de la BD al panel de detalles del Reporte::::::::::::::*/
                let items_panel_resumen = json.items_panel_resumen;

                // Si hay un orden guardado entonces recorre y asigna tal orden a los elementos
                if (items_panel_resumen) {
                    $.each(items_panel_resumen, function (index, value) {
                        let $target = $(panel_resumen_content).find(value.id);
                        $target.appendTo($(panel_resumen_content)); // or prependTo

                        $(value.id).width(value.width);
                    });
                }

                /* :::::::::Asignar Configuracion de la BD al DIV padre de Detalles y Fotos y Archivos::::::::::::*/
                let items_report_content = json.items_report_content;

                // Si hay un orden guardado entonces recorre y asigna tal orden a los elementos
                if (items_report_content) {
                    $.each(items_report_content, function (index, value) {
                        let target = $(report_content).find(value.id);
                        target.appendTo($(report_content)); // or prependTo
                    });
                }

                /* ::::::::::::::::::Asignar Configuracion de la BD al panel de Logos y Titulo::::::::::::::::::::*/
                let items_panel_title = json.items_panel_title;
                //let test = getJson();

                // Si hay un orden guardado entonces recorre y asigna tal orden a los elementos
                if (items_panel_title) {
                    $.each(items_panel_title, function (index, value) {
                        let target = $(panel_title).find(value.id);
                        target.appendTo($(panel_title)); // or prependTo

                    });
                }

                /* ::::::::::Asignar Configuracion de la BD al panel de Fotos y Archivos Adjuntos:::::::::::::::::*/
                let items_panel_photos_files = json.items_panel_photos_files;

                // Si hay un orden guardado entonces recorre y asigna tal orden a los elementos
                if (items_panel_photos_files) {
                    $.each(items_panel_photos_files, function (index, value) {
                        let $target = $(panel_photos_files).find(value.id);
                        $target.appendTo($(panel_photos_files)); // or prependTo
                    });
                }

                /* :::::::::::::::::::::::Asignar Configuracion de la BD al panel de Fotos::::::::::::::::::::::::*/
                let items_panel_photos = json.items_panel_photos;

                // Si hay un orden guardado entonces recorre y asigna tal orden a los elementos
                if (items_panel_photos) {
                    $.each(items_panel_photos, function (index, value) {
                        let $target = $(panel_photos).find(value.id);
                        $target.appendTo($(panel_photos));

                        $(value.id).width(value.width);
                        //$(value.id).height(value.height);
                    });
                }
            } catch (e) {
                $(panel_resumen_content).css({'display': 'initial', 'flex-wrap': 'flex'});
            }
        }
    });

    $('#toggle').change(function () {
        let conf_reporte = JSON.parse(localStorage.getItem('items_conf'));

        if (conf_reporte === null) {
            conf_reporte = {
                'toggle': {},
            }
        }


        if ($(this).prop('checked')) {

            const resizable = '.' + classes[2];

            $('.' + classes[6]).addClass(classes[2]);
            $(resizable).removeClass(classes[6]);

            $('.' + classes[4]).addClass(classes[0]);
            $('.' + classes[0]).removeClass(classes[4]);

            $('.' + classes[1]).sortable().sortable('enable');
            $(resizable).resizable().resizable('enable');

            /*::::::::::::::::::::::::Guardado del Orden de los elementos en sus Divs Padres:::::::::::::::::::::::::::.:*/

            //Ordena los elementos y guarda el orden del panel de Detalles del reporte
            $(panel_resumen_content).sortable({
                revert: true,
                opacity: 0.5,
                update: function (event, ui) {
                    let order_resumen = {};

                    let order = JSON.stringify($(this).sortable(
                        "toArray"
                    ));

                    let neworder = JSON.parse(order);

                    $.each(neworder, function (index, value) {
                        let id_act_resumen = "#" + value;
                        let parent_act_resumen = "#" + $(id_act_resumen).parent().attr('id');

                        let width = ($(id_act_resumen).width() / $(parent_act_resumen).width()) * 100;
                        let height = ($(id_act_resumen).height() / $(parent_act_resumen).height()) * 100;

                        order_resumen[index] = {
                            "id": id_act_resumen,
                            "width": parseInt(width.toString()) + "%",
                            "height": parseInt(height.toString()) + "%"
                        }
                    });

                    //localStorage.setItem('items_panel_resumen', JSON.stringify(order_resumen));
                    conf_reporte['items_panel_resumen'] = order_resumen;
                    localStorage.setItem('items_conf', JSON.stringify(conf_reporte));

                    saveJson(conf_reporte);
                }
            });

            // Ordena los elementos y guarda el orden del panel detalles y panel de archivos y fotos
            $(report_content).sortable({
                revert: true,
                opacity: 0.5,
                update: function (event, ui) {
                    let order_content = {};

                    let order = JSON.stringify($(this).sortable(
                        "toArray"
                    ));

                    let neworder = JSON.parse(order);
                    $.each(neworder, function (index, value) {
                        let id_act_content = "#" + value;
                        let parent_act_content = "#" + $(id_act_content).parent().attr('id');


                        let width = ($(id_act_content).width() / $(parent_act_content).width()) * 100;
                        let height = ($(id_act_content).height() / $(parent_act_content).height()) * 100;

                        order_content[index] = {
                            "id": id_act_content,
                            "width": parseInt(width.toString()) + "%",
                            "height": parseInt(height.toString()) + "%"
                        }
                    });

                    //localStorage.setItem('items_report_content', JSON.stringify(order_content));

                    conf_reporte['items_report_content'] = order_content;
                    localStorage.setItem('items_conf', JSON.stringify(conf_reporte));

                    saveJson(conf_reporte);
                }
            });

            // Ordena los elementos y guarda el orden del panel de logos y titulo
            $(panel_title).sortable({
                revert: true,
                opacity: 0.5,
                update: function (event, ui) {
                    let order_title = {};

                    let order = JSON.stringify($(this).sortable(
                        "toArray"
                    ));

                    let neworder = JSON.parse(order);
                    $.each(neworder, function (index, value) {
                        let id_act_title = "#" + value;
                        let parent_act_title = "#" + $(id_act_title).parent().attr('id');

                        let width = ($(id_act_title).width() / $(parent_act_title).width()) * 100;
                        let height = ($(id_act_title).height() / $(parent_act_title).height()) * 100;

                        order_title[index] = {
                            "id": id_act_title,
                            "width": parseInt(width.toString()) + "%",
                            "height": parseInt(height.toString()) + "%"
                        }
                    });
                    //localStorage.setItem('items_panel_title', JSON.stringify(order_title));

                    conf_reporte['items_panel_title'] = order_title;

                    localStorage.setItem('items_conf', JSON.stringify(conf_reporte));
                    saveJson(conf_reporte);
                }
            });

            // Ordena los elementos y guarda el orden del panel de fotos y archivos
            $(panel_photos_files).sortable({
                revert: true,
                opacity: 0.5,
                update: function (event, ui) {
                    let order_photos_files = {};

                    let order = JSON.stringify($(this).sortable(
                        "toArray"
                    ));

                    let neworder = JSON.parse(order);
                    $.each(neworder, function (index, value) {
                        let id_act_photos_files = "#" + value;
                        let parent_act_photos_files = "#" + $(id_act_photos_files).parent().attr('id');

                        let width = ($(id_act_photos_files).width() / $(parent_act_photos_files).width()) * 100;
                        let height = ($(id_act_photos_files).height() / $(parent_act_photos_files).height()) * 100;

                        order_photos_files[index] = {
                            "id": id_act_photos_files,
                            "width": parseInt(width.toString()) + "%",
                            "height": parseInt(height.toString()) + "%"
                        }
                    });

                    //localStorage.setItem('items_panel_photos_files', JSON.stringify(order_photos_files));

                    conf_reporte['items_panel_photos_files'] = order_photos_files;

                    localStorage.setItem('items_conf', JSON.stringify(conf_reporte));
                    saveJson(conf_reporte);
                }
            });

            $(panel_photos).sortable({
                revert: true,
                opacity: 0.5,
                update: function (event, ui) {
                    let order_photos = {};

                    let order = JSON.stringify($(this).sortable(
                        "toArray"
                    ));

                    let neworder = JSON.parse(order);
                    $.each(neworder, function (index, value) {
                        let id_act_photos = "#" + value;
                        let parent_act_photos = "#" + $(id_act_photos).parent().attr('id');

                        let width = ($(id_act_photos).width() / $(parent_act_photos).width()) * 100;
                        let height = ($(id_act_photos).height() / $(parent_act_photos).height()) * 100;

                        order_photos[index] = {
                            "id": id_act_photos,
                            "width": parseInt(width.toString()) + "%",
                            "height": parseInt(height.toString()) + "%"
                        }
                    });
                    //localStorage.setItem('items_panel_photos', JSON.stringify(order_photos));

                    conf_reporte['items_panel_photos'] = order_photos;

                    localStorage.setItem('items_conf', JSON.stringify(conf_reporte));
                    saveJson(conf_reporte);
                }
            });

            for (let child of $("#paneles").children()) {
                let id_element = $(child).attr('id');
                let type_element = $(child).attr('type');
                if (id_element !== undefined && type_element !== 'hidden') {

                    for (let child_2 of $("#" + id_element).children()) {
                        let id_element_2 = $(child_2).attr('id');
                        let type_element_2 = $(child_2).attr('type');

                        if (id_element_2 !== undefined && (type_element_2 !== 'hidden' || type_element_2 !== undefined)) {
                            let id_elemento_act = "#" + id_element_2;

                            for (let child_3 of $("#" + id_element_2).children()) {
                                let id_element_3 = $(child_3).attr('id');
                                let type_element_3 = $(child_3).attr('type');
                                if (id_element_3 !== undefined && (type_element_3 !== 'hidden' || type_element_3 !== undefined)) {
                                    for (let child_4 of $("#" + id_element_3).children()) {
                                        let id_element_4 = $(child_4).attr('id');
                                        let type_element_4 = $(child_4).attr('type');
                                        if (id_element_4 !== undefined && (type_element_4 !== 'hidden' || type_element_4 !== undefined)) {
                                            $("#" + id_element_4).resizable({
                                                animateEasing: "easeOutBounce",
                                                autoHide: true,
                                                containment: "parent",
                                                grid: [10, 10],
                                                //grid: [20, 10],
                                                handles: "e, w",
                                                stop: function (e, ui) {
                                                    let parent = "#" + $(this).parent().attr('id');
                                                    let elements = {};
                                                    $.each($(parent).children(), function (index, value) {

                                                        let id_actual = "#" + $(this).attr('id');

                                                        let width = ($(id_actual).width() / $(parent).width()) * 100;
                                                        let height = ($(id_actual).height() / $(parent).height()) * 100;

                                                        console.log(width);
                                                        $(id_actual).width(parseInt(width.toString()) + "%");

                                                        elements[index] = {
                                                            "id": id_actual,
                                                            "width": parseInt(width.toString()) + "%",
                                                            //"height": parseInt(height.toString()) + "%",
                                                            "height": parseInt($(id_actual).height()) + "px"
                                                        }
                                                    });

                                                    switch (parent) {
                                                        case '#panel_photos':
                                                            conf_reporte['items_panel_photos'] = elements;
                                                            saveJson(conf_reporte);
                                                            break;
                                                        case '#panel_resumen_content':
                                                            conf_reporte['items_panel_resumen'] = elements;
                                                            saveJson(conf_reporte);
                                                            break;
                                                        case 'panel_photos_files':
                                                            break;
                                                        case 'report_content':
                                                            break;

                                                    }

                                                }
                                            });
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            conf_reporte['toggle'] = {
                '0': {
                    'status': true
                }
            };
            saveJson(conf_reporte);
        } else {
            $('.ui-sortable').sortable().sortable('disable');
            $('.ui-resizable').resizable().resizable('disable');

            $('.' + classes[2]).addClass(classes[6]);
            $('.' + classes[6]).removeClass(classes[2]);

            $('.' + classes[0]).addClass(classes[4]);
            $('.' + classes[4]).removeClass(classes[0]);

            conf_reporte['toggle'] = {
                '0': {
                    'status': false
                }
            };
            saveJson(conf_reporte);
        }
    })
}
