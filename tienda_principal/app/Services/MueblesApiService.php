<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MueblesApiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.api_muebles.url');
    }

    private function conToken(): PendingRequest
    {
        return Http::withToken(Session::get('api_token'))->timeout(10);
    }

    private function sinToken(): PendingRequest
    {
        return Http::timeout(10);
    }

    // Muebles

    public function getMuebles(array $filtros = []): array
    {
        $response = $this->sinToken()->get($this->baseUrl . '/muebles', $filtros);
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json;
    }

    public function getMueble(int $id): array
    {
        $response = $this->sinToken()->get($this->baseUrl . "/muebles/{$id}");
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json;
    }

    public function createMueble(array $datos, $imagen = null): array
    {
        $request = $this->conToken();

        if ($imagen) {
            $response = $request->attach('imagen', file_get_contents($imagen->getRealPath()), $imagen->getClientOriginalName())
                ->post($this->baseUrl . '/muebles', $datos);
        } else {
            $response = $request->post($this->baseUrl . '/muebles', $datos);
        }

        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json + ['status' => $response->status()];
    }

    public function updateMueble(int $id, array $datos, $imagen = null): array
    {
        $request = $this->conToken();

        if ($imagen) {
            // Para PUT con multipart usamos POST con _method=PUT
            $datos['_method'] = 'PUT';
            $response = $request->attach('imagen', file_get_contents($imagen->getRealPath()), $imagen->getClientOriginalName())
                ->post($this->baseUrl . "/muebles/{$id}", $datos);
        } else {
            $response = $request->put($this->baseUrl . "/muebles/{$id}", $datos);
        }

        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json + ['status' => $response->status()];
    }

    public function deleteMueble(int $id): array
    {
        $response = $this->conToken()->delete($this->baseUrl . "/muebles/{$id}");
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json;
    }

    // Categorias

    public function getCategorias(): array
    {
        $response = $this->sinToken()->get($this->baseUrl . '/categorias');
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json;
    }

    public function getCategoria(int $id): array
    {
        $response = $this->sinToken()->get($this->baseUrl . "/categorias/{$id}");
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json;
    }

    public function createCategoria(array $datos): array
    {
        $response = $this->conToken()->post($this->baseUrl . '/categorias', $datos);
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json + ['status' => $response->status()];
    }

    public function updateCategoria(int $id, array $datos): array
    {
        $response = $this->conToken()->put($this->baseUrl . "/categorias/{$id}", $datos);
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json + ['status' => $response->status()];
    }

    public function deleteCategoria(int $id): array
    {
        $response = $this->conToken()->delete($this->baseUrl . "/categorias/{$id}");
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json;
    }

    // Galeria

    public function subirImagenes(int $productoId, array $imagenes): array
    {
        $request = $this->conToken()->asMultipart();

        foreach ($imagenes as $img) {
            $request = $request->attach(
                'imagenes[]',
                file_get_contents($img->getRealPath()),
                $img->getClientOriginalName()
            );
        }

        $response = $request->post($this->baseUrl . "/muebles/{$productoId}/galeria");
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json;
    }

    public function eliminarImagen(int $productoId, int $galeriaId): array
    {
        $response = $this->conToken()->delete($this->baseUrl . "/muebles/{$productoId}/galeria/{$galeriaId}");
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json;
    }

    public function setPrincipalImagen(int $productoId, int $galeriaId): array
    {
        $response = $this->conToken()->post($this->baseUrl . "/muebles/{$productoId}/galeria/{$galeriaId}/principal");
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json;
    }

    public function reordenarGaleria(int $productoId, array $orden): array
    {
        $response = $this->conToken()->post($this->baseUrl . "/muebles/{$productoId}/galeria/reordenar", ['orden' => $orden]);
        $json = $response->json();
        if ($json === null) {
            $json = [];
        }

        return $json;
    }
}
