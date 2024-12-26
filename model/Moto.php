<?php
class Moto extends Vehiculo {

    public int $cilindrada;
    public String $tipo_m;
    public bool $baul;


    public function __construct(String $matricula, String $color, String $combustible, float $precio, Usuario $vendedor, int $cilindrada, String $tipo_m, bool $baul){
        parent::__construct($matricula,$color,$combustible,$precio,$vendedor);
        $this->cilindrada = $cilindrada;
        $this->tipo_m = $tipo_m;
        $this->baul = $baul;
    
    }
    public function setCilindrada(int $cilindrada): void {$this->cilindrada = $cilindrada;}

	public function setTipo(String $tipo_m): void {$this->tipo_m = $tipo_m;}

	public function setBaul(bool $baul): void {$this->baul = $baul;}

	public function getCilindrada(): int {return $this->cilindrada;}

	public function getTipo(): String {return $this->tipo_m;}

	public function getBaul(): bool {return $this->baul;}

	

    public function __toString(): string {
        return "
        Matricula: $this->matricula, 
        Color: $this->color, 
        Combustible: $this->combustible, 
        Precio: $this->precio, 
        Vendedor: $this->vendedor, 
        Cilindrada: $this->cilindrada,
        Tipo: $this->tipo_m,
        Baul: $this->baul
        ";
    }
}

?>