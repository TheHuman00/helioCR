# Helio

![](<.gitbook/assets/Capture d’écran 2022-06-24 à 00.20.49.png>) ![](<.gitbook/assets/Capture d’écran 2022-06-24 à 00.11.04.png>) ![](<.gitbook/assets/Capture d’écran 2022-06-24 à 00.09.18.png>)

**HELIO** est un système de gestion de préventif, fondamentalement pensé pour les sections locale de la croix-rouge. Voici une liste des fonctionnalités du système (non-exhaustives) :&#x20;

* Gestion des volontaires et de leurs informations/compétence/formation
* Gestion des préventifs et de leurs effectifs
* Statistiques
* Admin/user

## Se connecter

1.  Nom d'utilisateur == Nom Prénom&#x20;

    Exemple : _Bob Michel_
2.  Votre mots de passe

    Par défaut : _mdp_

{% hint style="danger" %}
N'oubliez pas de changer votre mots de passe de base
{% endhint %}

## Installation de son instance de Hélio

{% hint style="info" %}
Je veux bien volontiers t'aider à installer une instance (pour n'importe quelle usage)

Go sur dans la partie [contact](contact.md)
{% endhint %}

### Téléchargement du repo

```bash
# Cloner le projet
$ git clone https://github.com/TheHuman00/helioCR.git
# Accéder au dossier télécharger
$ cd helioCR
```

### Ajout de la base de donnée

Chercher le fichier `helio.sql` Et mettez le dans votre base MYSQL qui se nommera **helio**

Ce fichier est un template

### Configuration des variables

1. Configurer le fichier `include/config.php` _pour la connection à la base de donnée_
2.  Modifier les mentions **CHANGER-MOI** dans les fichiers suivant :&#x20;

    Pour la configuration du système d'envoi de **e-mail** :&#x20;

    `acceptevent.php || accepteventpost.php || addevent.php || demandeperso.php || emaildispo.php || refuserevent.php || utilisateuradmin.php`

    Pour la configuation du système d'envoi de **SMS :**&#x20;

    `addevent.php || demandepersosms.php || eventadmin.php`

## &#x20;:newspaper: Table des matières

* [Vu par l'utilisateur](broken-reference)
  * [Accueil](vu-par-lutilisateur/accueil.md)
  * [Liste de préventif](vu-par-lutilisateur/liste-de-preventif.md)
  * [Préventifs](vu-par-lutilisateur/preventifs.md)
  * [Volontaires](vu-par-lutilisateur/volontaires.md)
  * [Exporter calendrier](vu-par-lutilisateur/exporter-calendrier.md)
  * [Changer de mots de passe](vu-par-lutilisateur/changer-de-mots-de-passe.md)
  * [Double préventif](vu-par-lutilisateur/double-preventif.md)
* [Vu par un responsable](broken-reference)
  * [Accueil](vu-par-un-responsable/accueil.md)
  * [Préventifs](vu-par-un-responsable/preventifs.md)
  * [Volontaires](vu-par-un-responsable/volontaires.md)
* [Contact](contact.md)
* [Code source](code-source.md)
