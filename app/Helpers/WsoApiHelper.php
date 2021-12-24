<?php

namespace App\Helpers;

use App\Helpers\GeneratorHelper;
use Carbon\Carbon;

class WsoApiHelper
{
  const DISK_NAME = 'apis';

  static function saveToWso($dataAPI, $dataUser)
  {
    $folderName = $dataAPI->nama . '-1.0.0';
    if (!file_exists(self::storagePath())) {
      mkdir($folderName, 0777, true);
    }
    // Api Meta
    $apiMetaYaml = GeneratorHelper::getApiMetaTemplate();
    $apiMetaYaml['name'] = $dataAPI->nama;

    // Api
    $apiJson = GeneratorHelper::getApiTemplate();
    $apiJson['data']['id'] = GeneratorHelper::generateUuid();
    $apiJson['data']['name'] = $dataAPI->nama;
    $apiJson['data']['description'] = $dataAPI->header;
    $apiJson['data']['context'] = $dataAPI->user_access_path;
    $apiJson['data']['tags'] = [$dataAPI->modul];
    $apiJson['data']['businessInformation'] = [
      'businessOwner' => $dataUser->nama,
      'businessOwnerEmail' => $dataUser->email,
      'technicalOwner' => $dataUser->nama,
      'technicalOwnerEmail' => $dataUser->email
    ];
    $apiJson['data']['createdTime'] = Carbon::now()->toDateTimeString();
    $apiJson['data']['lastUpdatedTime'] = Carbon::now()->toDateTimeString();
    $apiJson['data']['endpointConfig']['sandbox_endpoints']['url'] = $dataAPI->target_url;
    $apiJson['data']['endpointConfig']['production_endpoints']['url'] = $dataAPI->target_url;
    $apiJson['data']['operations'] = [
      [
        'id' => '',
        'target' => '/',
        'verb' => $dataAPI->method,
        'authType' => 'Application & Application User',
        'throttlingPolicy' => 'Unlimited',
        'scopes' => [],
        'usedProductIds' => []
      ]
    ];

    // Swagger
    $swaggerJson = GeneratorHelper::getSwaggerTemplate();
    $swaggerJson['info']['title'] = $dataAPI->nama;
    $swaggerJson['info']['description'] = $dataAPI->header;
    $swaggerJson['info']['contact'] = [
      'name' => $dataUser->nama,
      'url' => '',
      'email' => $dataUser->email
    ];
    $swaggerJson['paths'] = [
      '/' => [
        strtolower($dataAPI->method) => [
          'description' => 'hello',
          'responses' => [
            '200' => [
              'description' => 'OK',
            ],
          ],
          'security' => [
            [
              'default' => []
            ]
          ],
          'x-auth-type' => 'Application & Application User',
          'x-throttling-tier' => 'Unlimited',
          'x-wso2-application-security' => [
            'security-types' => ['oauth2'],
            'optional' => false
          ]
        ]
      ]
    ];
    $swaggerJson['components']['securitySchemes']['default']['flows']['implicit']['scopes'] = (object)[];
    $swaggerJson['x-wso2-cors']['accessControlAllowMethods'] = [$dataAPI->method];
    $swaggerJson['x-wso2-production-endpoints']['urls'] = [$dataAPI->target_url];
    $swaggerJson['x-wso2-sandbox-endpoints']['urls'] = [$dataAPI->target_url];
    $swaggerJson['x-wso2-sandbox-endpoints']['type'] = 'https';
    $swaggerJson['x-wso2-basePath'] = $dataAPI->user_access_path . '/1.0.0';

    // Save Meta Api
    $saveApiMeta = GeneratorHelper::saveApiMeta(self::DISK_NAME, $folderName, $apiMetaYaml, 'yaml');
    if (!$saveApiMeta['success']) {
      return $saveApiMeta['message'];
    }

    // Save Api
    $saveApi = GeneratorHelper::saveApi(self::DISK_NAME, $folderName, $apiJson, 'json');
    if (!$saveApi['success']) {
      return $saveApi['message'];
    }

    // Save Swagger
    $saveSwagger = GeneratorHelper::saveSwagger(self::DISK_NAME, $folderName, $swaggerJson, 'json');
    if (!$saveSwagger['success']) {
      return $saveSwagger['message'];
    }

    $importAPI = GeneratorHelper::importAPI($folderName, 'development');
    if (!$importAPI['success']) {
      return $importAPI['message'];
    }
    return $importAPI;
  }

  private static function storagePath($folder = '')
  {
    return storage_path('app/' . self::DISK_NAME . '/' . $folder);
  }
}
