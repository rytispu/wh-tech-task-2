<?php

declare(strict_types=1);

namespace WHInterviewTask;

use Exception;
use http\Exception\BadMethodCallException;
use http\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use WHInterviewTask\Utility\ApiKeyValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use WHInterviewTask\Controller\OrderController;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Psr\Log\LoggerInterface;

class App
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * @return void
     * @throws ResourceNotFoundException|Exception
     */
    public function run(): void
    {
        $routes = $this->getRoutes();

        $request = Request::createFromGlobals();
        if (!ApiKeyValidator::validate($request, $this->logger)) {
            $response = new JsonResponse(['error' => 'Unauthorized: Invalid API Key'], JsonResponse::HTTP_UNAUTHORIZED);
            $response->send();
            return;
        }

        $requestContext = new RequestContext();
        $requestContext->fromRequest($request);

        $matcher = new UrlMatcher($routes, $requestContext);

        try {
            $parameters = $matcher->match($request->getPathInfo());
            $controller = $parameters['_controller'];
        } catch (ResourceNotFoundException $e) {
            $this->logger->error("route not found: " . $e->getMessage());
            throw new Exception('Route not found' . $e->getMessage());
        }

        list($method, $controllerInstance) = $this->buildController($controller);

        $this->sendResponse(200, $controllerInstance->$method($request->request));
    }


    private function sendResponse($statusCode, $message) {
        http_response_code($statusCode);

        $result = json_encode($message);
        echo $result;
    }

    protected function getRoutes(): RouteCollection
    {
        $routes = new RouteCollection();

        $routes->add(
            'order_details',
            new Route(
                '/api/order/details',
                ['_controller' => [OrderController::class, 'get_order_details']],
                [], [], '', [], ['GET']
            )
        );

        $routes->add(
            'order_create',
            new Route(
                '/api/order',
                ['_controller' => [OrderController::class, 'create_order']],
                [], [], '', [], ['POST']
            )
        );

        return $routes;
    }

    /**
     * @param array $controller
     * @return array
     */
    protected function buildController(array $controller): array
    {
        [$class, $method] = $controller;
        if (!class_exists($class)) {
            throw new InvalidArgumentException("Controller class $class not found");
        }
        if (!method_exists($class, $method)) {
            throw new BadMethodCallException("Method $method not found in class $class");
        }

        $controllerInstance = new $class();
        return [$method, $controllerInstance];
    }

}
