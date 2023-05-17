<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Mensagem{
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        public $status = ['codigo_status'=>null, 'descricao_status'=>''];

        public function __get($name){
            return $this->$name;
        }
        public function __set($name, $value){
            $this->$name = $value;
        }
        //se algum campo do form estiver vazio, a mensagem nao sera valida
        public function mensagemValida(){
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
                return false;
            }else{
                return true;
            }
        }
    }

    //instancia a classe e define o valor dos seus atributos como os valores enviados via post
    $msg = new Mensagem();
    $msg->__set('para', $_POST['para']);
    $msg->__set('assunto', $_POST['assunto']);
    $msg->__set('mensagem', $_POST['mensagem']);

    if(!$msg->mensagemValida()){
        echo 'Mensagem Inválida!';
        header("Location: index.php");
    }
    //instancia de um email
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'host server';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'email';                     //SMTP username
        $mail->Password   = 'senha';                               //SMTP password
        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
        $mail->Port       = 'porta';                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        //quem vai enviar e receber o email
        $mail->setFrom('email', 'Rodrigo');
        $mail->addAddress($msg->__get('para'));     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $msg->__get('assunto');
        $mail->Body    = $msg->__get('mensagem');
        $mail->AltBody = 'É preciso um browser que suporte HTML';

        $mail->send();

        $msg->status['codigo_status'] = 1;
        $msg->status['descricao_status'] = 'E-mail enviado com sucesso!!';
    } catch (Exception $e) {
        $msg->status['codigo_status'] = 2;
        $msg->status['descricao_status'] = 'Não foi possivel enviar este email. Detalhes do ERROR: '.$mail->ErrorInfo;
    }
?>

<html>
    <head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
    <body>
        <div class="container">
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                        //se nao houve exception, exibe uma mensagem de sucesso
                        if($msg->status['codigo_status']==1){
                    ?>
                            <div class="container">
                                <h1 class="display-4 text-success">Sucesso</h1>
                                <p><?= $msg->status['descricao_status']?></p>
                                <a href="index.php" class="btn btn-lg btn-success mt-5 text-white">Voltar</a>
                            </div>
                    <?php
                        }
                    ?>

                     <?php
                        //se houve exception, exibe uma mensagem de erro
                        if($msg->status['codigo_status']==2){
                    ?>
                            <div class="container">
                                <h1 class="display-4 text-danger">Opps!</h1>
                                <p><?= $msg->status['descricao_status']?></p>
                                <a href="index.php" class="btn btn-lg btn-danger mt-5 text-white">Voltar</a>
                            </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
