# Guide de développement de drupal-init

Ce document décrit comment configurer votre environnement pour contribuer au développement de l'outil `drupal-init`.

## Prérequis

Pour contribuer au développement de ce projet, vous aurez besoin des éléments suivants installés sur votre système :

- **PHP 8.3+**
- **Composer**
- **Box** (pour compiler le PHAR)
- **ddev** (pour tester l'initialisation des projets Drupal)

## Configuration de l'environnement de développement

### Installation de PHP 8.3+

Sur Ubuntu/Debian :
```bash
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.3 php8.3-cli php8.3-common php8.3-xml php8.3-mbstring php8.3-zip
```

### Installation de Composer

```bash
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

### Installation de Box

```bash
curl -LSs https://github.com/box-project/box/releases/latest/download/box.phar -o box.phar
chmod +x box.phar
sudo mv box.phar /usr/local/bin/box
```

### Configuration de phar.readonly

Pour pouvoir créer des archives PHAR, vous devez désactiver la directive `phar.readonly` dans PHP :

```bash
# Trouvez le chemin de votre php.ini
php -i | grep "Loaded Configuration File"

# Éditez le fichier php.ini
sudo nano /etc/php/8.3/cli/php.ini

# Trouvez la ligne avec phar.readonly et mettez-la à Off
phar.readonly = Off
```

### Installation de ddev

Suivez les instructions sur le site officiel de ddev : https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/

## Installation du projet

1. Clonez le dépôt :
```bash
git clone https://github.com/votre-compte/drupal-init.git
cd drupal-init
```

2. Installez les dépendances :
```bash
composer install
```

## Tester l'outil

Pour tester l'outil pendant le développement, exécutez :
```bash
vendor/bin/castor
```

## Compilation de l'outil

Pour construire le binaire distributable, utilisez la commande suivante :

```bash
vendor/bin/castor repack --app-name=drupal-init && vendor/bin/castor compile drupal-init.linux.phar
```

Cette commande effectue deux opérations :
1. `vendor/bin/castor repack --app-name=drupal-init` : Recrée le fichier PHAR avec le nom spécifié
2. `vendor/bin/castor compile drupal-init.linux.phar` : Compile ce PHAR en un binaire statique autonome

Le binaire résultant (`drupal-init.linux.x86_64`) peut être distribué et exécuté sur n'importe quel système Linux compatible, sans nécessiter l'installation de PHP.

### Explications détaillées de la compilation

- La commande `repack` crée une archive PHAR qui contient toute l'application et ses dépendances
- Le paramètre `--app-name=drupal-init` définit le nom de l'application dans le PHAR
- La commande `compile` utilise [static-php-cli](https://github.com/crazywhalecc/static-php-cli) pour transformer le PHAR en un binaire autonome
- Le binaire final ne nécessite aucune dépendance externe et peut s'exécuter sur n'importe quel système Linux compatible

### Publier une nouvelle version

Après avoir compilé le binaire :
1. Testez-le localement
2. Créez un tag Git et une release sur GitHub
3. Téléversez le binaire compilé dans la release
4. Mettez à jour la documentation si nécessaire

## Structure du projet

- `castor.php` : Point d'entrée principal de l'application
- `ddev/ddev.php` : Contient les fonctions liées à ddev
- `drupalorg/drupalorg.php` : Contient les fonctions pour installer les modules Drupal

## Téléchargement du binaire

```bash
# Télécharger le binaire
curl -OL https://github.com/LifeLifeOne/castor-drupal/releases/download/v1.0/drupal-init.linux.x86_64

# Le rendre exécutable
chmod +x drupal-init.linux.x86_64

# Optionnel : déplacer le binaire dans un répertoire du PATH pour l'utiliser globalement
sudo mv drupal-init.linux.x86_64 /usr/local/bin/drupal-init
```

## Conventions de code

- Les variables PHP doivent utiliser le format snake_case (`$my_variable`)
- Les commentaires doivent être en anglais et se terminer par un point
- Chaque fonction doit avoir une documentation PHPDoc complète

## Licence

Ce projet est sous licence MIT.
