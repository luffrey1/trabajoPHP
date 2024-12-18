<?php
class Usuario {
    public $id;
    public $contra;
    public $direccion;
    public $cp;
    public $cVendidos;
    public $tlf;
    public $email;
    public $nombre;
    public $apellidos;
    public $imagen;

    // Constructor con parámetros opcionales y permitiendo que algunos sean null
    public function __construct(
        string $id, 
        string $contra, 
        ?string $direccion = null,  // Permite null por el ?
        ?string $cp = null,         // Permite null
        int $cVendidos = 0, 
        ?string $tlf = null,        // Permite null
        ?string $email = null,      // Permite null
        ?string $nombre = '',       // Permite null, valor por defecto es una cadena vacía
        ?string $apellidos = '',    // Permite null, valor por defecto es una cadena vacía
        $imagen = null
    ) {
        $this->id = $id;
        $this->contra = $contra;
        $this->direccion = $direccion;
        $this->cp = $cp;
        $this->cVendidos = $cVendidos;
        $this->tlf = $tlf;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->imagen = $imagen;
    }

    // Métodos Setters
    public function setId(string $id): void { $this->id = $id; }
    public function setContra(string $contra): void { $this->contra = $contra; }
    public function setDireccion(?string $direccion): void { $this->direccion = $direccion; }
    public function setCp(?string $cp): void { $this->cp = $cp; }
    public function setCVendidos(int $cVendidos): void { $this->cVendidos = $cVendidos; }
    public function setTlf(?string $tlf): void { $this->tlf = $tlf; }
    public function setEmail(?string $email): void { $this->email = $email; }
    public function setNombre(?string $nombre): void { $this->nombre = $nombre; }
    public function setApellidos(?string $apellidos): void { $this->apellidos = $apellidos; }
    public function setImagen($imagen): void { $this->imagen = $imagen; }

    // Métodos Getters
    public function getId(): string { return $this->id; }
    public function getContra(): string { return $this->contra; }
    public function getDireccion(): string { return $this->direccion ?? ''; }
    public function getCp(): string { return $this->cp ?? ''; }
    public function getCVendidos(): int { return $this->cVendidos; }
    public function getTlf(): string { return $this->tlf ?? ''; }
    public function getEmail(): string { return $this->email ?? ''; }
    public function getNombre(): string { return $this->nombre ?? ''; }
    public function getApellidos(): string { return $this->apellidos ?? ''; }
    public function getImagen() { return $this->imagen; }

    // Método mágico __toString para representar el objeto como string
    public function __toString() {
        return "Usuario: {$this->id}, Nombre: {$this->nombre} {$this->apellidos}";
    }
}
?>
