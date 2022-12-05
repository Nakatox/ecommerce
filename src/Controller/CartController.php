<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderEntry;
use App\Repository\CartRepository;
use App\Repository\OrderEntryRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/cart')]
class CartController extends AbstractController
{
    #[Route('/{cart_id}/product/{product_id}/add-to-cart', name: 'add_to_cart', methods: ['PATCH'])]
    public function addToCart(CartRepository $cartRepository, ProductRepository $productRepository, SerializerInterface $serializer, int $cart_id, int $product_id): JsonResponse
    {
        $cart = $cartRepository->find($cart_id);
        $product = $productRepository->find($product_id);

        if (empty($cart) || empty($product)) {
            return $this->json(
                [
                    'message' => 'Cart or product not found'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($product->getQuantity() > 0) {
            $product->setQuantity($product->getQuantity() - 1);
            $cart->setTotalAmount($cart->getTotalAmount() + $product->getPrice());
            $cart->addProduct($product);
            $cart->setLastTimeUpdated(new \DateTime());

            try {

            $cartRepository->save($cart, true);
            $productRepository->save($product, true);

            return $this->json([
                    'message' => 'Product added to cart',
                    'cart' => json_decode($serializer->serialize($cart,'json' ,['groups' => ['cart_products', 'cart']]))
                ],
                Response::HTTP_OK
            );
            } catch (\Exception $e) {
                return $this->json(
                    [
                        'message' => 'Error adding product to cart',
                        'error' => $e->getMessage()
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        } else {
            return $this->json(
                [
                    'message' => 'Product out of stock'
                ],
                Response::HTTP_OK
            );
        }
    }


    #[Route('/{cart_id}/product/{product_id}/remove-from-cart', name: 'remove_from_cart', methods: ['PATCH'])]
    public function removeFromCart(CartRepository $cartRepository, ProductRepository $productRepository, SerializerInterface $serializer, int $cart_id, int $product_id): JsonResponse
    {
        $cart = $cartRepository->find($cart_id);
        $product = $productRepository->find($product_id);

        if (empty($cart) || empty($product)) {
            return $this->json(
                [
                    'message' => 'Cart or product not found'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $product->setQuantity($product->getQuantity() + 1);
        $cart->setTotalAmount($cart->getTotalAmount() - $product->getPrice());
        $cart->removeProduct($product);
        $cart->setLastTimeUpdated(new \DateTime());

        try {

            $cartRepository->save($cart, true);
            $productRepository->save($product, true);

            return $this->json([
                    'message' => 'Product removed from cart',
                    'cart' => json_decode($serializer->serialize($cart,'json' ,['groups' => ['cart_products', 'cart']]))
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Error removing product from cart'
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{cart_id}', name: 'delete_cart', methods: ['DELETE'])]
    public function deleteCart(CartRepository $cartRepository, int $cart_id): JsonResponse
    {
        $cart = $cartRepository->find($cart_id);

        if (empty($cart)) {
            return $this->json(
                [
                    'message' => 'Cart not found'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        try {
            $cart->setTotalAmount(0);
            $products = $cart->getProducts();
            foreach ($products as $product) {
                $cart->removeProduct($product);
            }
            $cart->setLastTimeUpdated(new \DateTime());
            $cartRepository->save($cart, true);

            return $this->json([
                'message' => 'Cart cleared',
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Error deleting cart'
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{cart_id}/validate', name: 'validate_cart', methods: ['PATCH'])]
    public function validateCart(CartRepository $cartRepository, OrderRepository $orderRepository, OrderEntryRepository $entryRepository, SerializerInterface $serializer, int $cart_id): JsonResponse
    {
        $cart = $cartRepository->find($cart_id);

        if (empty($cart)) {
            return $this->json(
                [
                    'message' => 'Cart not found'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $order = new Order();
        $order->setTotalAmount($cart->getTotalAmount());
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setNumber(uniqid());
        $address = $cart->getClient()->getAddresses()->first();
        $addressString = $address->getStreet() . ' ' . $address->getCity() . ' ' .$address->getPostalCode();
        $order->setAddressDelivery($addressString);
        $order->setAddressFacturation($addressString);
        $order->setClient($cart->getClient());

        foreach ($cart->getProducts() as $product) {
            $entry = new OrderEntry();
            $entry->setName($product->getName());
            $entry->setPrice($product->getPrice());
            $entry->setCategory($product->getCategory()->getName());
            $entry->setDescription($product->getDescription());
            $entry->setOrderRelate($order);
            $entryRepository->save($entry, false);
        }

        try {
            $cart->setTotalAmount(0);
            $products = $cart->getProducts();
            foreach ($products as $product) {
                $cart->removeProduct($product);
            }
            $cartRepository->save($cart, true);
            $orderRepository->save($order, true);

            return $this->json([
                'message' => 'Cart validated',
                'order' => json_decode($serializer->serialize($order,'json' ,['groups' => ['order_orderEntry', 'order']]))
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Error validating cart',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
