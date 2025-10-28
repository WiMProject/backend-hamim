<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;

class FirebaseService
{
    private $client;
    private $projectId;

    public function __construct()
    {
        $this->client = new Client();
        $this->projectId = env('FIREBASE_PROJECT_ID');
    }

    public function verifyIdToken($idToken)
    {
        try {
            // Get Firebase public keys
            $response = $this->client->get('https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com');
            $keys = json_decode($response->getBody(), true);

            // Decode token header to get key ID
            $tokenParts = explode('.', $idToken);
            $header = json_decode(base64_decode($tokenParts[0]), true);
            $keyId = $header['kid'];

            if (!isset($keys[$keyId])) {
                throw new \Exception('Invalid key ID');
            }

            // Verify and decode token
            $publicKey = $keys[$keyId];
            $decoded = JWT::decode($idToken, new Key($publicKey, 'RS256'));

            // Validate claims
            if ($decoded->aud !== $this->projectId) {
                throw new \Exception('Invalid audience');
            }

            if ($decoded->iss !== 'https://securetoken.google.com/' . $this->projectId) {
                throw new \Exception('Invalid issuer');
            }

            if ($decoded->exp < time()) {
                throw new \Exception('Token expired');
            }

            return [
                'uid' => $decoded->sub,
                'email' => $decoded->email ?? null,
                'name' => $decoded->name ?? null,
                'picture' => $decoded->picture ?? null,
                'email_verified' => $decoded->email_verified ?? false
            ];

        } catch (\Exception $e) {
            throw new \Exception('Invalid Firebase token: ' . $e->getMessage());
        }
    }
}