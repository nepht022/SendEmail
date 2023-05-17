<?php
    //faz a requisicao do PHPMailer para o envio de emails
    require '../bibliotecas/PHPMailer/Exception.php';
    require '../bibliotecas/PHPMailer/PHPMailer.php';
    require '../bibliotecas/PHPMailer/SMTP.php';

    //faz a requisicao do php privado que controla o envio
    require '../processa_envio.php';
?>