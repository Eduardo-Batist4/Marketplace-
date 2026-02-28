<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('uploadImage')) {

    function uploadImage (?object $file, string $folder = 'uploads'): ?string
    {
        if(!$file) {
            return null;
        }

        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return Storage::putFileAs("public/{$folder}", $file, $fileName);
    }
}

if (!function_exists('deleteFile')) {
    function deleteFile(?string $path): void
    {
        if ($path && Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
