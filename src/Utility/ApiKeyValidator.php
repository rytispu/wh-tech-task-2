<?php

namespace WHInterviewTask\Utility;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiKeyValidator
{
    public static function validate(Request $request, LoggerInterface $logger): bool
    {
        $apiKey = getenv('API_KEY');
        $providedApiKey = $request->headers->get('X-API-KEY');

        if ($providedApiKey !== $apiKey) {
            $logger->error('Invalid API Key provided.');
            return false;
        }

        return true;
    }
}
