<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\StorageUploader;

class PersonelController extends Controller
{
  public function __construct()
  {
  }

  public function upload(Request $request)
  {
    $params = array(
      'data' => $request->input('data')
    );
    $result = StorageUploader::saveFile('uploads', 'test_storage.json', $params['data']);
    return $result;
  }

  public function getFile()
  {
    $result = StorageUploader::getFile('uploads/test_storage.json');
    return $result;
  }
}
