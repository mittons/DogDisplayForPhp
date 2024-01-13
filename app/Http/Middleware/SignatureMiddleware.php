<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\SignatureHelper;

class SignatureMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Get response data to sign
        $data = $response->getContent();

        // Generate signature
        $privateKeyPem = env('PEM_KEY'); // Load private key from env

        // Load the private key
        $privateKey = openssl_pkey_get_private($privateKeyPem);
        if (!$privateKey) {
            $signature = "Required environment variables are not set";

            // Add signature to response headers
            $response->headers->set('X-Custom-Signature-Header', $signature);
            return $response;
        }

        // Sign the data
        $signature = '';
        if (!openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {   
            $signature = "Error signing data";

            // Free the private key from memory
            openssl_free_key($privateKey);

            // Add signature to response headers
            $response->headers->set('X-Custom-Signature-Header', $signature);
            return $response;
        }

        // Free the private key from memory
        openssl_free_key($privateKey);

        // Add signature to response headers
        $response->headers->set('X-Custom-Signature-Header', base64_encode($signature));
        return $response;
    }
}