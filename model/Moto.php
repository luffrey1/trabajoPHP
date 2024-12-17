<?php
class Moto extends Vehiculo {

    public int $cilindrada;
    public String $tipo;
    public bool $baul;


    public function __construct(String $matricula, String $color, String $combustible, float $precio, Usuario $uVendedor, Usuario $uComprador, int $cilindrada, String $tipo, bool $baul){
        parent::__construct($matricula,$color,$combustible,$precio,$uvendedor,$uComprador);
        $this->cilindrada = $cilindrada;
        $this->tipo = $tipo;
        $this->baul = $baul;
    
    }
    public function setCilindrada(int $cilindrada): void {$this->cilindrada = $cilindrada;}

	public function setTipo(String $tipo): void {$this->tipo = $tipo;}

	public function setBaul(bool $baul): void {$this->baul = $baul;}

	public function getCilindrada(): int {return $this->cilindrada;}

	public function getTipo(): String {return $this->tipo;}

	public function getBaul(): bool {return $this->baul;}

	

    public function __toString(): string {
        return "
        Matricula: $this->matricula, 
        Color: $this->color, 
        Combustible: $this->combustible, 
        Precio: $this->precio, 
        Vendedor: $this->uVendedor, 
        Comprador: $this->uComprador,
        Cilindrada: $this->cilindrada,
        Tipo: $this->tipo,
        Baul: $this->baul
        ";
    }
}

?>