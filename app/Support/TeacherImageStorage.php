<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class TeacherImageStorage
{
    public static function store(UploadedFile $file): string
    {
        $directory = public_path('uploads/teachers');

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = bin2hex(random_bytes(8)) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($directory, $filename);

        return 'uploads/teachers/' . $filename;
    }

    public static function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($path);
        $uploadDirectory = public_path('uploads/teachers');

        if (! str_starts_with(realpath($fullPath) ?: '', realpath($uploadDirectory) ?: '')) {
            return;
        }

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
