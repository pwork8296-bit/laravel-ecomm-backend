<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(
        protected ClientService $clientService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = [
            'search' => $request->query('search'),
            'status' => $request->has('status') ? $request->query('status') : null,
        ];

        $clients = $this->clientService->getAllClients($filters, $perPage);

        return response()->json([
            'status' => 'success',
            'data' => $clients,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $client = $this->clientService->getClientById($id);

        if (! $client) {
            return response()->json([
                'status' => 'error',
                'message' => 'Client not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $client,
        ]);
    }

    public function showByDomain(string $domain): JsonResponse
    {
        $client = $this->clientService->getClientByDomain($domain);

        if (! $client) {
            return response()->json([
                'status' => 'error',
                'message' => "Client with domain '{$domain}' not found.",
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $client,
        ]);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = $this->clientService->createClient($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Client created successfully.',
            'data' => $client,
        ], 201);
    }

    public function update(UpdateClientRequest $request, int $id): JsonResponse
    {
        $client = $this->clientService->updateClient($id, $request->validated());

        if (! $client) {
            return response()->json([
                'status' => 'error',
                'message' => 'Client not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Client updated successfully.',
            'data' => $client,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->clientService->deleteClient($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Client not found or could not be deleted.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Client deleted successfully.',
        ]);
    }
}
