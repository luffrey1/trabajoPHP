<?php

class Venta{
    public Vehiculo $vehiculo;
    public Usuario $comprador;
    public Usuario $vendedor;
    public DateTime $fechaVenta;

    public function __construct(Vehiculo $vehiculo, Usuario $comprador, Usuario $vendedor, DateTime $fechaVenta){
        $this->vehiculo = $vehiculo;
        $this->comprador = $comprador;
        $this->vendedor = $vendedor;
        $this->fechaVenta = $fechaVenta;
    }

    


    public function getvehiculo()
    {
        return $this->vehiculo;
    }


    public function setvehiculo($vehiculo)
    {
        $this->vehiculo = $vehiculo;

        return $this;
    }

    public function getcomprador()
    {
        return $this->comprador;
    }

    public function setcomprador($comprador)
    {
        $this->comprador = $comprador;

        return $this;
    }


    public function getvendedor()
    {
        return $this->vendedor;
    }


    public function setvendedor($vendedor)
    {
        $this->vendedor = $vendedor;

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