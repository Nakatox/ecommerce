<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/order')]
class OrderController extends AbstractController
{
    #[Route('/{id}', name: 'get_order', methods: ['GET'])]
    public function getOrder(OrderRepository $orderRepository, SerializerInterface $serializer, int $id): JsonResponse
    {
        $order = $orderRepository->find($id);

        if (empty($order)) {
            return $this->json(
                [
                    'message' => 'Order not found'
                ],
                Response::HTTP_OK
            );
        }

        return $this->json([
            'message' => 'Order found',
            'order' => json_decode($serializer->serialize($order,'json' ,['groups' => ['order', 'order_orderEntry']]))
            ],
            Response::HTTP_OK
        );
    }

    #[Route('', name: 'get_orders', methods: ['GET'])]
    public function getOrders(OrderRepository $orderRepository, Request $request, SerializerInterface $serializer): JsonResponse
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
                'orders' => json_decode($serializer->serialize($orders,'json' ,['groups' => ['order', 'order_orderEntry']])),
            ],
            Response::HTTP_OK
        );
    }


}
