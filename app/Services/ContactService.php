<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ContactService
{
    public function __construct(
        protected ContactRepositoryInterface $contactRepository
    ) {}

    public function getAllContacts(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->contactRepository->getFiltered($filters, $perPage);
    }

    public function getContactById(int $id): ?Contact
    {
        return $this->contactRepository->findById($id, ['*'], ['client']);
    }

    public function getContactsByClientId(int $clientId): Collection
    {
        return $this->contactRepository->findByClientId($clientId);
    }

    public function createContact(array $data): Contact
    {
        if (! isset($data['status'])) {
            $data['status'] = true;
        }

        /** @var Contact $contact */
        $contact = $this->contactRepository->create($data);
        return $contact->load('client');
    }

    public function updateContact(int $id, array $data): ?Contact
    {
        /** @var Contact|null $contact */
        $contact = $this->contactRepository->update($id, $data);
        return $contact ? $contact->load('client') : null;
    }

    public function deleteContact(int $id): bool
    {
        return $this->contactRepository->delete($id);
    }
}
