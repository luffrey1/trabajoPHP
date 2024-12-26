<?php

abstract class Vehiculo {
    protected String $matricula;
    protected String $color;
    protected String $combustible;
    protected float $precio;
    protected Usuario $vendedor;

public function __construct(String $matricula, String $color, String $combustible, float $precio, Usuario $vendedor){
	$this->matricula = $matricula;$this->color = $color;$this->combustible = $combustible;$this->precio = $precio;$this->vendedor = $vendedor;}
	public function setMatricula(String $matricula): void {$this->matricula = $matricula;}

	public function setColor(String $color): void {$this->color = $color;}

	public function setCombustible(String $combustible): void {$this->combustible = $combustible;}

	public function setPrecio(float $precio): void {$this->precio = $precio;}

	public function setVendedor(Usuario $vendedor): void {$this->vendedor = $vendedor;}


	public function getMatricula(): String {return $this->matricula;}

	public function getColor(): String {return $this->color;}

	public function getCombustible(): String {return $this->combustible;}

	public function getPrecio(): float {return $this->precio;}

	public function getVendedor(): Usuario {return $this->vendedor;}


	public function __toString(): string {
        return "Matricula: $this->matricula, Color: $this->color, Combustible: $this->combustible, Precio: $this->precio, Vendedor: $this->vendedor";
    }
 
}

?>