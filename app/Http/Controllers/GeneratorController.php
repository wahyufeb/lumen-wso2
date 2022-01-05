<?php

namespace App\Http\Controllers;

use App\Helpers\WsoApiHelper;

class GeneratorController extends Controller
{
	public function testHelper()
	{
		$dataApi = [
			'nama' => 'ApiJune',
			'header' => 'Test API June123',
			'user_access_path' => '/june',
			'modul' => 'june',
			'method' => 'GET',
			'target_url' => 'https://m.facebook.com'
		];

		$dataUser = [
			'nama' => 'June Wanwimol',
			'email' => 'june@gmail.com'
		];
		$testWso = WsoApiHelper::saveToWso((object) $dataApi, (object) $dataUser);
		return $testWso;
	}
}
