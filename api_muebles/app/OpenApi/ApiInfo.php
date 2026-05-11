<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
	title: 'API Muebles - Tienda de Muebles',
	version: '1.0.0',
	description: 'API REST para catalogo, categorias y galeria de muebles'
)]
#[OA\Server(
	url: 'http://api_muebles.test/api/v1',
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
