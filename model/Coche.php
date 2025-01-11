<?php

class Coche extends Vehiculo {
    public int $puertas;
    public int $caballos;
    public String $carroceria;
    public String $airbag;


    public function __construct(String $matricula, String $color, String $combustible, float $precio, 
    Usuario $vendedor,int $puertas,int $caballos,String $carroceria,String $airbag, $imagen, String $comprado = 'no'){
    
        parent::__construct($matricula,$color,$combustible,$precio,$vendedor,$imagen, $comprado);
        $this->puertas = $puertas;
        $this->caballos = $caballos;
        $this->carroceria = $carroceria;
        $this->airbag = $airbag;

        
    }

    public function getPuertas(): int {return $this->puertas;}

	public function getCaballos(): int {return $this->caballos;}

	public function getCarroceria(): String {return $this->carroceria;}

	public function getAirbag(): String {return $this->airbag;}

	public function setPuertas(int $puertas): void {$this->puertas = $puertas;}

	public function setCaballos(int $caballos): void {$this->caballos = $caballos;}

	public function setCarroceria(String $carroceria): void {$this->carroceria = $carroceria;}

	public function setAirbag(String $airbag): void {$this->airbag = $airbag;}

	
	public function __toString(): string {
        return "Matricula: $this->matricula, 
        Color: $this->color, 
        Combustible: $this->combustible, 
        Precio: $this->precio, 
        Vendedor: $this->vendedor, 
        Puertas: $this->puertas,
        CV: $this->caballos,
        Carroceria: $this->carroceria,
        Airbags: $this->airbag
        
        ";
    }

}

?>