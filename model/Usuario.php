<?php

class Usuario {
    public String $id;
    public String $contra;
    public String $direccion;
    public int $postal;
    public int $cochesV;
    public int $tlf;

    public function __construct(String $id, String $contra, String $direccion, int $postal, int $cochesV, int $tlf){$this->id = $id;$this->contra = $contra;$this->direccion = $direccion;$this->postal = $postal;$this->cochesV = $cochesV;$this->tlf = $tlf;}
	public function setId(String $id): void {$this->id = $id;}

	public function setContra(String $contra): void {$this->contra = $contra;}

	public function setDireccion(String $direccion): void {$this->direccion = $direccion;}

	public function setPostal(int $postal): void {$this->postal = $postal;}

	public function setCochesV(int $cochesV): void {$this->cochesV = $cochesV;}

	public function setTlf(int $tlf): void {$this->tlf = $tlf;}

	public function getId(): String {return $this->id;}

	public function getContra(): String {return $this->contra;}

	public function getDireccion(): String {return $this->direccion;}

	public function getPostal(): int {return $this->postal;}

	public function getCochesV(): int {return $this->cochesV;}

	public function getTlf(): int {return $this->tlf;}

	
    
}

?>