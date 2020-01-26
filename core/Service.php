<?php

class Service {
    /*
     * la fonction envoie mail prend en paramètre le destinataire
     */

    public function envoiMail($to, $body, $subject) {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'inscription@uvs.edu.sn';
        $mail->Password = 'Passer@2017';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->From = 'support@uvs.edu.sn';
        $mail->FromName = 'CURSUS - UVS';
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->MsgHTML($body);

        if (is_array($to)) {
            $mail->addAddress($to['mail'], $to['prenom'] . ' ' . $to['nom']);
            //$mail->addAddress('ahmet.thiam@uvs.edu.sn', 'Ahmet THIAM');
        } else {
            $mail->AddAddress($to);
        }
        $mail->addAddress('ahmet.thiam@uvs.edu.sn', 'Ahmet THIAM');
        //$mail->addAddress('birahim.babou@uvs.edu.sn', 'Birahim BABAOU');
        //$mail->addReplyTo('support@uvs.edu.sn', 'Support - DISI');

        try {
            if (!$mail->Send()) {
                $envoie = 0;
            } else {
                $envoie = 1;
            }
        } catch (phpmailerException $e) {
            echo $e->errorMessage();
        } catch (Exception $e) {
            echo $e->errorMessage();
        }
        $mail->SmtpClose();
        unset($mail);

        return $envoie;
    }

    public function corpsMailCompte($e_mail) {
        $contenu = "<h2 style='color: white; padding-top: 10px; padding-bottom: 10px; padding-left: 10px; background-color: #4184F3; height: 25%; width: 100%;'>Ajout d'un utilisateur à CURSUS</h2>";
        $contenu .= '<p> Identifiant : <strong>' . $e_mail . '</strong></p>';
        $contenu .= '<p> Mot de passe : <strong>{Le mot de passe du Portail}</strong></p>';
        $contenu .= '<br>';
        $contenu .= '<p> Accès : <strong>https://cursus.uvs.sn</strong></p>';
        $contenu .= "<p><i>Pour plus de sécurité, vous avez la possibilité de modifier votre mot de passe à partir de votre profil.</i></p>";
        $contenu .= "<p><i>Merci de contacter l'administrateur pour plus d'informations!</i></p>";

        return $contenu;
    }

}
