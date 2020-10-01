$(document).ready(function () {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == 'controller') {
            var controlador = pair[1];
            //var accion = pair[2];
        }
        if (pair[0] == 'action') {
            var accion = pair[1];
        }
    }
    //document.write(controlador)

    switch (controlador) {
        case 'SeguimientosReporte':
            responsive: true,
                $('#example').DataTable({
                    "rowCallback": function (row, data, index) {
                        if (index % 2 == 0) {
                            $(row).removeClass('myodd myeven');
                            $(row).addClass('myodd');
                        } else {
                            $(row).removeClass('myodd myeven');
                            $(row).addClass('myeven');
                        }
                    },
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                    },
                    "ordering": false,
                    "pageLength": 100,
                    //rowReorder: true
                });
            break;
        case 'CamposReporte':
        case 'Empresas_Proyectos':
        case 'Perfiles':
            $('#example').DataTable({
                responsive: true,
                "rowCallback": function (row, data, index) {
                    if (index % 2 == 0) {
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myodd');
                    } else {
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myeven');
                    }
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                "ordering": false,
                "pageLength": 25,
                rowReorder: true
            });
            break;
        case 'Usuarios':
        case 'MatrizComunicacion':
            $('#example').DataTable({
                responsive: true,
                "rowCallback": function (row, data, index) {
                    if (index % 2 == 0) {
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myodd');
                    } else {
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myeven');
                    }
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                //"order": [1, 'asc'],
                "ordering": false,
                "pageLength": 25
            });

            $('#example1').DataTable({
                responsive: true,
                "rowCallback": function (row, data, index) {
                    if (index % 2 == 0) {
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myodd');
                    } else {
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myeven');
                    }
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                //"order": [1, 'asc'],
                "ordering": false,
                "pageLength": 25
            });

            break;
        case 'ReportesLlenados':
            $('#seguimientos').DataTable({
                responsive: true,
                "rowCallback": function (row, data, index) {
                    if (index % 2 == 0) {
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myodd');
                    } else {
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myeven');
                    }
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                //"order": [1, 'asc'],
                "ordering": false,
                "pageLength": 25
            });

            $('#vinculados').DataTable({
                responsive: true,
                "rowCallback": function (row, data, index) {
                    if (index % 2 == 0) {
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myodd');
                    } else {
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myeven');
                    }
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                //"order": [1, 'asc'],
                "ordering": false,
                "pageLength": 25
            });
            break;
        default:
            $('#example').dataTable({
                select: false,
                responsive: true,
                "rowCallback": function (row, data, index) {
                    if (index % 2 == 0) {
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myodd');
                    } else {
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myeven');
                    }
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                "ordering": false,
                "pageLength": 50
            });
    }


});
