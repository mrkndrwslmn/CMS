<?php

namespace App\Services;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    protected $client;
    protected $baseUrl;
    protected $apiKey;
    protected $serviceKey;

    public function __construct()
    {
        $this->baseUrl = config('supabase.url');
        $this->apiKey = config('supabase.key');
        $this->serviceKey = config('supabase.service_key');
        
        $this->client = new HttpClient([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
        ]);
    }

    /**
     * Execute a query on Supabase
     */
    public function query($table, $params = [])
    {
        try {
            $response = $this->client->get("/rest/v1/{$table}", [
                'query' => $params
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Supabase query error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Insert data into Supabase table
     */
    public function insert($table, $data)
    {
        try {
            $response = $this->client->post("/rest/v1/{$table}", [
                'json' => $data
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Supabase insert error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update data in Supabase table
     */
    public function update($table, $data, $conditions = [])
    {
        try {
            $queryString = '';
            if (!empty($conditions)) {
                $queryString = '?' . http_build_query($conditions);
            }

            $response = $this->client->patch("/rest/v1/{$table}{$queryString}", [
                'json' => $data
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Supabase update error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete data from Supabase table
     */
    public function delete($table, $conditions = [])
    {
        try {
            $queryString = '';
            if (!empty($conditions)) {
                $queryString = '?' . http_build_query($conditions);
            }

            $response = $this->client->delete("/rest/v1/{$table}{$queryString}");

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Supabase delete error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Authenticate user with Supabase Auth
     */
    public function auth($email, $password)
    {
        try {
            $response = $this->client->post('/auth/v1/token?grant_type=password', [
                'json' => [
                    'email' => $email,
                    'password' => $password
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Supabase auth error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Upload file to Supabase Storage
     */
    public function uploadFile($bucket, $path, $file)
    {
        try {
            $response = $this->client->post("/storage/v1/object/{$bucket}/{$path}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                ],
                'body' => $file
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Supabase storage error: ' . $e->getMessage());
            throw $e;
        }
    }
}