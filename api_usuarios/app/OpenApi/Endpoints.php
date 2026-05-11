<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Autenticacion')]
#[OA\Tag(name: 'Usuarios')]
class Endpoints
{
	#[OA\Post(path: '/register', tags: ['Autenticacion'], summary: 'Registrar usuario', responses: [new OA\Response(response: 201, description: 'Creado')])]
	public function register(): void
	{
	}

	#[OA\Post(path: '/login', tags: ['Autenticacion'], summary: 'Iniciar sesion', responses: [new OA\Response(response: 200, description: 'OK')])]
	public function login(): void
	{
	}

	#[OA\Get(path: '/perfil', tags: ['Autenticacion'], summary: 'Ver perfil', security: [['bearerAuth' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
	public function perfil(): void
	{
	}

	#[OA\Get(path: '/token-abilities', tags: ['Autenticacion'], summary: 'Obtener abilities del token actual', security: [['bearerAuth' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
	public function tokenAbilities(): void
	{
	}

	#[OA\Post(path: '/logout', tags: ['Autenticacion'], summary: 'Cerrar sesion', security: [['bearerAuth' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
	public function logout(): void
	{
	}

	#[OA\Get(path: '/usuarios', tags: ['Usuarios'], summary: 'Listar usuarios', security: [['bearerAuth' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
	public function usuarios(): void
	{
	}

	#[OA\Get(
		path: '/usuarios/{id}',
		tags: ['Usuarios'],
		summary: 'Ver usuario',
		security: [['bearerAuth' => []]],
		parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function usuarioPorId(): void
	{
	}

	#[OA\Put(
		path: '/usuarios/{id}',
		tags: ['Usuarios'],
		summary: 'Actualizar usuario',
		security: [['bearerAuth' => []]],
		parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function actualizarUsuario(): void
	{
	}

	#[OA\Delete(
		path: '/usuarios/{id}',
		tags: ['Usuarios'],
		summary: 'Eliminar usuario',
		security: [['bearerAuth' => []]],
		parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function eliminarUsuario(): void
	{
	}
}
