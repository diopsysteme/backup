<?php
namespace App\Providers;
use Illuminate\Http\Request;
interface AuthenticateServiceProvider
{
    public function login(array $credentials);
    public function logout();
    public function refresh(Request $request);
}
