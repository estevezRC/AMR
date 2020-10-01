function dashBoard(reportesUsuarios, reportesUtilizados) {

    am4core.ready(function () {
// Themes begin
        am4core.useTheme(am4themes_dataviz);
        am4core.useTheme(am4themes_animated);
// Themes end

        // Create chart instance
        var chart = am4core.create("reportes_usuarios", am4charts.XYChart);
        var title = chart.titles.create();
        title.text = "Reportes Por Usuario";
        title.fontSize = 20;
        title.marginBottom = 20;

        chart.data = reportesUsuarios;

// Create axes
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "user";
        categoryAxis.renderer.grid.template.disabled = true;
        categoryAxis.renderer.labels.template.horizontalCenter = "right";
        categoryAxis.renderer.labels.template.verticalCenter = "middle";
        categoryAxis.renderer.labels.template.rotation = -45;
        categoryAxis.tooltip.disabled = true;
        categoryAxis.renderer.minHeight = 110;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.minWidth = 50;
        valueAxis.renderer.grid.template.disabled = false;
        valueAxis.min = 0;

// Create series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.sequencedInterpolation = true;
        series.dataFields.valueY = "reportes";
        series.dataFields.categoryX = "user";
        series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
        series.columns.template.strokeWidth = 0;

        series.tooltip.pointerOrientation = "vertical";

        series.columns.template.column.cornerRadiusTopLeft = 10;
        series.columns.template.column.cornerRadiusTopRight = 10;
        series.columns.template.column.fillOpacity = 0.8;

// on hover, make corner radiuses bigger
        var hoverState = series.columns.template.column.states.create("hover");
        hoverState.properties.cornerRadiusTopLeft = 0;
        hoverState.properties.cornerRadiusTopRight = 0;
        hoverState.properties.fillOpacity = 1;

        series.columns.template.adapter.add("fill", function (fill, target) {
            return chart.colors.getIndex(target.dataItem.index);
        });
// Cursor
        chart.cursor = new am4charts.XYCursor();

// Create chart instance
        var chart = am4core.create("reportes_utilizados", am4charts.XYChart);
        chart.scrollbarX = new am4core.Scrollbar();
        var title = chart.titles.create();
        title.text = "Reportes Mas Utilizados";
        title.fontSize = 20;
        title.marginBottom = 20;

        chart.data = reportesUtilizados;
// Create axes
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "nombre";
        categoryAxis.renderer.grid.template.disabled = true;
        categoryAxis.renderer.labels.template.horizontalCenter = "right";
        categoryAxis.renderer.labels.template.verticalCenter = "middle";
        categoryAxis.renderer.labels.template.rotation = -45;
        var label = categoryAxis.renderer.labels.template;
        label.truncate = true;
        label.maxWidth = 220;
        label.ellipsis = '...';
        categoryAxis.tooltip.disabled = true;
        categoryAxis.renderer.minHeight = 110;
        categoryAxis.renderer.minGridDistance = 10;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.minWidth = 50;
        valueAxis.renderer.grid.template.disabled = false;
        valueAxis.min = 0;
// Create series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.sequencedInterpolation = true;
        series.dataFields.valueY = "cantidad";
        series.dataFields.categoryX = "nombre";
        series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
        series.columns.template.strokeWidth = 0;

        series.tooltip.pointerOrientation = "vertical";

        series.columns.template.column.cornerRadiusTopLeft = 10;
        series.columns.template.column.cornerRadiusTopRight = 10;
        series.columns.template.column.fillOpacity = 0.8;

// on hover, make corner radiuses bigger
        var hoverState = series.columns.template.column.states.create("hover");
        hoverState.properties.cornerRadiusTopLeft = 0;
        hoverState.properties.cornerRadiusTopRight = 0;
        hoverState.properties.fillOpacity = 1;

        series.columns.template.adapter.add("fill", function (fill, target) {
            return chart.colors.getIndex(target.dataItem.index);
        });
// Cursor
        chart.cursor = new am4charts.XYCursor();
    });

}

function pastel(datos) {
    am4core.ready(function () {
        <!-- Chart code -->
        // Themes begin
        am4core.useTheme(am4themes_dataviz);
        am4core.useTheme(am4themes_animated);
        // Themes end

        var chart = am4core.create("pastel_graph", am4charts.PieChart3D);
        chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

        chart.legend = new am4charts.Legend();

        chart.data = datos;

        var series = chart.series.push(new am4charts.PieSeries3D());
        //series.slices.template.tooltipText = "";
        series.labels.template.disabled = true;
        series.ticks.template.disabled = true;
        series.dataFields.value = "porcentaje";
        series.dataFields.category = "elemento";
    });
}
