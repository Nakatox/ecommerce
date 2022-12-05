<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'get_products', methods: ['GET'])]
    public function getAllProducts(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {

        $product = $productRepository->findAll();

        if (empty($product)) {
            return $this->json(
                [
                    'message' => 'No products found'
                ],
                Response::HTTP_OK
            );
        }

        return $this->json([
                'message' => 'Products found',
                'products' => json_decode($serializer->serialize($product,'json' ,['groups' => 'product']))
            ],
            Response::HTTP_OK
        );
    }

    #[Route('/{id}', name: 'get_product', methods: ['GET'])]
    public function getProduct(ProductRepository $productRepository, SerializerInterface $serializer, int $id): JsonResponse
    {

        $product = $productRepository->find($id);

        if (empty($product)) {
            return $this->json(
                [
                    'message' => 'Product not found'
                ],
                404
            );
        }

        return $this->json([
            'message' => 'Product found',
            'product' => json_decode($serializer->serialize($product,'json' ,['groups' => ['product', 'product_category']]))
            ]
        );
    }

    #[Route('/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct(ProductRepository $productRepository, int $id): JsonResponse
    {
        $product = $productRepository->find($id);

        if (empty($product)) {
            return $this->json(
                [
                    'message' => 'No product found'
                ],
                404
            );
        }
        $productRepository->remove($product, true);

        return $this->json(
            [
                'message' => 'Product deleted',
            ],
            Response::HTTP_OK
        );
    }

    #[Route('/', name: 'create_product', methods: ['POST'])]
    public function createProduct(ProductRepository $productRepository, Request $request, SerializerInterface $serializer): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $product = new Product();

        $form = $this->createForm(
            ProductType::class,
            $product
        );

        $form->submit($data);
        if (!$form->isValid()){
            return $this->json(
                [
                    'message' => 'Invalid form',
                    'errors' => $form->getErrors(true)
                ],
                400
            );
        }

        $product->setSlug($data['name'] . '-' . uniqid());

        try{
            $productRepository->save($product, true);

            return $this->json(
                [
                    'message' => 'Product created',
                    'product' => json_decode($serializer->serialize($product,'json' ,['groups' => ['product', 'product_category']]))
                ],
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Product not created',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_BAD_GATEWAY
            );
        }

    }

    #[Route('/{id}', name: 'update_product', methods: ['PUT'])]
    public function updateProduct(ProductRepository $productRepository, Request $request, SerializerInterface $serializer, int $id): JsonResponse
    {
        $product = $productRepository->find($id);

        if (empty($product)) {
            return $this->json(
                [
                    'message' => 'No product found'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(
            ProductType::class,
            $product
        );

        $form->submit($data);
        if (!$form->isValid()){
            return $this->json(
                [
                    'message' => 'Invalid form',
                    'errors' => $form->getErrors(true)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $productRepository->save($product, true);

            return $this->json(
                [
                    'message' => 'Product updated',
                    'product' => json_decode($serializer->serialize($product,'json' ,['groups' => ['product', 'product_category']]))
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Error updating product',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_BAD_GATEWAY
            );
        }

    }
}
