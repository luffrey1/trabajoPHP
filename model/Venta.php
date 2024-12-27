<?php

class Venta{

    public String $codigo_venta;
    public String $id_vehiculo;
    public String $id_comprador;
    public String $id_vendedor;

    public function __construct(String $codigo_venta, String $id_vehiculo, String $id_comprador, String $id_vendedor){
        $this->codigo_venta = $codigo_venta;
        $this->id_vehiculo = $id_vehiculo;
        $this->id_comprador = $id_comprador;
        $this->id_vendedor = $id_vendedor;
    }

    
    public function getCodigo_venta()
    {
        return $this->codigo_venta;
    }

    public function setCodigo_venta($codigo_venta)
    {
        $this->codigo_venta = $codigo_venta;

        return $this;
    }


    public function getId_vehiculo()
    {
        return $this->id_vehiculo;
    }


    public function setId_vehiculo($id_vehiculo)
    {
        $this->id_vehiculo = $id_vehiculo;

        return $this;
    }

    public function getId_comprador()
    {
        return $this->id_comprador;
    }

    public function setId_comprador($id_comprador)
    {
        $this->id_comprador = $id_comprador;

        return $this;
    }


    public function getId_vendedor()
    {
        return $this->id_vendedor;
    }


    public function setId_vendedor($id_vendedor)
    {
        $this->id_vendedor = $id_vendedor;

        return $this;
    }
}