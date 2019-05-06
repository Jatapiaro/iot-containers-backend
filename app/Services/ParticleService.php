<?php
namespace App\Services;

use GuzzleHttp\Client;

class ParticleService {

    /**
     * Mirrors the created user in the Particle Cloud
     */
    public function mirror($email) {
        $client = new Client();
        $productUrl = env('PARTICLE_PHOTON_PRODUCT_URL') . 'customers';
        $clientId = env('PARTICLE_PHOTON_PRODUCT_ID');
        $clientSecret = env('PARTICLE_PHOTON_PRODUCT_SECRET');
        $response = $client->post($productUrl, [
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => [
                "client_id" => $clientId,
                "client_secret" => $clientSecret,
                "email" => $email,
                "no_password" => true
            ],
            'http_errors' => false
        ]);
        $dataDetails = json_decode((string) $response->getBody(), true);
    }

}
