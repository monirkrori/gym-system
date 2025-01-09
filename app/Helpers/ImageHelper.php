<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageHelper
{
    /**
     * Handle image upload and return the path.
     *
     * @param  \Illuminate\Http\UploadedFile  $image
     * @param  string  $directory
     * @return string|null
     */
    public static function uploadImage(UploadedFile $image, string $directory): ?string
    {
        // Validate image 
        if (!$image->isValid()) {
            return null;
        }

        // Define the file name (timestamp + original name)
        $fileName = time() . '_' . $image->getClientOriginalName();

        // Store the image and return the path
        $path = $image->storeAs($directory, $fileName, 'public');

        return $path;
    }
}
