<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Muebles')]
#[OA\Tag(name: 'Categorias')]
#[OA\Tag(name: 'Galeria')]
class Endpoints
{
	#[OA\Get(path: '/muebles', tags: ['Muebles'], summary: 'Listar muebles', responses: [new OA\Response(response: 200, description: 'OK')])]
	public function listarMuebles(): void
	{
	}

	#[OA\Get(
		path: '/muebles/{id}',
		tags: ['Muebles'],
		summary: 'Detalle mueble',
		parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function detalleMueble(): void
	{
	}

	#[OA\Post(path: '/muebles', tags: ['Muebles'], summary: 'Crear mueble', security: [['bearerAuth' => []]], responses: [new OA\Response(response: 201, description: 'Creado')])]
	public function crearMueble(): void
	{
	}

	#[OA\Put(
		path: '/muebles/{id}',
		tags: ['Muebles'],
		summary: 'Editar mueble',
		security: [['bearerAuth' => []]],
		parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function editarMueble(): void
	{
	}

	#[OA\Delete(
		path: '/muebles/{id}',
		tags: ['Muebles'],
		summary: 'Eliminar mueble',
		security: [['bearerAuth' => []]],
		parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function eliminarMueble(): void
	{
	}

	#[OA\Get(path: '/categorias', tags: ['Categorias'], summary: 'Listar categorias', responses: [new OA\Response(response: 200, description: 'OK')])]
	public function listarCategorias(): void
	{
	}

	#[OA\Get(
		path: '/categorias/{id}',
		tags: ['Categorias'],
		summary: 'Detalle categoria',
		parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function detalleCategoria(): void
	{
	}

	#[OA\Post(path: '/categorias', tags: ['Categorias'], summary: 'Crear categoria', security: [['bearerAuth' => []]], responses: [new OA\Response(response: 201, description: 'Creado')])]
	public function crearCategoria(): void
	{
	}

	#[OA\Put(
		path: '/categorias/{id}',
		tags: ['Categorias'],
		summary: 'Editar categoria',
		security: [['bearerAuth' => []]],
		parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function editarCategoria(): void
	{
	}

	#[OA\Delete(
		path: '/categorias/{id}',
		tags: ['Categorias'],
		summary: 'Eliminar categoria',
		security: [['bearerAuth' => []]],
		parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function eliminarCategoria(): void
	{
	}

	#[OA\Post(
		path: '/muebles/{productoId}/galeria',
		tags: ['Galeria'],
		summary: 'Subir imagenes a galeria',
		security: [['bearerAuth' => []]],
		parameters: [new OA\Parameter(name: 'productoId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function subirGaleria(): void
	{
	}

	#[OA\Delete(
		path: '/muebles/{productoId}/galeria/{galeriaId}',
		tags: ['Galeria'],
		summary: 'Eliminar imagen de galeria',
		security: [['bearerAuth' => []]],
		parameters: [
			new OA\Parameter(name: 'productoId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
			new OA\Parameter(name: 'galeriaId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
		],
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function eliminarGaleria(): void
	{
	}

	#[OA\Post(
		path: '/muebles/{productoId}/galeria/{galeriaId}/principal',
		tags: ['Galeria'],
		summary: 'Marcar imagen principal de galeria',
		security: [['bearerAuth' => []]],
		parameters: [
			new OA\Parameter(name: 'productoId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
			new OA\Parameter(name: 'galeriaId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
		],
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function principalGaleria(): void
	{
	}

	#[OA\Post(
		path: '/muebles/{productoId}/galeria/reordenar',
		tags: ['Galeria'],
		summary: 'Reordenar galeria del producto',
		security: [['bearerAuth' => []]],
		parameters: [
			new OA\Parameter(name: 'productoId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
		],
		requestBody: new OA\RequestBody(
			required: true,
			content: new OA\JsonContent(
				properties: [
					new OA\Property(property: 'orden', type: 'array', items: new OA\Items(type: 'integer')),
				]
			)
		),
		responses: [new OA\Response(response: 200, description: 'OK')]
	)]
	public function reordenarGaleria(): void
	{
	}
}
