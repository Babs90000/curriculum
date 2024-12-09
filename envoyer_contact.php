<?php
require './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $attachment = $_FILES['attachment'];

    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        // Informations d'identification ENV
        $mail->Host = getenv("MAILERTOGO_SMTP_HOST", true);
        $mail->Port = intval(getenv("MAILERTOGO_SMTP_PORT", true));
        $mail->Username = getenv("MAILERTOGO_SMTP_USER", true);
        $mail->Password = getenv("MAILERTOGO_SMTP_PASS", true);
        $mailertogo_domain = getenv("MAILERTOGO_DOMAIN", true);

        // En-têtes de l'e-mail
        $mail->setFrom("mailer@{$mailertogo_domain}", "Votre Nom");
        $mail->addAddress('b.camara.diaby@outlook.com'); // Ajouter un destinataire

        // Ajouter la pièce jointe si elle existe
        if ($attachment['error'] == UPLOAD_ERR_OK) {
            $mail->addAttachment($attachment['tmp_name'], $attachment['name']);
        }

        // Contenu de l'e-mail
        $mail->isHTML(false);
        $mail->Subject = "Ton profil intéresse " . $name;
        $mail->Body    = "Nom: " . $name . "\nEmail: " . $email . "\nMessage: \n" . $message;

        // Envoyer l'e-mail
        $mail->send();

        // Envoyer l'e-mail de confirmation à l'utilisateur
        $mail->clearAddresses();
        $mail->addAddress($email);
        $mail->Subject = "Confirmation de réception de votre message";
        $mail->Body    = "Bonjour " . $name . ",\n\nMerci de votre intérêt pour mon profil. J'ai bien reçu votre message et je reviendrais vers vous dès que possible.\n\nCordialement,\nBabou CAMARA-DIABY";
        $mail->send();

        echo "Votre message a bien été envoyé.</br>";
    } catch (Exception $e) {
        echo "Votre message n'a pas été envoyé: {$mail->ErrorInfo}</br>";
    }

    echo "Vous allez être redirigé à la page précédente dans 5 secondes...";
    header("refresh:5;url=" . $_SERVER['HTTP_REFERER']);
    exit();
}
?>