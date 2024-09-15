<?php
namespace App\Services;

use App\Models\Client;
use App\Models\User;
use Illuminate\Validation\ValidationException;

interface UserServiceInterface
{
    public function storeUserSaveClient(array $data);
    public function getUsersWithFilters(array $data);
}
