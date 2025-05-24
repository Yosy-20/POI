<?php
class usuario
{
    private $id;
    private $usuario;
    private $correo;
    private $contrasena;
    private $nombre;
    private $apellidoP;
    private $apellidoM;
    private $foto;

    public function UsuarioCompleto($usuario, $correo, $contrasena, $nombre, $apellidoP, $apellidoM,$foto)
    {
        $this->usuario = $usuario;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
        $this->nombre = $nombre;
        $this->apellidoP = $apellidoP;
        $this->apellidoM = $apellidoM;
        $this->foto= $foto;
    }
    public function __construct($usuario, $correo, $contrasena)
    {
        $this->usuario = $usuario;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
    }
    public function UsuarioInfo($id, $nombre, $apellidoP, $apellidoM, $foto)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidoP = $apellidoP;
        $this->apellidoM = $apellidoM;
        $this->foto= $foto;
    }



}

