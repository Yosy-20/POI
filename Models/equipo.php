<?php
class equipo{
    private $nombre;
    private $descripcion;
    private $codigo;
    private $color;
    private $creado;

    public function __construct($nombre,$descripcion,$codigo,$color,$creado){
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->codigo = $codigo;
        $this->color = $color;
        $this->creado = $creado;

    }
}
