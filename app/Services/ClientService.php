<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\Contracts\ClientRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClientService
{
    public function __construct(
        protected ClientRepositoryInterface $clientRepository
    ) {}

    public function getAllClients(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->clientRepository->getFiltered($filters, $perPage);
    }

    public function getClientById(int $id): ?Client
    {
        return $this->clientRepository->findById($id, ['*'], ['blogs', 'contacts']);
    }

    public function getClientByDomain(string $domain): ?Client
    {
        return $this->clientRepository->findByDomain($domain);
    }

    public function createClient(array $data): Client
    {
        if (! isset($data['status'])) {
            $data['status'] = true;
        }

        /** @var Client $client */
        $client = $this->clientRepository->create($data);
        return $client;
    }

    public function updateClient(int $id, array $data): ?Client
    {
        /** @var Client|null $client */
        $client = $this->clientRepository->update($id, $data);
        return $client;
    }

    public function deleteClient(int $id): bool
    {
        return $this->clientRepository->delete($id);
    }
}
