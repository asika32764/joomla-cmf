<?php
/**
 * Part of rad project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace DevelopmentBundle\Command\Dev\Init;

use Windwalker\Console\Command\Command;

/**
 * The InitCommand class.
 *
 * @since  __DEPLOY_VERSION__
 */
class InitCommand extends Command
{
	/**
	 * An enabled flag.
	 *
	 * @var bool
	 */
	public static $isEnabled = true;

	/**
	 * Console(Argument) name.
	 *
	 * @var  string
	 */
	protected $name = 'init';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Init dev context, add config.dist and .gitignore.';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'init <cmd><command></cmd> <option>[option]</option>';

	/**
	 * Initialise command information.
	 *
	 * @return void
	 */
	public function initialise()
	{
		parent::initialise();
	}

	/**
	 * Execute this command.
	 *
	 * @return bool
	 */
	protected function doExecute()
	{
		$resrc = DEVELOPMENT_BUNDLE_ROOT . '/Resources/templates';

		$configFile = $resrc . '/configuration.dist.php';
		$gitignoreFile = $resrc . '/gitignore.dist';

		// Copy config
		$content = file_get_contents($configFile);

		// Replace secret
		$secret = \JUserHelper::genRandomPassword(16);

		$content = str_replace(
			"public \$secret = 'V0rML8ej3sPaDber';",
			sprintf("public \$secret = '%s';", $secret),
			$content
		);

		file_put_contents(JPATH_ROOT . '/configuration.dist.php', $content);

		$this->out('Write <info>configuration.dist.php</info> successfully.');

		// Copy gitignore
		\JFile::copy($gitignoreFile, JPATH_ROOT . '/.gitignore');

		$this->out('Write <info>.gitignore</info> successfully.');

		return true;
	}
}
