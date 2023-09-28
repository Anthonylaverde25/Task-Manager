<?php 

namespace Model;

class Usuario extends ActiveRecord {

    // LA COMBINACION  protected static SE EMPLEA PARA DEFINIR PROPIEDADES QUE ALMACENAN INFORMACION COMPARTIDA O CONFIGURACIONES EN COMUN CON TODAS LAS INSTANCIAS DE LA CLASE
    // LA PROPIEDAD STATIC PERMITE QUE ESTAS ESTEN PRESENTES EN TODAS LAS INSTANCIAS DE LA CLASE EN CUESTION Y AL SER PROTECTED SOLO PUEDEN SER ACCEDIDAS DESDE LA CLASE EN SI

    protected static $tabla = "usuarios";
    protected static $columnasDB = ['id','nombre','email','password','token','confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;
    
   public function __construct($args = []) {
    $this->id = $args['id'] ?? null;
    $this->nombre = $args['nombre'] ?? "";
    $this->email = $args['email'] ?? "";
    $this->password = $args['password'] ?? "";
    $this->password2 = $args['password2'] ?? "";
    $this->password_actual = $args['password_actual'] ?? "";
    $this->password_nuevo = $args['password_nuevo'] ?? "";
    $this->token = $args['token'] ?? "";
    $this->confirmado = $args['confirmado'] ?? 0;
   }
   

    function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = "El nombre de usuario es obligatorio";
        }

        if(!$this->email){
            self::$alertas['error'][] = "El correo electronico es obligatorio";
        }

        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        if(strlen($this->password) < 6 ) {
            self::$alertas['error'][] = 'La contraseña debe tener mas de 6 caracteres';
        }

        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Las contraseñas no coinciden';
        }

        return self::$alertas;
    }


    // Validar Usuario
    public function validar_perfil(){

        if($this->nombre === ""){
            self::$alertas['error'][] = 'El nombre de usuario es obligatorio';
        }

        if($this->email === ""){
            self::$alertas['error'][] = 'El correo electronico es obligatorio';
        }

        return self::$alertas;
    }


    
    


    // Validar Login
    public function validarLogin(){

       if(!$this->email) {
        self::$alertas['error'][] = 'El correo electronico es obligatorio';
       }


       if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
        self::$alertas['error'][] = 'Correo electronico no valido';
       }

       if(!$this->password){
        self::$alertas['error'][] = 'La contraseña es obligatoria';
       }

        return self::$alertas;
    }

    // Validar Password
    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        if(strlen($this->password) < 6 ) {
            self::$alertas['error'][] = 'La contraseña debe tener mas de 6 caracteres';
        }

        return self::$alertas;
    }

    // Validar nuevo Password
    public function validar_password(){
        if($this->password_actual === ""){
            self::$alertas['error'][] = 'La contraseña actual no puede estar vacia';
        };

        if($this->password_nuevo === ""){
            self::$alertas['error'][] ='La nueva contraseña no puede estar vacia';
        }

        if(strlen($this->password_nuevo) < 6){
            self::$alertas['error'][] = 'La nueva contraseña debe tener mas de 6 caracteres';
        }

        return self::$alertas;
    }

    // Comprobar password actual
    public function comprobar_password(){
        return password_verify($this->password_actual, $this->password);
    }


    // Valida email
    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El correo electronico es obligatorio';
        }

        if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'Correo electronico no valido';
        }

        return self::$alertas;
    }


    // HASHEA EL PASSWORD
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // CREAR TOKEN
    public function crearToken(){
        $this->token = uniqid();
    }    
}

?>