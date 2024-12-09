<?php
require './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $attachment = $_FILES['attachment'];

    $phpmailer = new PHPMailer(true);

    try {
        // Configuration du serveur SMTP
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io'; // Adresse du serveur SMTP de Mailtrap
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = '42e27e6fee9041'; // Votre nom d'utilisateur Mailtrap
        $phpmailer->Password = '76f234318a9081'; // Votre mot de passe Mailtrap
        $phpmailer->SMTPSecure = 'tls';
        $phpmailer->Port = 2525;

        // En-têtes de l'e-mail
        $phpmailer->setFrom('from@example.com', 'Babou-CAMARA-DIABY');
        $phpmailer->addAddress('b.camara.diaby@outlook.com'); // Ajouter un destinataire

        // Ajouter la pièce jointe si elle existe
        if ($attachment['error'] == UPLOAD_ERR_OK) {
            $phpmailer->addAttachment($attachment['tmp_name'], $attachment['name']);
        }

        // Contenu de l'e-mail
        $phpmailer->isHTML(false); // Envoyer l'e-mail en texte brut
        $phpmailer->Subject = "Ton profil intéresse " . $name;
        $phpmailer->Body    = "Nom: " . $name . "\nEmail: " . $email . "\nMessage: \n" . $message;

        // Envoyer l'e-mail
        $phpmailer->send();

        // Envoyer l'e-mail de confirmation à l'utilisateur
        $phpmailer->clearAddresses();
        $phpmailer->addAddress($email);
        $phpmailer->Subject = "Confirmation de réception de votre message";
        $phpmailer->Body    = "Bonjour " . $name . ",\n\nMerci de votre intérêt pour mon profil. J'ai bien reçu votre message et je reviendrais vers vous dès que possible.\n\nCordialement,\nBabou CAMARA-DIABY";
        $phpmailer->send();

        echo "Votre message a bien été envoyé.</br>";
    } catch (Exception $e) {
        echo "Votre message n'a pas été envoyé: {$phpmailer->ErrorInfo}</br>";
    }

    echo "Vous allez être redirigé à la page précédente dans 5 secondes...";
    header("refresh:5;url=" . $_SERVER['HTTP_REFERER']);
    exit();
}
?>