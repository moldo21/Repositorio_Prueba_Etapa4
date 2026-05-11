<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UsuarioApiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.api_usuarios.url');
    }

    private function conToken(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withToken(Session::get('api_token'))->timeout(10);
    }

    public function register(array $datos): array
    {
        $response = Http::timeout(10)->post($this->baseUrl . '/register', $datos);
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }
        return $json;
    }

    public function login(string $email, string $password): array
    {
        $response = Http::timeout(10)->post($this->baseUrl . '/login', [
            'email'    => $email,
            'password' => $password,
        ]);
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }
        return $json + ['status' => $response->status()];
    }

    public function logout(): array
    {
        $response = $this->conToken()->post($this->baseUrl . '/logout');
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }
        return $json;
    }

    public function perfil(): array
    {
        $response = $this->conToken()->get($this->baseUrl . '/perfil');
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }
        return $json;
    }

    public function getUsuarios(): array
    {
        $response = $this->conToken()->get($this->baseUrl . '/usuarios');
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }
        return $json;
    }

    public function getUsuario(int $id): array
    {
        $response = $this->conToken()->get($this->baseUrl . "/usuarios/{$id}");
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }
        return $json;
    }

    public function updateUsuario(int $id, array $datos): array
    {
        $response = $this->conToken()->put($this->baseUrl . "/usuarios/{$id}", $datos);
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }
        return $json;
    }

    public function deleteUsuario(int $id): array
    {
        $response = $this->conToken()->delete($this->baseUrl . "/usuarios/{$id}");
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }
        return $json;
    }
}
