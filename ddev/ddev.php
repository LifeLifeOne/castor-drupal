<?php

namespace Ddev;

use Castor\Attribute\AsTask;
use function Castor\io;
use function Castor\run;
use function Castor\fs;
use function Castor\context;
use function Castor\exit_code;
use function Castor\capture;

/**
 * Check if ddev is installed on the system.
 *
 * @return bool
 *   TRUE if ddev is installed, FALSE otherwise.
 */
#[AsTask(namespace: 'ddev', description: 'Vérifie si ddev est installé sur le système')]
function checkDdevInstallation(): bool
{
    io()->section('Vérification de l\'installation de ddev');

    $ddev_installed = exit_code('which ddev > /dev/null 2>&1') === 0;

    if (!$ddev_installed) {
        io()->error('ddev n\'est pas installé sur votre système.');
        io()->note('Pour installer ddev, suivez les instructions sur:');
        io()->writeln('<info>https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/</info>');
        return false;
    }

    $version = capture('ddev --version');
    io()->success("ddev est installé (version: $version)");

    return true;
}

/**
 * Install selected ddev addons for the project.
 *
 * @param string $project_path
 *   The path to the project where the addons will be installed.
 *
 * @return void
 *   Nothing.
 */
function install_addons(string $project_path): void
{
    io()->section('Installation des addons ddev');

    // Initialize the addons installed counter.
    $addons_installed = 0;

    // Ask for phpMyAdmin installation.
    if (io()->confirm('Voulez-vous installer phpMyAdmin ?', FALSE)) {
        io()->text('Installation de phpMyAdmin...');
        run('ddev add-on get ddev/ddev-phpmyadmin',
            context: context()->withWorkingDirectory($project_path));
        io()->success('phpMyAdmin installé !');
        $addons_installed++;
    }

    // Ask for Solr installation.
    if (io()->confirm('Voulez-vous installer Solr ?', FALSE)) {
        io()->text('Installation de Solr...');
        run('ddev add-on get ddev/ddev-solr',
            context: context()->withWorkingDirectory($project_path));
        io()->success('Solr installé !');
        $addons_installed++;
    }

    if ($addons_installed > 0) {
        io()->success("$addons_installed addon(s) installé(s) !");
        // Restart ddev to apply addons.
        if (io()->confirm('Voulez-vous redémarrer ddev pour appliquer les addons ?')) {
            io()->text('Redémarrage de ddev...');
            run('ddev restart', context: context()->withWorkingDirectory($project_path));
            io()->success('ddev redémarré !');
        }
    } else {
        io()->warning('Aucun addon n\'a été installé.');
    }
}

/**
 * Create a new Drupal project using ddev.
 *
 * @return string|null
 *   The path to the created project or NULL if the project creation failed.
 */
#[AsTask(namespace: 'ddev', description: 'Initialise un nouveau projet Drupal dans un dossier dédié')]
function ddevNew(): ?string
{
    io()->title('Création d\'un nouveau projet Drupal');

    // Check if ddev is installed.
    if (!checkDdevInstallation()) {
        return NULL;
    }

    // Ask for the project name.
    $project_name = io()->ask('Quel est le nom machine du futur projet ?');

    // Get the parent directory of the Castor project.
    $parent_dir = dirname(getcwd());

    // Full path of the project.
    $project_path = $parent_dir . '/' . $project_name;

    // Check if the project directory already exists.
    if (fs()->exists($project_path)) {
        io()->error("Le dossier '$project_path' existe déjà.");
        return NULL;
    }

    // Create the project directory.
    fs()->mkdir($project_path);

    io()->section('Initialisation de ddev');

    run('ddev config --project-type=drupal11 --docroot=web',
        context: context()->withWorkingDirectory($project_path));
    run('ddev start',
        context: context()->withWorkingDirectory($project_path));

    io()->section('Installation de Drupal');
    run('ddev composer create "drupal/cms" -n',
        context: context()->withWorkingDirectory($project_path));

    // Install addons if requested.
    install_addons($project_path);

    // Install Drush.
    io()->section('Installation de Drush');
    run('ddev composer require drush/drush',
        context: context()->withWorkingDirectory($project_path));

    // Install Drupal with Drush.
    io()->section('Installation de Drupal avec Drush');
    run('ddev drush site:install -y',
        context: context()->withWorkingDirectory($project_path));

    // Display ddev describe.
    io()->section('Configuration de ddev');
    run('ddev describe',
        context: context()->withWorkingDirectory($project_path));

    io()->success("Projet Drupal '$project_name' initialisé dans '$project_path' !");

    // Print login link.
    io()->section('Accès à Drupal');
    run('ddev drush uli',
        context: context()->withWorkingDirectory($project_path));

    // Return the project path for further use.
    return $project_path;
}