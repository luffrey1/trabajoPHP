<?php

class Venta{

    public String $codigoVenta;
    public String $idVehiculo;
    public String $idComprador;
    public String $idVendedor;
    public DateTime $fechaVenta;

    public function __construct(String $codigoVenta, String $idVehiculo, String $idComprador, String $idVendedor, DateTime $fechaVenta){
        $this->codigoVenta = $codigoVenta;
        $this->idVehiculo = $idVehiculo;
        $this->idComprador = $idComprador;
        $this->idVendedor = $idVendedor;
        $this->fechaVenta = $fechaVenta;
    }

    
    public function getCodigoVenta()
    {
        return $this->codigoVenta;
    }

    public function setCodigoVenta($codigoVenta)
    {
        $this->codigoVenta = $codigoVenta;

        return $this;
    }


    public function getIdVehiculo()
    {
        return $this->idVehiculo;
    }


    public function setIdVehiculo($idVehiculo)
    {
        $this->idVehiculo = $idVehiculo;

        return $this;
    }

    public function getIdComprador()
    {
        return $this->idComprador;
    }

    public function setIdComprador($idComprador)
    {
        $this->idComprador = $idComprador;

        return $this;
    }


    public function getIdVendedor()
    {
        return $this->idVendedor;
    }


    public function setIdVendedor($idVendedor)
    {
        $this->idVendedor = $idVendedor;

        return $this;
    }


    public function getFechaVenta()
    {
        return $this->fechaVenta;
    }

    public function setFechaVenta($fechaVenta)
    {
        $this->fechaVenta = $fechaVenta;

        return $this;
    }


}