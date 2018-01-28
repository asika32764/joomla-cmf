<?php
/**
 * Part of joomlarad project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace DevelopmentBundle\Installer;

use Composer\Script\Event;

/**
 * The composer installer.
 *
 * @since 1.0
 */
class ComposerInstaller
{
	/**
	 * Do install.
	 *
	 * @param Event $event The command event.
	 *
	 * @return  void
	 */
	public static function install(Event $event)
	{
		$windPath = getcwd();
		$root = realpath($windPath . '/../../..');

		$io = $event->getIO();

		$resourcesPath = $root . '/resources';

		if (!is_dir($resourcesPath))
		{
			mkdir($resourcesPath, 0755, true);

			file_put_contents($resourcesPath . '/index.html', '<html></html>');

			$io->write('Create resources folder.');
		}

		$seederPath = $resourcesPath . '/seeders/DatabaseSeeder.php';

		if (!is_file($seederPath))
		{
			mkdir(dirname($seederPath), 0755, true);

			copy($windPath . '/Resources/templates/DatabaseSeeder.php', $seederPath);

			$io->write('Create Seeder file.');
		}

		// Complete
		$io->write('Install complete.');
	}
}
