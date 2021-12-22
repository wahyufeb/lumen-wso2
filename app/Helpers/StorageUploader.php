<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;


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
}
