<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Helpers\GeneratorHelper;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;

/**
 * Class WsoCommand
 *
 * @category Console_Command
 * @package App\Console\Commands
 */
class WsoCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'wso:import {path} {env}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import WSO2 API';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */

	public function handle()
	{
		try {
			$pathApi = $this->argument('path');
			$envName = $this->argument('env');

			$importingWsoCommand = GeneratorHelper::apictlCommand($pathApi, $envName);
			if ($importingWsoCommand['success']) {
				$this->info('Berhasil Impor API');
			} else {
				$this->error($importingWsoCommand['message']);
			}
		} catch (Exception $e) {
			$this->error($e->getMessage());
		}
	}
}
