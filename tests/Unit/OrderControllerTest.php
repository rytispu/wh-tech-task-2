<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\InputBag;
use WHInterviewTask\Controller\OrderController;

class OrderControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $dotenv = Dotenv\Dotenv::createUnsafeImmutable('.');
        $dotenv->load();
    }

    public function test_order_creation()
    {
        $controller = new OrderController();

        $response = $controller->create_order(
            new InputBag(['total_amount' => 10, 'customer_id' => 1])
        );

        $this->assertTrue($response > 0);
    }

    public function test_order_details()
    {
        $controller = new OrderController();

        $response = $controller->get_order_details(new InputBag(['order_id' => 1]));

        $this->assertNotEmpty($response);
    }
}
