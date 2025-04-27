<?php

namespace DrupalOrg;

use Castor\Attribute\AsTask;
use function Castor\io;
use function Castor\context;
use function Castor\run;

/**
 * Install contrib modules for Drupal.
 *
 * @param string $project_path
 *   The path to the project where the modules will be installed.
 *
 * @return void
 *   Nothing.
 */
#[AsTask(namespace: 'drupalorg', description: 'Installation des modules contrib Drupal')]
function installContribModules(string $project_path): void
{
    io()->section('Installation des modules contrib Drupal');
    install('select2', $project_path);
}

/**
 * Install contrib module.
 *
 * @param string $module_name
 *   The name of the module to install.
 * @param string $project_path
 *   The path to the project where the module will be installed.
 *
 * @return void
 *   Nothing.
 */
function install(string $module_name, string $project_path): void
{
    $package = 'drupal/' . $module_name;
    io()->section('Installation du module "' . $module_name . '"');
    $success = run("ddev composer require '$package'",
        context: context()->withWorkingDirectory($project_path));

    if ($success->getExitCode() !== 0) {
        io()->error('Erreur lors de l\'installation du module "' . $module_name . '"');
    } else {
        run('ddev drush en ' . $module_name . ' -y',
            context: context()->withWorkingDirectory($project_path));
    }
}