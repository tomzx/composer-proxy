<?php

namespace ComposerProxy\Server;

use Composer\Satis\Command\BuildCommand;
use Composer\Satis\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

class Server
{
	protected $config;

	public function serve(array $dependencies)
	{
		$this->config = require APP_ROOT.'/config.php';

		$updated = $this->updateConfiguration($dependencies);
		if ($updated) {
			// TODO: We may want to run satis anyways to check for updates
			$this->runSatis();
		}
	}

	protected function updateConfiguration(array $dependencies)
	{
		// Update config file
		$configFile = $this->config['config'];

		$initialConfig = [];
		$config = $this->getConfigTemplate();
		if (file_exists($configFile)) {
			$initialConfig = json_decode(file_get_contents($configFile), true);
			$config = array_merge($config, $initialConfig);
		}

		$config['require'] = $this->mergeDependencies($config['require'], $dependencies);

		if ($config !== $initialConfig) {
			file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));
			return true;
		}

		return false;
	}

	protected function getConfigTemplate()
	{
		return [
			'homepage' => $this->config['homepage'],
			'repositories' => $this->config['repositories'],
			'require' => [],
			'require-dependencies' => $this->config['require-dependencies'],
			'archive' => [
				'directory' => 'dist',
				'format' => 'zip',
				'skip-dev' => false,
			]
		];
	}

	// TODO: Probably need to force lowercase on everything, just in case
	// TODO: Validate that the given constraint is valid
	protected function mergeDependencies(array $currentDependencies, array $requestedDependencies)
	{
		foreach ($requestedDependencies as $require => $constraint) {
			$requireExists = array_key_exists($require, $currentDependencies);
			if ( ! $requireExists) {
				// Add it and move on
				$currentDependencies[$require] = $constraint;
				continue;
			}

			$currentConstraint = $currentDependencies[$require];
			$isInCurrentConstraint = strpos($currentConstraint, $constraint) !== false;
			if ($isInCurrentConstraint) {
				continue;
			}

			$currentDependencies[$require] = $currentConstraint.'|'.$constraint;
		}
		return $currentDependencies;
	}

	protected function runSatis()
	{
		$application = new Application();
		$application->add(new BuildCommand());

		$arguments = [
			'build',
			'file' => $this->config['config'],
			'output-dir' => APP_ROOT.'/public',
			'--no-html-output' => true,
		];

		$input = new ArrayInput($arguments);
		$output = new NullOutput();
		$application->run($input, $output);
	}
}