<?php
namespace App\Services;

use App\Exceptions\ServiceException;
use App\Facades\Mailer;
use App\Facades\Qr;
use App\Facades\Pdf;
use App\Facades\FileUpload;
use App\Events\UploadUserPhotoEvent;
use App\Facades\SerExcept;
use App\Repository\ClientRepositoryInterface;

class ClientService implements ClientServiceInterface
{

    protected $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
    
    public function createClient(array $clientData, array $userData = null, $photo = null)
    {
        try {
            return $this->clientRepository->createClient($clientData, $userData);
        } catch (ServiceException $serexept) {
            throw new ServiceException('Failed to upload file: ' . $serexept->getMessage(), 'FileUploadService', 0, $serexept);
        }
    }

    public function getClientWithPhotoInBase64($id, $includeUser = false)
    {
        $client = $includeUser
            ? $this->clientRepository->getClientWithUser($id)
            : $this->clientRepository->findClientById($id);

        if ($client->photo) {
            $client->image_base64 = FileUpload::fileToBase64($client->photo);
        }
        return $client;
    }

    public function getClientWithUser($id)
    {
        return $this->clientRepository->findClientById($id);
    }

    public function delete($id)
    {
        return $this->clientRepository->delete($id);
    }
    public function getAllClient($request)
    {
        return $this->clientRepository->filter($request);
    }

    public function getClientWithDebts($clientId)
    {
        return $this->clientRepository->findClientWithDebts($clientId);
    }

    public function findByTelephone($telephone)
    {
        return $this->clientRepository->findByTelephone($telephone);
    }

    public function getClientWithDetails($id, $includeUser = false)
    {
        if ($includeUser === 'user') {
            return $this->clientRepository->getClientWithUser($id);
        }
        return $this->clientRepository->findClientById($id);
    }

    public function getUserByClient($id)
    {
        $client = $this->clientRepository->findClientById($id);
        if (!$client->user) {
            throw new ServiceException("This client doesn't have an associated user account.");
        }
        return $client->user;
    }
}
