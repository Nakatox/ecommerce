<?php

namespace App\Tests;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\OrderEntry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderTestEntity extends KernelTestCase
{
    public function testOrders(): void
    {
        $order = new Order();

        $order->setTotalAmount(1000);
        $date = new \DateTime();
        $order->setCreatedAt($date);
        $order->setNumber("id");
        $order->setAddressDelivery("4 rue des raviolis");
        $order->setAddressFacturation("4 rue des raviolis");

        for ($i = 0; $i < 10; $i++) {
            $orderEntry = new OrderEntry();
            $orderEntry->setCategory("category");
            $orderEntry->setPrice(rand(1, 100));
            $orderEntry->setDescription('description ' . $i);
            $orderEntry->setOrderRelate($order);
        }

        $client = new Client();
        $client->setFirstName("Jean");
        $client->setLastName("Dupont");
        $client->setEmail("random@gmail.com");
        $client->setBirthDate(new \DateTime());

        $order->setClient($client);

        $this->assertEquals(1000, $order->getTotalAmount());
        $this->assertEquals($date, $order->getCreatedAt());
        $this->assertEquals("id", $order->getNumber());
        $this->assertEquals("4 rue des raviolis", $order->getAddressDelivery());
        $this->assertEquals("4 rue des raviolis", $order->getAddressFacturation());
        $this->assertEquals($client, $order->getClient());

        $orderEntry = $order->getOrderEntry()[0];
        $this->assertEquals("category", $orderEntry->getCategory());
        $this->assertEquals(1, $orderEntry->getPrice());
        $this->assertEquals('description 0', $orderEntry->getDescription());
        $this->assertEquals($order, $orderEntry->getOrderRelate());

    }
}
