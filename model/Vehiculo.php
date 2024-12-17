<?php

abstract class Vehiculo {
    private String $matricula;
    private String $color;
    private String $combustible;
    private float $precio;
    private Usuario $uVendedor;
    private Usuario $uComprador;
public function __construct(String $matricula, String $color, String $combustible, float $precio, Usuario $uVendedor, Usuario $uComprador){$this->matricula = $matricula;$this->color = $color;$this->combustible = $combustible;$this->precio = $precio;$this->uVendedor = $uVendedor;$this->uComprador = $uComprador;}
	public function setMatricula(String $matricula): void {$this->matricula = $matricula;}

	public function setColor(String $color): void {$this->color = $color;}

	public function setCombustible(String $combustible): void {$this->combustible = $combustible;}

	public function setPrecio(float $precio): void {$this->precio = $precio;}

	public function setuVendedor(Usuario $uVendedor): void {$this->uVendedor = $uVendedor;}

	public function setUComprador(Usuario $uComprador): void {$this->uComprador = $uComprador;}

	public function getMatricula(): String {return $this->matricula;}

	public function getColor(): String {return $this->color;}

	public function getCombustible(): String {return $this->combustible;}

	public function getPrecio(): float {return $this->precio;}

	public function getuVendedor(): Usuario {return $this->uVendedor;}

	public function getUComprador(): Usuario {return $this->uComprador;}

	public function __toString(): string {
        return "Matricula: $this->matricula, Color: $this->color, Combustible: $this->combustible, Precio: $this->precio, Vendedor: $this->uVendedor, Comprador:: $this->uComprador";
    }
 
}

?>