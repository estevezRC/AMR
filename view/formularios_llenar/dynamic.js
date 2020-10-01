$(document).ready(function () {
    $.ajax({
        data: {
            id_Gpo_Valores_Reporte: 373,
            Id_Reporte: 1,
            tipo_Reporte: 0
        },
        url: "https://creando.uno/reportech/index.php?controller=LlenadosReporte&action=modificarreporte&id_Gpo_Valores_Reporte=373&Id_Reporte=1&tipo_Reporte=0",
        // &id_Gpo_Valores_Reporte=373&Id_Reporte=1&tipo_Reporte=0
        type: 'GET',
        success: function (response) {
            //let result = $.parseJSON(response);
            console.log(response);
            //console.error(this.props.url, status, err.toString());
        }
    });
});
