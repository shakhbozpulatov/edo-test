<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse
{
    protected function success(mixed $data = null, string $message = 'OK', int $status = 200): JsonResponse
    {
        $payload = ['success' => true];

        if ($message !== 'OK') {
            $payload['message'] = $message;
        }

        if ($data instanceof JsonResource || $data instanceof ResourceCollection) {
            return $data->additional(['success' => true])->response()->setStatusCode($status);
        }

        $payload['data'] = $data;

        return response()->json($payload, $status);
    }

    protected function created(mixed $data = null, string $message = 'Created'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    protected function noContent(): JsonResponse
    {
        return response()->json(['success' => true], 204);
    }

    protected function error(string $message, int $status = 400, mixed $errors = null): JsonResponse
    {
        $payload = [
            'success'    => false,
            'statusCode' => $status,
            'message'    => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }

    protected function paginated(mixed $resource): JsonResponse
    {
        return $resource->additional(['success' => true])->response();
    }
}
