<?php

use Castor\Attribute\AsTask;
use function Castor\import;
use function Ddev\ddevNew;
use function DrupalOrg\installContribModules;

import(__DIR__ . '/ddev/ddev.php');
import(__DIR__ . '/drupalorg/drupalorg.php');

#[AsTask(description: 'Initialise un projet Drupal complet', default: true)]
function init(): void
{
    // Check if ddev is installed, then start a new project.
    $project_path = ddevNew();

    // If project was successfully created.
    if ($project_path) {
        // Install contrib drupal modules.
        installContribModules($project_path);

        // Install custom drupal modules.
        // installCustomModules($project_path);

        // Install PlatformSh environment.
        // installPlatformsh($project_path);

        // Install SonraQube environment.
        // installSonarQube($project_path);
    }
}