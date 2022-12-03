<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/order')]
class OrderController extends AbstractController
{
    #[Route('/{id}', name: 'get_order', methods: ['GET'])]
    public function getOrder(OrderRepository $orderRepository, int $id): JsonResponse
    {
        $order = $orderRepository->find($id);

        if (empty($response)) {
            return $this->json(
                [
                    'message' => 'Order not found'
                ],
                Response::HTTP_OK
            );
        }

        return $this->json([
            'message' => 'Order found',
            'order' => $order
            ],
            Response::HTTP_OK
        );
    }

    //client id as query param
    #[Route('', name: 'get_orders', methods: ['GET'])]
    public function getOrders(OrderRepository $orderRepository, Request $request): JsonResponse
    {
        $clientId = $request->query->get('client_id');

        $orders = $orderRepository->findBy(['client' => $clientId]);

        if (empty($orders)) {
            return $this->json(
                [
                    'message' => 'No orders found'
                ],
                Response::HTTP_OK
            );
        }

        return $this->json([
                'message' => 'Orders found',
                'orders' => $orders,
            ],
            Response::HTTP_OK
        );
    }


}
