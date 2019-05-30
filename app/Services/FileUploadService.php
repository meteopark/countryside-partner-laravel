<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Storage;

class FileUploadService
{
    const STORAGE_TYPE_PROFILE = "profile";
    const STORAGE_TYPE_CONTENT = "content";

    private $extensions = [
        'jpg',
        'jpeg',
        'png',
    ];

    /**
     * @param UploadedFile|null $file
     * @return string|null
     */
    public function uploadProfile(?UploadedFile $file): ?string
    {
        if (is_null($file)) {
            return null;
        }

        return $this->uploadToStorage(self::STORAGE_TYPE_PROFILE, $file);
    }

    /**
     * @param UploadedFile|null $file
     * @return string|null
     */
    public function uploadContent(?UploadedFile $file): ?string
    {
        if (is_null($file)) {

            return null;
        }

        return $this->uploadToStorage(self::STORAGE_TYPE_CONTENT, $file);
    }

    /**
     * @param string $storageType
     * @param UploadedFile $file
     * @return string|null
     */
    public function uploadToStorage(string $storageType, UploadedFile $file): ?string
    {
        $filePath = null;

        if ($this->arrowExtension($file->getClientOriginalExtension())) {
            $filePath = $storageType . '/' . time() . $file->getClientOriginalName();
            Storage::disk('ncloud')->put($filePath, file_get_contents($file));
        } else {
            $filePath = "File not allowed";
        }

        return $filePath;
    }

    /**
     * @param string $extension
     * @return bool
     */
    public function arrowExtension(string $extension): bool
    {
        return collect($this->extensions)->search(strtolower($extension));
    }
}
