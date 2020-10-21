<?php
namespace Mail\Templates;

interface TemplateInterface {
    public function __construct(array $datos);
    public function getContent(): string;
}
