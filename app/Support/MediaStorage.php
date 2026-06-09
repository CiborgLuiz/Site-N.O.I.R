<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class MediaStorage
{
    public static function store(UploadedFile $file, string $directory): string
    {
        $directory = trim($directory, '/');
        $storageDirectory = trim(self::prefix().'/'.$directory, '/');
        $fileName = self::fileName($file);

        $storedPath = Storage::disk(self::disk())->putFileAs(
            $storageDirectory,
            $file,
            $fileName
        );

        if (! $storedPath) {
            throw new RuntimeException('Upload storage failed.');
        }

        return trim($storedPath, '/');
    }

    public static function delete(?string $path): void
    {
        if (! self::isManagedUpload($path)) {
            return;
        }

        Storage::disk(self::disk())->delete(trim((string) $path, '/'));
    }

    public static function url(?string $path): string
    {
        if (! $path) {
            return '';
        }

        $path = trim($path);

        if (Str::startsWith($path, ['http://', 'https://', 'data:'])) {
            return $path;
        }

        if (self::isManagedUpload($path)) {
            if (self::disk() === 'public_uploads') {
                return asset(ltrim($path, '/'));
            }

            return Storage::disk(self::disk())->url(ltrim($path, '/'));
        }

        return asset(ltrim($path, '/'));
    }

    public static function label(?string $path): string
    {
        if (! $path) {
            return '';
        }

        if (Str::startsWith($path, 'data:')) {
            return 'arquivo embutido';
        }

        return self::isManagedUpload($path)
            ? self::disk().':'.ltrim($path, '/')
            : ltrim($path, '/');
    }

    public static function isManagedUpload(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        return Str::startsWith(ltrim($path, '/'), self::prefix().'/');
    }

    public static function disk(): string
    {
        return config('filesystems.uploads_disk', 'public_uploads');
    }

    public static function prefix(): string
    {
        return trim(config('filesystems.uploads_path_prefix', 'uploads'), '/');
    }

    private static function fileName(UploadedFile $file): string
    {
        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($baseName) ?: 'arquivo';
        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'bin';

        return $safeName.'-'.now()->format('YmdHis').'-'.Str::lower(Str::random(6)).'.'.Str::lower($extension);
    }
}
