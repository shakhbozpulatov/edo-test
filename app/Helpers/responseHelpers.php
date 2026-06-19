<?php

use Illuminate\Http\JsonResponse;

function createdResponse($data = [], string $message = 'Created', $status = 'success'): JsonResponse
{
    return response()->json([
        'errorMessage' => $message,
        'status'       => $status,
        'data'         => $data,
    ], 201);
}

function okResponse($data = null, string $message = 'ok', $status = 'ok', array $meta_data = []): JsonResponse
{
    return response()->json(array_merge([
        'errorMessage' => $message,
        'status'       => $status,
        'data'         => $data,
    ], $meta_data));
}

function okWithPaginateResponse($data = []): JsonResponse
{
    if ($data instanceof \Illuminate\Http\Resources\Json\ResourceCollection) {
        return $data->toResponse(request());
    }

    return response()->json($data);
}

function badRequestResponse(string $message = 'Bad Request', $data = [], $status = 'bad_request'): JsonResponse
{
    return response()->json([
        'errorMessage' => $message,
        'status'       => $status,
        'data'         => $data,
    ], 400);
}

function invalidData(string $message = 'The given data was invalid.', $data = [], $status = 'invalid_data', array $extraData = []): JsonResponse
{
    return response()->json([
        'errorMessage' => $message,
        'status'       => $status,
        'data'         => $data,
        ...$extraData,
    ], 422);
}

function unauthorizedRequestResponse(string $message = 'Unauthorized', $data = [], $status = 'unauthorized'): JsonResponse
{
    return response()->json([
        'errorMessage' => $message,
        'status'       => $status,
        'data'         => $data,
    ], 401);
}

function forbiddenRequestResponse(string $message = 'Forbidden', $data = [], $status = 'forbidden'): JsonResponse
{
    return response()->json([
        'errorMessage' => $message,
        'status'       => $status,
        'data'         => $data,
    ], 403);
}

function notFoundResponse(string $message = 'Not Found', $data = [], $status = 'not_found'): JsonResponse
{
    return response()->json([
        'errorMessage' => $message,
        'status'       => $status,
        'data'         => $data,
    ], 404);
}
