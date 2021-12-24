<?php

namespace App\Http\Controllers;

use App\Helpers\WsoApiHelper;

class GeneratorController extends Controller
{
  public function testHelper()
  {
    $dataApi = [
      'nama' => 'ApiWahyu',
      'header' => 'Test API Keterangan',
      'user_access_path' => '/test',
      'modul' => 'test',
      'method' => 'GET',
      'target_url' => 'https://test.com'
    ];

    $dataUser = [
      'nama' => 'Test User',
      'email' => 'febriantowahyu63@gmail.com'
    ];
    $testWso = WsoApiHelper::saveToWso((object) $dataApi, (object) $dataUser);
    return $testWso;
  }
}
