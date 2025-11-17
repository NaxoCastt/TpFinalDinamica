<?php
// Importar clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ControlCorreo {

    public static function enviarCorreo($destinatarioMail, $destinatarioNombre, $asunto, $cuerpoHTML) {
        
        $mail = new PHPMailer(true); 

        try {
            // ==========================================================
            // AQUI ES DONDE VAN TUS DATOS DE MAILTRAP
            // ==========================================================
            
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;  
            $mail->isSMTP();
            
            // Host:
            $mail->Host       = 'sandbox.smtp.mailtrap.io'; 
            
            $mail->SMTPAuth   = true;
            
            // Username:
            $mail->Username   = 'b8654df8859008'; 
            
            // Password:
            $mail->Password   = '02033c2c319de3';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            
            // Port: (587 es el estándar para STARTTLS)
            $mail->Port       = 587;                    
            


            // Remitente (Quien envía)
            $mail->setFrom('ventas@jugueteria.com', 'Juguetería IUPPI');
            
            // Destinatario (Quien recibe)
            $mail->addAddress($destinatarioMail, $destinatarioNombre); 

            // Contenido
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $asunto;
            $mail->Body    = $cuerpoHTML;
            $mail->AltBody = strip_tags($cuerpoHTML); 

            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            return false;
        }
    }
}

?>