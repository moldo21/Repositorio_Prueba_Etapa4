<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
	title: 'API Usuarios - Tienda de Muebles',
	version: '1.0.0',
	description: 'API REST para autenticacion, perfil y gestion de usuarios'
)]
#[OA\Server(
	url: 'http://api_usuarios.test/api/v1',
	description: 'Servidor local'
)]
#[OA\SecurityScheme(
	securityScheme: 'bearerAuth',
	type: 'http',
	scheme: 'bearer',
	bearerFormat: 'Sanctum'
)]
class ApiInfo
{
}
