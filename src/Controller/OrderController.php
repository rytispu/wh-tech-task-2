<?php

declare(strict_types=1);

namespace WHInterviewTask\Controller;

use PDO;
use Symfony\Component\HttpFoundation\InputBag;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends Controller
{
    private PDO $db_connection;
    private LoggerInterface $logger;

    public function __construct(PDO $db_connection, LoggerInterface $logger)
    {
        $this->db_connection = $db_connection;
        $this->logger = $logger;
    }
    public function get_order_details(InputBag $args): JsonResponse
    {
        $order_id = $args->get('order_id');

        if (!$order_id) {
            return new JsonResponse(['error' => 'Invalid order ID'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            // Fetch order details
            $order_query = "SELECT * FROM myapp.orders WHERE id = :order_id";
            $order_stmt = $this->db_connection->prepare($order_query);
            $order_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $order_stmt->execute();
            $order = $order_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                return new JsonResponse(['error' => 'Order not found'], JsonResponse::HTTP_NOT_FOUND);
            }

            $customer_id = $order['customer_id'];

            // Fetch customer details
            $customer_query = "SELECT * FROM myapp.customers WHERE id = :customer_id";
            $customer_stmt = $this->db_connection->prepare($customer_query);
            $customer_stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
            $customer_stmt->execute();
            $customer = $customer_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$customer) {
                return new JsonResponse(['error' => 'Customer not found'], JsonResponse::HTTP_NOT_FOUND);
            }

            return new JsonResponse([
                'order_id' => $order['id'],
                'customer_name' => $customer['name'],
                'order_date' => $order['order_date'],
                'total_amount' => $order['total_amount']
            ], JsonResponse::HTTP_OK);

        } catch (\PDOException $e) {
            $this->logger->error("Database error: " . $e->getMessage());
            return new JsonResponse(['error' => 'An error occurred while fetching the order details'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a new order
     *
     * @param InputBag $args
     * @return int|false
     */
    public function create_order(InputBag $args) : JsonResponse
    {
        $customer_id = $args->get('customer_id');
        $total_amount = $args->get('total_amount');

        if (!$customer_id || !$total_amount) {
            return new JsonResponse(['error' => 'Missing parameters'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $query = "INSERT INTO myapp.orders (customer_id, total_amount) VALUES (:customer_id, :total_amount)";
            $stmt = $this->db_connection->prepare($query);
            $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
            $stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);

           $stmt->execute();

        } catch (\PDOException $e) {
            $this->logger->error("Database error: " . $e->getMessage());
            return new JsonResponse(['error' => 'An error occurred while creating the order'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'message' => 'Order created successfully',
            'order_id' => (int) $this->db_connection->lastInsertId()
        ], JsonResponse::HTTP_CREATED);
    }

    function calculate_order_total()
    {
        $query = "SELECT * FROM myapp.orders";
        $total = 0;
        $result = $this->get_db_connection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($result as $item) {
            if (isset($item['total_amount'])) {
                $total += $item['total_amount'];
            }
        }
        return $total;
    }

}
