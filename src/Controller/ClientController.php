<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\CartRepository;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'get_clients', methods: ['GET'])]
    public function getAllClients(ClientRepository $clientRepository): JsonResponse
    {
        $response = $clientRepository->findAll();

        if (empty($response)) {
            return $this->json(
                [
                    'message' => 'No clients found'
                ],
                Response::HTTP_OK
            );
        }

        return $this->json([
                'message' => 'Clients found',
                'clients' => $response,
            ],
            Response::HTTP_OK
        );
    }

    #[Route('/{id}', name: 'get_client', methods: ['GET'])]
    public function getClient(ClientRepository $clientRepository, int $id): JsonResponse
    {
        $response = $clientRepository->find($id);

        if (empty($response)) {
            return $this->json(
                [
                    'message' => 'Client not found'
                ],
                404
            );
        }

        return $this->json([
            'message' => 'Client found',
            'client' => $response
            ],
            Response::HTTP_OK
        );
    }

    #[Route('/{id}', name: 'delete_client', methods: ['DELETE'])]
    public function deleteClient(ClientRepository $clientRepository, int $id): JsonResponse
    {


        $client = $clientRepository->find($id);

        if (empty($client)) {
            return $this->json(
                [
                    'message' => 'Client not found'
                ],
                404
            );
        }

        try{
            $clientRepository->remove($client, true);
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Error deleting client'
                ],
                500
            );
        }
    }

    #[Route('/', name: 'add_client', methods: ['POST'])]
    public function addClient(Request $request, ClientRepository $clientRepository, CartRepository $cartRepository): JsonResponse
    {
        $client = new Client();
        $form = $this->createForm(
            ClientType::class,
            $client,
            ['method' => 'POST']
        );

        $parameters = json_decode($request->getContent(), true);
        $form->submit($parameters);
        if (!$form->isValid()) {
            return $this->json(
                [
                    'message' => 'Invalid data',
                    'errors' => $form->getErrors(true)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $clientRepository->save($client, true);

            $cart = new Cart();
            $cart->setTotalAmount(0);
            $cart->setClient($client);
            $cartRepository->save($cart, true);

            return $this->json(
                [
                    'message' => 'Client added',
                    'client' => [
                        'name' => $client->getFirstName(),
                        'email' => $client->getEmail(),
                    ]
                ],
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Error while saving client',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{id}', name: 'update_client', methods: ['PUT'])]
    public function updateClient(Request $request, ClientRepository $clientRepository, int $id): JsonResponse
    {
        $client = $clientRepository->find($id);

        if (empty($client)) {
            return $this->json(
                [
                    'message' => 'Client not found'
                ],
                404
            );
        }

        $form = $this->createForm(
            ClientType::class,
            $client,
            ['method' => 'PUT']
        );

        $parameters = json_decode($request->getContent(), true);
        $form->submit($parameters);
        if (!$form->isValid()) {
            return $this->json(
                [
                    'message' => 'Invalid data',
                    'errors' => $form->getErrors(true)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $clientRepository->save($client, true);

            return $this->json(
                [
                    'message' => 'Client updated',
                    'client' => [
                        'name' => $client->getFirstName(),
                        'lastname' => $client->getLastName(),
                        'birthdate' => $client->getBirthDate(),
                        'email' => $client->getEmail(),
                    ]
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Error while updating client',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{id}/cart', name: 'get_client_cart', methods: ['GET'])]
    public function getClientCart(ClientRepository $clientRepository, int $id): JsonResponse
    {
        $client = $clientRepository->find($id);

        if (empty($client)) {
            return $this->json(
                [
                    'message' => 'Client not found'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json([
            'message' => 'Client found',
            'cart' => $client->getCart()
            ],
            Response::HTTP_OK
        );
    }
}
