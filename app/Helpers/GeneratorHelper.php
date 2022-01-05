<?php

namespace App\Helpers;

use App\Helpers\StorageUploader;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\PhpExecutableFinder;

class GeneratorHelper
{
	const PATH_APICTL = '../../../../Documents/wso-cli/apictl';
	const PATH_APICTL_ARTISAN = '../../../Documents/wso-cli/apictl';
	const ENV_VARIABLE = ['HOME' => './../../../'];
	static function getApiMetaTemplate()
	{
		$apiMetaYamlPath = storage_path('app/templates/api_meta.yaml');
		$apiMetaYaml = Yaml::parseFile($apiMetaYamlPath);
		return $apiMetaYaml;
	}

	static function saveApiMeta($disk, $folder, $file, $type = 'yaml')
	{
		$tipeFile = ($type == 'yaml') ? 'yaml' : 'json';
		$dataFile = ($type == 'yaml') ? Yaml::dump($file, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK) : $file;
		$saveApiMeta = StorageUploader::saveToFolder($disk, $folder, 'api_meta.' . $tipeFile, $dataFile);
		return $saveApiMeta;
	}

	static function getApiTemplate()
	{
		$apiJsonPath = storage_path('app/templates/api.json');
		$apiJsonFile = file_get_contents($apiJsonPath);
		$apiJson = json_decode($apiJsonFile, true);
		return $apiJson;
	}

	static function saveApi($disk, $folder, $file, $type = 'yaml')
	{
		$tipeFile = ($type == 'yaml') ? 'yaml' : 'json';
		$dataFile = ($type == 'yaml') ? Yaml::dump($file, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK) : json_encode($file);
		$saveApi = StorageUploader::saveToFolder($disk, $folder, 'api.' . $tipeFile, $dataFile);
		return $saveApi;
	}

	static function getSwaggerTemplate()
	{
		$swaggerJsonPath = storage_path('app/templates/Definitions/swagger.json');
		$swaggerJsonFile = file_get_contents($swaggerJsonPath);
		$swaggerJson = json_decode($swaggerJsonFile, true);
		return $swaggerJson;
	}

	static function saveSwagger($disk, $folder, $file, $type = 'yaml')
	{
		$tipeFile = ($type == 'yaml') ? 'yaml' : 'json';
		$dataFile = ($type == 'yaml') ? Yaml::dump($file, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK) : json_encode($file);
		$folderFile = $folder . '/Definitions';

		if (!file_exists(storage_path('app/apis/' . $folderFile))) {
			mkdir(storage_path('app/apis/' . $folderFile), 0777, true);
		}
		$saveSwagger = StorageUploader::saveToFolder($disk, $folderFile, 'swagger.' . $tipeFile, $dataFile);
		return $saveSwagger;
	}

	static function apictlCommand($folderAPI, $env)
	{
		$command = 'apictl import api -f ' . storage_path('app/apis/' . $folderAPI) . ' -e ' . $env . ' -k';
		$process = Process::fromShellCommandline($command, self::PATH_APICTL_ARTISAN, self::ENV_VARIABLE);
		$process->run();
		if (!$process->isSuccessful()) {
			return ['success' => false, 'message' => $process->getErrorOutput()];
		} else {
			return ['success' => true, 'message' => $process->getOutput()];
		}
	}

	static function importAPI($config)
	{
		$wsoCommand = 'php artisan wso:import ' . $config['path'] . ' ' . $config['environment'];
		$process = Process::fromShellCommandline($wsoCommand, '../');
		$process->run();
		if ($process->getOutput() == 'Berhasil Impor API') {
			return ['success' =>  true, 'message' => $process->getOutput()];
		} else {
			return ['success' => false, 'message' => $process->getOutput()];
		}
	}

	static function generateUuid()
	{
		return Uuid::uuid6()->toString();
	}
}
