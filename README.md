# drupal-init

Outil en ligne de commande pour initialiser rapidement et facilement un projet Drupal avec ddev.

## Fonctionnalités

- 🚀 Initialisation rapide d'un projet Drupal avec ddev
- ⚙️ Installation automatique de Drupal avec Drush
- 🧩 Installation optionnelle d'addons ddev (phpMyAdmin, Solr etc..)
- 📦 Installation automatique de modules contrib Drupal (select2 etc..)
- 🔄 Génération des liens d'accès au site et à l'administration

## Installation

### Option 1 : Installation du binaire (recommandé)

```bash
# Télécharger le binaire
curl -OL https://github.com/votre-compte/drupal-init/releases/latest/download/drupal-init.linux.x86_64

# Le rendre exécutable
chmod +x drupal-init.linux.x86_64

# Optionnel : déplacer le binaire dans un répertoire du PATH pour l'utiliser globalement
sudo mv drupal-init.linux.x86_64 /usr/local/bin/drupal-init
```

### Option 2 : Installation depuis les sources

Pour installer le projet depuis les sources ou contribuer au développement, consultez le [Guide de Développement](README-DEVELOPMENT.md).

## Utilisation

```bash
drupal-init
```

L'outil vous guidera pas à pas à travers le processus d'initialisation :

1. Vérification de l'installation de ddev
2. Demande du nom du projet
3. Création et configuration du projet ddev
4. Installation de Drupal via Composer et Drush
5. Installation optionnelle d'addons ddev
6. Installation des modules contrib Drupal
7. Affichage des liens d'accès

## Prérequis

- **ddev** doit être installé sur votre système.

## Licence

Ce projet est sous licence MIT.
