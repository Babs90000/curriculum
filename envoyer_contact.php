<?php
require './vendor/autoload.php';

use Mailgun\Mailgun;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $apiKey = getenv('MAILGUN_API_KEY');
    $domain = getenv('MAILGUN_DOMAIN');
    $mgClient = Mailgun::create($apiKey);

    $attachment = $_FILES['attachment'];

    try {
        // Préparer les données de l'e-mail
        $emailData = [
            'from'    => 'no-reply@' . $domain,
            'to'      => 'b.camara.diaby@outlook.com',
            'subject' => "Ton profil intéresse " . $name,
            'text'    => "Nom: " . $name . "\nEmail: " . $email . "\nMessage: \n" . $message,
        ];

        // Ajouter la pièce jointe si elle existe
        if ($attachment['error'] == UPLOAD_ERR_OK) {
            $emailData['attachment'] = [
                [
                    'filePath' => $attachment['tmp_name'],
                    'filename' => $attachment['name']
                ]
            ];
        }

        // Envoyer l'e-mail au destinataire principal
        $mgClient->messages()->send($domain, $emailData);

        // Envoyer l'e-mail de confirmation à l'utilisateur
        $mgClient->messages()->send($domain, [
            'from'    => 'no-reply@' . $domain,
            'to'      => $email,
            'subject' => "Confirmation de réception de votre message",
            'text'    => "Bonjour " . $name . ",\n\nMerci de votre intérêt pour mon profil. J'ai bien reçu votre message et je reviendrais vers vous dès que possible.\n\nCordialement,\nBabou CAMARA-DIABY",
        ]);

        echo "Votre message a bien été envoyé.</br>";
    } catch (Exception $e) {
        echo "Votre message n'a pas été envoyé: {$e->getMessage()}</br>";
    }

    echo "Vous allez êtes redirigé à la page précédente dans 5 secondes...";
    header("refresh:5;url=" . $_SERVER['HTTP_REFERER']);
    exit();
}
?>