Projet : Sortir.com
Version : 1.0.0
Auteur : LEJUEZ Morgan

Description:
Plateforme web permettant aux utilisateurs d'organiser des activités (sorties).
C'est une plateforme privée dont l’inscription sera gérée par le ou les administrateurs. 
Les sorties ainsi que les participants sont rattachés à un campus pour permettre une organisation géographique des sorties.

Installation:
Installer WampServer, cmder et composer.
Cloner le projet et le placer dans le sous-dossier www de wamp64.
Ouvrir l'invite de commande cmder, se placer dans le dossier "Sortir.com".
Installer tous les bundles nécessaires avec la commande "composer req nomDuBundle".
Créer la base de donnée avec la commande "php bin/console doctrine:database:create".
Dans votre navigateur, se rendre sur 127.0.0.1/phpmyadmin ou localhost/phpmyadmin.
Se connecter à la base de données (id: root, mdp: aucun).

Création de tables:
Dans cmder, toujours positionné dans le dossier Sortir.com, taper la commande suivante:
"php bin/console doctrine:schema:update --force"
Vérifier les tables dans phpmyadmin.


Accéder au site :
Dans le navigateur, taper l'url suivante:
127.0.0.1/Sortir.com/public ou localhost/Sortir.com/public

Créer un compte admin dans la base de donnée afin de pouvoir se connecter sur le site et accéder aux fonctionnalités du site.


Bugs et fonctionnalités à fixer:
- selects dynamiques formulaire de création de sortie
- filtrage du tableau de sorties

Fonctionnalités à venir:
- ajout de photo de profil
- responsive design
- inscription d'utilisateur par intégration fichier csv
- mot de passe oublié
- gestion de groupe privés (organisateur de sortie)