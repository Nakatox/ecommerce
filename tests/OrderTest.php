<?php

namespace App\Tests;

use App\Entity\Cart;
use App\Entity\Category;
use App\Entity\Client;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderTest extends WebTestCase
{
    public function testOrder(): void
    {
        $kernel = self::bootKernel();
        $em = $kernel->getContainer()->get('doctrine')->getManager();

        $clientRepository = $em->getRepository(Client::class);
        $categoryRepository = $em->getRepository(Category::class);
        $productRepository = $em->getRepository(Product::class);
        $cartRepository = $em->getRepository(Cart::class);

        $client = new Client();
        $client->setFirstName("Jean");
        $client->setLastName("Dupont");
        $client->setEmail("random@gmail.com");
        $client->setBirthDate(new \DateTime());

        $clientRepository->save($client);

        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName('Category ' . $i);

            $categoryRepository->save($category);
        }

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName('product ' . $i);
            $product->setQuantity(rand(1, 100));
            $product->setPrice(rand(1, 100));
            $product->setDescription('description ' . $i);
            $product->setSlug('name ' . $i . '-' . uniqid());

            $productRepository->save($product);
        }

        $cart = new Cart();
        $cart->setClient($client);
        $cart->setTotalAmount(0);
        $cart->setCreatedAt(new \DateTimeImmutable('-' . rand(1, 10) . ' days'));

        $cartRepository->save($cart, true);


        $call = static::createClient();
        $call->request('PATCH', 'http://127.0.0.1:8000/api/cart/'. $cart->getId() .'validate');
        $this->assertSame(200, $call->getResponse()->getStatusCode());

        $call->request('GET', 'http://127.0.0.1:8000/api/cart/'. $cart->getId());
        $this->assertSame(200, $call->getResponse()->getStatusCode());
        $this->assertJson($call->getResponse()->getContent());
        $this->assertJsonStringEqualsJsonString(
            '{
            "id":' . $cart->getId() . ',
            "totalAmount":' . $cart->getTotalAmount() . ',
            "createdAt":"' . $cart->getCreatedAt()->format('Y-m-d H:i:s') . '",
            "client":
                {
                    "id":' . $client->getId() . ',
                    "firstName":"' . $client->getFirstName() . '",
                    "lastName":"' . $client->getLastName() . '",
                    "email":"' . $client->getEmail() . '",
                    "birthDate":"' . $client->getBirthDate()->format('Y-m-d H:i:s') . '"
                },
            "orderEntries":' . $call->getResponse()->getContent() . '
            }',
            $call->getResponse()->getContent()
        );

    }

    public function productProvider(){

    }
}
