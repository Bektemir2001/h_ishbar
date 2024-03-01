<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class UploadFileService
{
    public function upload(UploadedFile $file, string $path): string
    {
        $name = str_replace(['/', '.'], '', Hash::make(time() . rand(10000000, 99999999)));
        $fileName = $name . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($path), $fileName);
        return $path.'/'.$fileName;
    }
}
