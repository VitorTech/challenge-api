<?php

namespace App\Support\Traits;

use Illuminate\Support\Facades\Storage;

trait UsesFile
{
    /**
     * @param string $filename
     * @param string $path = 'public/files/'
     * @return string
     */
    public static function getFileURL(
        string|null $filename,
        string $path = 'public/files/'
    ): ?string {
        if (!$filename) {
            return null;
        }

        return env('APP_URL') . ':' . env('APP_PORT', '80') . Storage::url($path . $filename);
    }

    /**
     * @param string $filename
     * @param string $path = 'public/files/'
     * @return boolean
     */
    public function deleteFile(
        string|null $filename,
        string $path = 'public/files/'
    ): bool {
        $file = $path . $filename;

        return Storage::delete($file);
    }
}
