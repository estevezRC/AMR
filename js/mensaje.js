function mensajes(insercion, newElemento) {
    if (insercion == 1) {
        //alertify.alert('Nuevo Registro', '' + newElemento);
        alertify.success(newElemento, 6);
    }
    if (insercion == 2) {
        //alertify.alert('Registro Repetido', '' + newElemento);
        alertify.error(newElemento, 6);
    }
    if (insercion == 3) {
        //alertify.alert('Registro Modificado', '' + newElemento);
        alertify.success(newElemento, 6);
    }
    if (insercion == 4) {
        alertify.success(newElemento);
    }
    if (insercion == 5) {
        alertify.error(newElemento);
    }
}

function borrarRegistro(id, idElemento, nombreElemento, controller, action) {
    alertify.confirm('Borrar Registro', '' + 'Seguro que desea borrar el registro: ' + nombreElemento, function () {
            document.location.href = 'index.php?controller=' + controller + '&action=' + action + '&' + idElemento + '=' + id;
        },
        function () {
            console.log('Registro no borrado');
        }).set({labels: {ok: 'Aceptar', cancel: 'Cancelar'}, padding: false});

}

function borrarRegistroAjax(id, idElemento, nombreElemento, controller, action) {
    alertify.confirm('Borrar Registro', '' + 'Seguro que desea borrar el registro: ' + nombreElemento, function () {
            $.ajax({
                url: `index.php?controller=${controller}&action=${action}&${idElemento}=${id}`,
                method: "POST",
                success: function (response) {
                    console.log(response);
                    let respuestaJSON = $.parseJSON(response);

                    let mensaje = respuestaJSON.mensaje, status = respuestaJSON.estado, ruta = respuestaJSON.ruta;

                    if (status)
                        alertify.success(mensaje);
                    else
                        alertify.error(mensaje);

                    setTimeout(function () {
                        document.location.href = ruta;
                    }, 2000);
                }
            });
        },
        function () {
            console.log('Registro no borrado');
        }).set({labels: {ok: 'Aceptar', cancel: 'Cancelar'}, padding: false});
}


function restaurarAjax(id, idElemento, nombreElemento, controller, action) {
    alertify.confirm('Restaurar Registro', '' + 'Seguro que desea restaurar el registro: ' + nombreElemento, function () {
            $.ajax({
                url: `index.php?controller=${controller}&action=${action}&${idElemento}=${id}`,
                method: "POST",
                success: function (response) {
                    let respuestaJSON = $.parseJSON(response);
                    let mensaje = respuestaJSON.mensaje, status = respuestaJSON.estado, ruta = respuestaJSON.ruta;

                    if (status)
                        alertify.success(mensaje);
                    else
                        alertify.error(mensaje);

                    setTimeout(function () {
                        document.location.href = ruta;
                    }, 2000);
                }
            });
        },
        function () {
            console.log('Registro no borrado');
        }).set({labels: {ok: 'Aceptar', cancel: 'Cancelar'}, padding: false});
}


function enviarCorreo(id, idElemento, nombreElemento, controller, action) {
    alertify.confirm('Enviar correo de bienvenida', '' + 'Seguro que desea enviarle correo al usuario: ' + nombreElemento, function () {
            $.ajax({
                url: `index.php?controller=${controller}&action=${action}&${idElemento}=${id}`,
                method: "POST",
                success: function (response) {
                    let respuestaJSON = $.parseJSON(response);
                    let mensaje = respuestaJSON.mensaje, status = respuestaJSON.estado, ruta = respuestaJSON.ruta;

                    if (status)
                        alertify.success(mensaje);
                    else
                        alertify.error(mensaje);

                    setTimeout(function () {
                        document.location.href = ruta;
                    }, 2000);
                }
            });
        },
        function () {
            console.log('Correo no enviado');
        }).set({labels: {ok: 'Aceptar', cancel: 'Cancelar'}, padding: false});
}
