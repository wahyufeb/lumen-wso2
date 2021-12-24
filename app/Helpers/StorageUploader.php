<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;

class StorageUploader
{
  public static function saveFile($disk, $filename, $data)
  {
    $saveToStorage = Storage::disk($disk)->put($filename, $data);
    if ($saveToStorage != 1) {
      // Gagal save ke storage
      return "Gagal menyimpan ke storage";
    }
    // Berhasil save ke storage
    return "Berhasil menyimpan ke storage";
  }

  public static function getFile($path)
  {
    return Storage::get($path);
  }

  public static function saveToFolder($disk, $folder, $filename, $data)
  {
    $saveToStorage = Storage::disk($disk)->put($folder . '/' . $filename, $data);
    if ($saveToStorage != 1) {
      // Gagal save ke storage
      return [
        'success' => false,
        'message' => 'Gagal menyimpan ke storage'
      ];
    }
    // Berhasil save ke storage
    return [
      'success' => true,
      'message' => 'Berhasil menyimpan ke storage'
    ];
  }
}
