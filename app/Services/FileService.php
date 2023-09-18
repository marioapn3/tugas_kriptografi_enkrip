<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function uploadFile($file, $path = 'file')
    {
        $fileName = Str::random(60);
        $extension = $file->getClientOriginalExtension();
        // $path = date('Y') . '/' . date('m') . '/' . date('d');
        $pathName = '/storage/' . $path . '/' . $fileName . '.' . $extension;

        Storage::put('/public/' . $path . '/' . $fileName . '.' . $extension, File::get($file));

        return $pathName;
    }
}
