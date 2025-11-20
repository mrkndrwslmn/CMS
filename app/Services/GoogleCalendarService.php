<?php

namespace App\Services;

use App\Models\User;
use App\Models\AdiutorCalendarIntegration;
use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    protected GoogleClient $client;
    protected ?GoogleCalendar $service = null;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(config('services.google_calendar.client_id'));
        $this->client->setClientSecret(config('services.google_calendar.client_secret'));
        $this->client->setRedirectUri(config('services.google_calendar.redirect_uri'));
        
        // Add required scopes for calendar access
        $this->client->addScope(GoogleCalendar::CALENDAR);  // Full calendar access
        $this->client->addScope('https://www.googleapis.com/auth/calendar.events');
        
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');  // Force consent screen to show all scopes
        $this->client->setIncludeGrantedScopes(true);  // Include previously granted scopes
    }

    /**
     * Get the OAuth authorization URL
     */
    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Handle OAuth callback and save tokens
     */
    public function handleCallback(string $code, User $adiutor): AdiutorCalendarIntegration
    {
        try {
            // Exchange authorization code for access token
            $token = $this->client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                throw new \Exception('Error fetching access token: ' . $token['error']);
            }

            // Get calendar ID (primary calendar)
            $this->client->setAccessToken($token);
            $calendarService = new GoogleCalendar($this->client);
            $calendarList = $calendarService->calendarList->listCalendarList();
            $primaryCalendar = collect($calendarList->getItems())->firstWhere('primary', true);

            // Save or update integration
            $integration = AdiutorCalendarIntegration::updateOrCreate(
                ['adiutor_id' => $adiutor->id],
                [
                    'provider' => 'google',
                    'calendar_id' => $primaryCalendar ? $primaryCalendar->getId() : 'primary',
                    'access_token' => Crypt::encryptString($token['access_token']),
                    'refresh_token' => isset($token['refresh_token']) ? Crypt::encryptString($token['refresh_token']) : null,
                    'token_expires_at' => Carbon::now()->addSeconds($token['expires_in']),
                    'is_connected' => true,
                    'last_synced_at' => now(),
                    'sync_settings' => [
                        'timezone' => config('app.timezone'),
                        'working_hours' => [
                            'start' => '09:00:00',
                            'end' => '17:00:00',
                        ],
                    ],
                ]
            );

            Log::info('Google Calendar connected successfully', [
                'adiutor_id' => $adiutor->id,
                'calendar_id' => $integration->calendar_id,
            ]);

            return $integration;
        } catch (\Exception $e) {
            Log::error('Failed to connect Google Calendar', [
                'adiutor_id' => $adiutor->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Initialize Google Calendar service with user's tokens
     */
    protected function initializeService(User $adiutor): void
    {
        $integration = $adiutor->calendarIntegration;

        if (!$integration || !$integration->is_connected) {
            throw new \Exception('Calendar not connected');
        }

        // Check if token needs refresh
        if ($integration->token_expires_at && $integration->token_expires_at->isPast()) {
            $this->refreshAccessToken($integration);
            $integration->refresh();
        }

        $this->client->setAccessToken([
            'access_token' => Crypt::decryptString($integration->access_token),
            'refresh_token' => $integration->refresh_token ? Crypt::decryptString($integration->refresh_token) : null,
            'expires_in' => $integration->token_expires_at ? $integration->token_expires_at->diffInSeconds(now()) : 3600,
        ]);

        $this->service = new GoogleCalendar($this->client);
    }

    /**
     * Refresh access token using refresh token
     */
    protected function refreshAccessToken(AdiutorCalendarIntegration $integration): void
    {
        try {
            if (!$integration->refresh_token) {
                throw new \Exception('No refresh token available');
            }

            $refreshToken = Crypt::decryptString($integration->refresh_token);
            
            // Use the refresh token directly with the method
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);

            if (isset($newToken['error'])) {
                throw new \Exception('Error refreshing token: ' . $newToken['error']);
            }

            $integration->update([
                'access_token' => Crypt::encryptString($newToken['access_token']),
                'token_expires_at' => Carbon::now()->addSeconds($newToken['expires_in']),
            ]);

            Log::info('Access token refreshed', ['adiutor_id' => $integration->adiutor_id]);
        } catch (\Exception $e) {
            Log::error('Failed to refresh access token', [
                'adiutor_id' => $integration->adiutor_id,
                'error' => $e->getMessage(),
            ]);

            // Only mark as disconnected if it's a permanent error (not a temporary network issue)
            if (str_contains($e->getMessage(), 'invalid_grant') || str_contains($e->getMessage(), 'Token has been expired or revoked')) {
                $integration->update(['is_connected' => false]);
            }
            throw $e;
        }
    }

    /**
     * Get calendar events for a date range
     */
    public function getEvents(User $adiutor, Carbon $startDate, Carbon $endDate): array
    {
        $this->initializeService($adiutor);

        try {
            $optParams = [
                'timeMin' => $startDate->toRfc3339String(),
                'timeMax' => $endDate->toRfc3339String(),
                'singleEvents' => true,
                'orderBy' => 'startTime',
            ];

            $events = $this->service->events->listEvents('primary', $optParams);
            $items = [];

            foreach ($events->getItems() as $event) {
                $start = $event->getStart()->getDateTime() ?: $event->getStart()->getDate();
                $end = $event->getEnd()->getDateTime() ?: $event->getEnd()->getDate();

                $items[] = [
                    'id' => $event->getId(),
                    'title' => $event->getSummary() ?: '(No title)',
                    'start' => Carbon::parse($start),
                    'end' => Carbon::parse($end),
                    'is_all_day' => !$event->getStart()->getDateTime(),
                    'description' => $event->getDescription(),
                    'location' => $event->getLocation(),
                    'status' => $event->getStatus(),
                ];
            }

            return $items;
        } catch (\Exception $e) {
            Log::error('Failed to fetch calendar events', [
                'adiutor_id' => $adiutor->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a calendar event for a scheduled task
     */
    public function createTaskEvent(User $adiutor, array $taskData): string
    {
        $this->initializeService($adiutor);

        try {
            $event = new \Google\Service\Calendar\Event([
                'summary' => '[CMS] ' . $taskData['title'],
                'description' => $this->buildEventDescription($taskData),
                'start' => [
                    'dateTime' => $taskData['start']->toRfc3339String(),
                    'timeZone' => config('app.timezone'),
                ],
                'end' => [
                    'dateTime' => $taskData['end']->toRfc3339String(),
                    'timeZone' => config('app.timezone'),
                ],
                'colorId' => $this->getColorIdForPriority($taskData['priority'] ?? 'medium'),
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'popup', 'minutes' => 15],
                    ],
                ],
            ]);

            $createdEvent = $this->service->events->insert('primary', $event);

            Log::info('Calendar event created', [
                'adiutor_id' => $adiutor->id,
                'event_id' => $createdEvent->getId(),
                'task_title' => $taskData['title'],
            ]);

            return $createdEvent->getId();
        } catch (\Exception $e) {
            Log::error('Failed to create calendar event', [
                'adiutor_id' => $adiutor->id,
                'task_title' => $taskData['title'] ?? 'Unknown',
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing calendar event
     */
    public function updateTaskEvent(User $adiutor, string $eventId, array $taskData): void
    {
        $this->initializeService($adiutor);

        try {
            $event = $this->service->events->get('primary', $eventId);
            
            $event->setSummary('[CMS] ' . $taskData['title']);
            $event->setDescription($this->buildEventDescription($taskData));
            $event->setStart(new \Google\Service\Calendar\EventDateTime([
                'dateTime' => $taskData['start']->toRfc3339String(),
                'timeZone' => config('app.timezone'),
            ]));
            $event->setEnd(new \Google\Service\Calendar\EventDateTime([
                'dateTime' => $taskData['end']->toRfc3339String(),
                'timeZone' => config('app.timezone'),
            ]));
            $event->setColorId($this->getColorIdForPriority($taskData['priority'] ?? 'medium'));

            $this->service->events->update('primary', $eventId, $event);

            Log::info('Calendar event updated', [
                'adiutor_id' => $adiutor->id,
                'event_id' => $eventId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update calendar event', [
                'adiutor_id' => $adiutor->id,
                'event_id' => $eventId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a calendar event
     */
    public function deleteTaskEvent(User $adiutor, string $eventId): void
    {
        $this->initializeService($adiutor);

        try {
            $this->service->events->delete('primary', $eventId);

            Log::info('Calendar event deleted', [
                'adiutor_id' => $adiutor->id,
                'event_id' => $eventId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete calendar event', [
                'adiutor_id' => $adiutor->id,
                'event_id' => $eventId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Disconnect calendar integration
     */
    public function disconnect(User $adiutor): void
    {
        $integration = $adiutor->calendarIntegration;

        if ($integration) {
            $integration->update([
                'is_connected' => false,
                'access_token' => null,
                'refresh_token' => null,
                'token_expires_at' => null,
            ]);

            Log::info('Calendar disconnected', ['adiutor_id' => $adiutor->id]);
        }
    }

    /**
     * Build event description with task details
     */
    protected function buildEventDescription(array $taskData): string
    {
        $description = "Project: {$taskData['project']}\n";
        $description .= "Priority: " . ucfirst($taskData['priority'] ?? 'medium') . "\n";
        
        if (!empty($taskData['description'])) {
            $description .= "\nDescription:\n{$taskData['description']}\n";
        }
        
        if (!empty($taskData['url'])) {
            $description .= "\nView in CMS: {$taskData['url']}";
        }

        return $description;
    }

    /**
     * Get Google Calendar color ID based on priority
     */
    protected function getColorIdForPriority(string $priority): string
    {
        return match (strtolower($priority)) {
            'high', 'urgent' => '11', // Red
            'medium' => '5',           // Yellow
            'low' => '2',              // Green
            default => '9',            // Blue
        };
    }
}
