<?php
class tarea{
    private $titulo;
    private $descripcion;

    private $fecha;
    private $hora;

    public function __construct($titulo,$descripcion,$fecha,$hora){
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->fecha = $fecha;
        $this->hora  = $hora;
    }

}