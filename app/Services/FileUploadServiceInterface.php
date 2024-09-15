<?php
namespace App\Services;
interface FileUploadServiceInterface{
public function uploadFile($file, $type, $customPath = null);
public function fileToBase64($filePath);
}
