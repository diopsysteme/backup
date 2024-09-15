<?php
namespace App\Services;

use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService implements FileUploadServiceInterface
{
    // Upload de fichier
    public function uploadFile($file, $type, $customPath = null)
    {
        $path = $customPath ?? ($type === 'image' ? 'images' : 'documents');

        if (is_string($file)) {
            $file = new UploadedFile($file, basename($file), null, null, true);
        }

        return $file->store($path);

         
    }

    public function fileToBase64($filePath)
    {
        if (Storage::exists($filePath)) {
            $fileContent = Storage::get($filePath);

            return base64_encode($fileContent);
        }
        // throw new ServerException("Fichier non trouvé.",request() );
    }

    
}
