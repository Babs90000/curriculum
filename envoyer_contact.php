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
        $mail->Host = getenv('MAILERTOGO_SMTP_HOST'); // Adresse du serveur SMTP
        $mail->SMTPAuth = true;
        $mail->Username = getenv('MAILERTOGO_SMTP_USER'); // Nom d'utilisateur SMTP
        $mail->Password = getenv('MAILERTOGO_SMTP_PASS'); // Mot de passe SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Destinataires
        $mail->setFrom('no-reply@votre-domaine.com', 'Votre Nom');
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