<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        protected ContactService $contactService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = [
            'search' => $request->query('search'),
            'client_id' => $request->has('client_id') ? (int) $request->query('client_id') : null,
            'status' => $request->has('status') ? $request->query('status') : null,
        ];

        $contacts = $this->contactService->getAllContacts($filters, $perPage);

        return response()->json([
            'status' => 'success',
            'data' => $contacts,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $contact = $this->contactService->getContactById($id);

        if (! $contact) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contact not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $contact,
        ]);
    }

    public function byClient(int $clientId): JsonResponse
    {
        $contacts = $this->contactService->getContactsByClientId($clientId);

        return response()->json([
            'status' => 'success',
            'data' => $contacts,
        ]);
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        $contact = $this->contactService->createContact($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Contact created successfully.',
            'data' => $contact,
        ], 201);
    }

    public function update(UpdateContactRequest $request, int $id): JsonResponse
    {
        $contact = $this->contactService->updateContact($id, $request->validated());

        if (! $contact) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contact not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Contact updated successfully.',
            'data' => $contact,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->contactService->deleteContact($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contact not found or could not be deleted.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Contact deleted successfully.',
        ]);
    }
}
