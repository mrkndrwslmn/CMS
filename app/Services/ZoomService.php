<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ZoomService
{
    protected $client;
    protected $accountId;
    protected $clientId;
    protected $clientSecret;
    protected $apiUrl = 'https://api.zoom.us/v2';

    public function __construct()
    {
        $this->client = new Client();
        $this->accountId = config('services.zoom.account_id');
        $this->clientId = config('services.zoom.client_id');
        $this->clientSecret = config('services.zoom.client_secret');
    }

    /**
     * Get Server-to-Server OAuth access token
     */
    protected function getAccessToken(): ?string
    {
        try {
            // Cache the token for 55 minutes (tokens expire after 1 hour)
            return Cache::remember('zoom_access_token', 3300, function () {
                $response = $this->client->post('https://zoom.us/oauth/token', [
                    'headers' => [
                        'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                    ],
                    'form_params' => [
                        'grant_type' => 'account_credentials',
                        'account_id' => $this->accountId,
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);
                return $data['access_token'] ?? null;
            });
        } catch (GuzzleException $e) {
            Log::error('Zoom OAuth Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a Zoom meeting
     * 
     * @param string $topic Meeting title
     * @param Carbon $startTime Meeting start time
     * @param int $duration Duration in minutes
     * @param string|null $agenda Meeting agenda/description
     * @return array|null Meeting details including join_url and start_url
     */
    public function createMeeting(string $topic, Carbon $startTime, int $duration = 60, ?string $agenda = null): ?array
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            Log::error('Failed to get Zoom access token');
            return null;
        }

        try {
            $response = $this->client->post($this->apiUrl . '/users/me/meetings', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'topic' => $topic,
                    'type' => 2, // Scheduled meeting
                    'start_time' => $startTime->toIso8601String(),
                    'duration' => $duration,
                    'timezone' => config('app.timezone', 'UTC'),
                    'agenda' => $agenda ?? '',
                    'settings' => [
                        'host_video' => true,
                        'participant_video' => true,
                        'join_before_host' => false,
                        'mute_upon_entry' => true,
                        'watermark' => false,
                        'audio' => 'both',
                        'auto_recording' => 'none',
                        'waiting_room' => true,
                    ],
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return [
                'meeting_id' => $data['id'] ?? null,
                'join_url' => $data['join_url'] ?? null,
                'start_url' => $data['start_url'] ?? null,
                'password' => $data['password'] ?? null,
            ];
        } catch (GuzzleException $e) {
            Log::error('Zoom Create Meeting Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get meeting details
     * 
     * @param string $meetingId Zoom meeting ID
     * @return array|null Meeting details
     */
    public function getMeeting(string $meetingId): ?array
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            return null;
        }

        try {
            $response = $this->client->get($this->apiUrl . '/meetings/' . $meetingId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Zoom Get Meeting Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update a Zoom meeting
     * 
     * @param string $meetingId Zoom meeting ID
     * @param array $data Meeting data to update
     * @return bool Success status
     */
    public function updateMeeting(string $meetingId, array $data): bool
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            return false;
        }

        try {
            $this->client->patch($this->apiUrl . '/meetings/' . $meetingId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            return true;
        } catch (GuzzleException $e) {
            Log::error('Zoom Update Meeting Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a Zoom meeting
     * 
     * @param string $meetingId Zoom meeting ID
     * @return bool Success status
     */
    public function deleteMeeting(string $meetingId): bool
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            return false;
        }

        try {
            $this->client->delete($this->apiUrl . '/meetings/' . $meetingId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            return true;
        } catch (GuzzleException $e) {
            Log::error('Zoom Delete Meeting Error: ' . $e->getMessage());
            return false;
        }
    }
}
