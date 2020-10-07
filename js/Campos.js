$(document).ready(function () {
    var MaxInputs = 200; //maximum input boxes allowed
    var contenedor = $("#contenedor"); //Input boxes wrapper ID
    var AddButton = $("#agregarCampo"); //Add button ID
    //var x = contenedor.length; //initlal text box count
    var x = $("#contenedor div").length + 1;
    var FieldCount = x - 1; //to keep track of text box added
    $(AddButton).click(function (e)  //on add input button click
    {
        if (x <= MaxInputs) //max input box allowed
        {
            FieldCount++; //text box added increment
            //add input box
            $(contenedor).append("<table> <tr> <td style='width: 98%'> <input type='text' style='margin-top: .5em;' name='mitexto_" + FieldCount + "' id='campo_" + FieldCount + "' placeholder='Ingresar valor' class='form-control'>" +
                "</td> <td> <a href='#' class='eliminar' style='font-size: 18px; margin-top: .5em;'><i class='fa fa-trash-o' aria-hidden='true'></i></a>" +
                "</td> </tr> </table>");
            $('#conteo_default').val(FieldCount);
            x++; //text box increment
        }
        return false;
    });

    $("body").on("click", ".eliminar", function (e) { //user click on remove text
        if (x > 1) {
            //$(this).parent('td tr table div').remove();
            $(this).parent().parent().parent().parent().remove();
            x--; //decrement textbox
            FieldCount--;
        }
        return false;
    });


    valorSelect = document.getElementById("reactivo_campo").value;
    switch (valorSelect) {
        case "radio":
            $("#cantidad").attr("hidden", true);
            $('#valores').removeAttr("hidden");
            break;
        case "checkbox":
            $("#cantidad").attr("hidden", true);
            $('#valores').removeAttr("hidden");
            break;
        case "select":
            $("#cantidad").attr("hidden", true);
            $('#valores').removeAttr("hidden");
            break;
        case "file":
            $("#valores").attr("hidden", true);
            $('#cantidad').removeAttr("hidden");
            break;
        default:
            $("#cantidad").attr("hidden", true);
            $("#valores").attr("hidden", true);
    }


    valorSelectMod = document.getElementById("reactivo_campoMod").value;
    switch (valorSelectMod) {
        case "radio":
            $("#cantidadMod").attr("hidden", true);
            $('#valoresMod').removeAttr("hidden");
            break;
        case "checkbox":
            $("#cantidadMod").attr("hidden", true);
            $('#valoresMod').removeAttr("hidden");
            break;
        case "select":
            $("#cantidadMod").attr("hidden", true);
            $('#valoresMod').removeAttr("hidden");
            break;
        case "file":
            $("#valoresMod").attr("hidden", true);
            $('#cantidadMod').removeAttr("hidden");
            break;
        default:
            $("#cantidadMod").attr("hidden", true);
            $("#valoresMod").attr("hidden", true);
    }

});

// /*
function val(id) {

    var reactivo_campo1 = document.getElementById("reactivo_campo").value;
    if (reactivo_campo1 == 'NULL')
        $("#btnGuardar").attr("disabled", true);
    else
        $('#btnGuardar').removeAttr("disabled");

    //valorSelect = document.getElementById('reactivo_campoMod').value;
    switch (reactivo_campo1) {
        case "radio":
            $("#cantidad").attr("hidden", true);
            $('#valores').removeAttr("hidden");
            break;
        case "checkbox":
            $("#cantidad").attr("hidden", true);
            $('#valores').removeAttr("hidden");
            break;
        case "select":
            $("#cantidad").attr("hidden", true);
            $('#valores').removeAttr("hidden");
            break;
        case "file":
            $("#valores").attr("hidden", true);
            $('#cantidad').removeAttr("hidden");
            break;
        default:
            $("#cantidad").attr("hidden", true);
            $("#valores").attr("hidden", true);

    }

    valorSelectMod = document.getElementById("reactivo_campoMod").value;
    switch (valorSelectMod) {
        case "radio":
            $("#cantidadMod").attr("hidden", true);
            $('#valoresMod').removeAttr("hidden");
            break;
        case "checkbox":
            $("#cantidadMod").attr("hidden", true);
            $('#valoresMod').removeAttr("hidden");
            break;
        case "select":
            $("#cantidadMod").attr("hidden", true);
            $('#valoresMod').removeAttr("hidden");
            break;
        case "file":
            $("#valoresMod").attr("hidden", true);
            $('#cantidadMod').removeAttr("hidden");
            break;
        default:
            $("#cantidadMod").attr("hidden", true);
            $("#valoresMod").attr("hidden", true);
    }
}

//*/

function Nombre_Campo(tipo_Reactivo) {
    let nombre_reactivo = '';
    switch (tipo_Reactivo) {
        case "text":
            nombre_reactivo = "Texto corto";
            break;
        case "textarea":
            nombre_reactivo = "Texto largo";
            break;
        case "radio":
            nombre_reactivo = "Una opción";
            break;
        case "checkbox":
            nombre_reactivo = "Check list";
            break;
        case "number":
            nombre_reactivo = "Número";
            break;
        case "date":
            nombre_reactivo = "Fecha";
            break;
        case "time":
            nombre_reactivo = "Hora";
            break;
        case "select":
            nombre_reactivo = "Menú";
            break;
        case "file":
            nombre_reactivo = "Imagen";
            break;
        case "label":
            nombre_reactivo = "Etiqueta";
            break;
        case "checkbox-incidencia":
            nombre_reactivo = "Incidencia";
            break;
        case "check_list_asistencia":
            nombre_reactivo = "General(Tabla) Check list";
            break;
        case "text-cadenamiento":
            nombre_reactivo = "Cadenamiento";
            break;
        case "rango_fechas":
            nombre_reactivo = "Rango de Fechas";
            break;
        case "select-tabla":
            nombre_reactivo = "General(Tabla) Menú";
            break;
        case "decimal":
            nombre_reactivo = "Decimal";
            break;
    }
    return nombre_reactivo;
}
