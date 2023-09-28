<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email,$nombre,$token)

    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }


  public function enviarConfirmacion(){

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Port = 2525;
    $mail->Username = '2e5d2c9c2b5655';
    $mail->Password = 'eb2527a23050ee';

    $mail->setFrom('cuentas@uptask.com');
    $mail->addAddress('cuentas@uptask.com', 'uptask.com');
    $mail->Subject = 'Confirmar tu cuenta';

    $mail->isHTML(TRUE);
    $mail->CharSet = 'UTF-8';

    $contenido = '<html>';
    $contenido.= "<p><strong>Hola ". $this->nombre ." </strong>Has creado tu cuenta en upTask, confirma en tu cuenta en el siguiente enlace</p>";
    $contenido.="<p>Presiona aqui:<a href='http://localhost:3000/confirmar?token=" .$this->token. "'>Confirma tu cuenta</a></p>";
    $contenido.="<p>Si tu no creaste esta cuenta ignora este mensaje</p>";
    $contenido.='</html>';


    $mail->Body = $contenido;
    $mail->send();


  }


  public function enviarInstrucciones(){
        
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Port = 2525;
    $mail->Username = '2e5d2c9c2b5655';
    $mail->Password = 'eb2527a23050ee';

    $mail->setFrom('cuentas@uptask.com');
    $mail->addAddress('cuentas@uptask.com', 'uptask.com');
    $mail->Subject = 'Recupera tu cuenta';

    $mail->isHTML(TRUE);
    $mail->CharSet = 'UTF-8';

    $contenido = '<html>';
    $contenido.= "<p><strong>Hola ". $this->nombre ." </strong>Parece que has perdido tu cuenta, recuperala en el siguiente enlace</p>";
    $contenido.="<p>Presiona aqui:<a href='http://localhost:3000/restablecer?token=" .$this->token. "'>Recuperar cuenta</a></p>";
    $contenido.="<p>Si tu no solicitaste recuperar esta cuenta, puedes ignorar este mensaje</p>";
    $contenido.='</html>';


    $mail->Body = $contenido;
    $mail->send();


  }
}