﻿Projet de S3 et S4 :
Ce projet à pour but de créer un site web de prise de RDV dans différents domaines (médical, juridique...).
Ce site sera générique et proche du site "Doctolib" (www.doctolib.fr) dans sa conception.
Cette plateforme permettra à un client ou un patient de prendre un rendez-vous téléphonique ou au cabinet, de poser une question simple, de demander une consultation juridique ou de santé sans transmission du dossier.

-------------------------------------------------------------------------

Identifiants d'un compte professionnel de test :
test@chirurgienc.com
testtest

Identifiants d'un compte utilisateur :
test@test.com
testtest

Identifiants compte admin :
admin@admin.com
adminadmin

Identifiants de la boîte mail du projet :

pfconsult.mail@gmail.com
pfconsult1234!

-------------------------------------------------------------------------

INSTALLATION SERVEUR MAIL POUR QUE LES MAILS FONCTIONNENT :

Le dossier sendmail est à copier à la racine de wamp (remonter de 1 niveau à partir du dossier www)

Pour trouver php.ini -> Cliquer sur wamp dans la barre des tâches, puis aller dans PHP -> php.ini
Remplacer uniquement les lignes suivantes du fichier :

[mail function]
; For Win32 only.
; http://php.net/smtp
SMTP = localhost
; http://php.net/smtp-port
smtp_port = 587

; For Win32 only.
; http://php.net/sendmail-from
sendmail_from ="admin@wampserver.invalid"

; For Unix only.  You may supply arguments as well (default: "sendmail -t -i").
; http://php.net/sendmail-path
sendmail_path = "D:\Programmes\wamp64\sendmail\sendmail"

COMMENT TESTER ? :

Créer un compte avec votre adresse mail perso, essayer de vous connecter et faire "mot de passe oublié", entrer votre mail.
Normalement un mail vous sera envoyé avec un mot de passe provisoire.

-------------------------------------------------------------------------

VERSIONS UTILISEES

Version de Wamp utilisée : 3.1.3
Version de PHP : 7.2.4
Version de PhpMyAdmin : 4.7.9

-------------------------------------------------------------------------

NAVIGATEUR A UTILISER

Firefox

-------------------------------------------------------------------------

AVIS AUX POTENTIELS FUTURS ETUDIANTS SUR LE PROJET
Bon chance


