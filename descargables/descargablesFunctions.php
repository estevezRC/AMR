<?php
class descargablesFunctions extends ControladorBase
{
    /*****************************************************************************************
     *                          FUNCIONES NATIVAS CREACIÓN EXCEL
     ****************************************************************************************/

//:::::::::::::::::::::: AÑADIR TEXTO A LAS CELDAS ::::::::::::::::::::::::::::::
    function addText(string $celda, string $content, $hoja)
    {
        $hoja->getCell($celda)->setValue($content);
    }

//:::::::::::::::::::::: COLOR TEXTO A LAS CELDAS ::::::::::::::::::::::::::::::
    function setCellColor(string $celda, string $color, $hoja)
    {
        $hoja->getStyle($celda)->getFill()->setFillType(Fill::FILL_SOLID);
        $hoja->getStyle($celda)->getFill()->getStartColor()->setARGB($color);
    }

//:::::::::::::::::::::::: COMBINAR LAS CELDAS :::::::::::::::::::::::::::::::::
    function mergeRange(string $rango, $hoja)
    {
        $hoja->mergeCells($rango);
    }

//:::::::::::::::::::::::: ALINEAR CONTENIDO CELDAS :::::::::::::::::::::::::::::
    function alignCellContent(string $celda, string $posicionamientoX = 'CENTER', string $posicionamientoY = 'CENTER', $hoja, $alignment)
    {
        $posicionamientoY = strtoupper($posicionamientoY);
        $posicionamientoX = strtoupper($posicionamientoX);
        $config = [
            'wrapText' => true
        ];
        eval("\$config['horizontal'] = \$alignment::HORIZONTAL_$posicionamientoX;");
        eval("\$config['vertical'] = \$alignment::VERTICAL_$posicionamientoY;");

        $hoja->getStyle($celda)->applyFromArray(['alignment' => $config]);
    }

    function addImageCell(string $src, string $name, string $cell, int $width, int $height, $hoja)
    {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName($name);
        $drawing->setDescription($name);
        $drawing->setPath($src);
        //$drawing->setWidth($size);
        $drawing->setWidthAndHeight($width, $height);
        $drawing->setResizeProportional(true);
        $drawing->setCoordinates($cell);
        $drawing->setOffsetX(0);
        $drawing->setOffsetY(10);
        $drawing->setWorksheet($hoja);
    }

    function addImageCellEncabezado(string $src, string $name, string $cell, int $width, int $height, $hoja)
    {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName($name);
        $drawing->setDescription($name);
        $drawing->setPath($src);
        //$drawing->setWidth($size);
        $drawing->setWidthAndHeight($width, $height);
        $drawing->setResizeProportional(true);
        $drawing->setCoordinates($cell);
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);
        $drawing->setWorksheet($hoja);
    }

    /*****************************************************************************************
     *                        FIN FUNCIONES NATIVAS CREACIÓN EXCEL
     ****************************************************************************************/
}


?>