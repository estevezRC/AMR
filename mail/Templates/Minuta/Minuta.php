<?php

namespace Mail\Templates\Minuta;

use Mail\Templates\TemplateInterface;

class Minuta implements TemplateInterface
{
    private string $template;
    private array $datos;
    private string $urlContent = __DIR__ . "/template.final.html";
    private ?string $urlLogo;
    private array $arrayInfoFinal;

    function __construct(array $data, $urlLogo = null)
    {
        $this->datos = $data;
        $this->urlLogo = $urlLogo;
    }

    private function replaceDataOnTemplate(): void
    {
        $this->crearInformacionFinal();
        foreach ($this->arrayInfoFinal as $key => $dato) {
            $this->template = str_replace("{{$key}}", $dato, $this->template);
        }
    }

    private function insertarDatosGenerales(): void
    {
        if ($this->urlLogo)
            $this->arrayInfoFinal['logo'] = $this->urlLogo;

        $this->arrayInfoFinal['fecha'] = date('d-m-Y', strtotime($this->datos[0]['valor']));
        $this->arrayInfoFinal['hora'] = $this->datos[1]['valor'];
        $this->arrayInfoFinal['lugar'] = $this->datos[2]['valor'];
        $this->arrayInfoFinal['introduccion'] = $this->datos[3]['valor'];
        $this->arrayInfoFinal['desarrollo'] = $this->datos[5]['valor'];
        $this->arrayInfoFinal['motivo'] = $this->datos[7]['valor'];
    }

    private function insertarDatosParticipantes(): void
    {
        foreach ($this->datos[4]['valor'] as $participante) {
            $this->arrayInfoFinal['participantes'] .= <<< EOF
                <li class="mb-2" style="box-sizing: border-box; border-width: 0; border-style: solid; 
                border-color: #e2e8f0; margin-bottom: .5rem;">
                    <span class="mr-3" style="box-sizing: border-box; border-width: 0; border-style: solid; 
                    border-color: #e2e8f0; margin-right: .75rem;">
                        {$participante['nombre']}
                    </span>
                </li>
            EOF;
        }
    }

    private function insertDatosAcuerdos(): void
    {
        foreach ($this->datos[6]['valor'] as $acuerdo) {
            $this->arrayInfoFinal['acuerdos'] .= <<< EOF
                <li class="ml-4 border-solid border-t-2 py-1 border-orange-600 mb-4" style="box-sizing: border-box; 
                border-width: 0; --border-opacity: 1; border-color: #dd6b20; border-color: rgba(221,107,32,var(--border-opacity));
                border-style: solid; border-top-width: 2px; margin-bottom: 1rem; margin-left: 1rem; padding-top: .25rem; 
                padding-bottom: .25rem;">
                {$acuerdo[0]->valorCampo}
                
                <p class="font-semibold leading-tight mt-3" style="box-sizing: border-box; border-width: 0; border-style: solid; border-color: #e2e8f0; margin: 0; font-weight: 600; line-height: 1.25; margin-top: .75rem;">
                    Responsable
                </p>
                <p class="inline-block ml-2 mt-2 px-2 bg-blue-500 text-white rounded-lg" style="box-sizing: border-box; border-width: 0; border-style: solid; border-color: #e2e8f0; margin: 0; --bg-opacity: 1; background-color: #4299e1; background-color: rgba(66,153,225,var(--bg-opacity)); border-radius: .5rem; display: inline-block; margin-top: .5rem; margin-left: .5rem; padding-left: .5rem; padding-right: .5rem; --text-opacity: 1; color: #fff; color: rgba(255,255,255,var(--text-opacity));">
                    {$acuerdo[1]->valorCampo}
                </p>
            </li> 
            EOF;
        }
    }

    private function crearInformacionFinal(): void
    {
        $this->insertarDatosGenerales();
        $this->insertarDatosParticipantes();
        $this->insertDatosAcuerdos();
    }

    public function getContent(): string
    {
        $content = file_get_contents($this->urlContent, FILE_USE_INCLUDE_PATH);

        ob_start();
        echo($content);
        $this->template = ob_get_clean();

        $this->replaceDataOnTemplate();

        return $this->template;
    }
}
