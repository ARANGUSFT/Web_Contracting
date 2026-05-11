<?php
// app/Services/PushNotificationService.php

namespace App\Services;

use App\Models\FcmToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    private string $fcmUrl = 'https://fcm.googleapis.com/v1/projects/contracting-alliance-inc/messages:send';

    /**
     * Enviar notificación push a un subcontratista
     */
    public function sendToSubcontractor(
        int    $subcontractorId,
        string $title,
        string $body,
        array  $data = []
    ): void {
        $tokens = FcmToken::where('subcontractor_id', $subcontractorId)
            ->pluck('token');

        if ($tokens->isEmpty()) return;

        foreach ($tokens as $token) {
            $this->send($token, $title, $body, $data);
        }
    }

    private function send(string $token, string $title, string $body, array $data): void
    {
        try {
            $accessToken = $this->getAccessToken();

            Http::withToken($accessToken)
                ->post($this->fcmUrl, [
                    'message' => [
                        'token'        => $token,
                        'notification' => [
                            'title' => $title,
                            'body'  => $body,
                        ],
                        'data'         => array_map('strval', $data),
                        'android'      => [
                            'priority' => 'high',
                            'notification' => [
                                'sound'        => 'default',
                                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            ]
                        ],
                        'apns' => [
                            'payload' => [
                                'aps' => [
                                    'sound' => 'default',
                                    'badge' => 1,
                                ]
                            ]
                        ]
                    ]
                ]);
        } catch (\Exception $e) {
            Log::error('FCM send error: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene el access token de Google usando la service account
     * Requiere: composer require google/auth
     * Y el archivo: storage/app/firebase-service-account.json
     */
    private function getAccessToken(): string
    {
        $credentialsPath = storage_path('app/firebase-service-account.json');

        $credentials = new \Google\Auth\Credentials\ServiceAccountCredentials(
            'https://www.googleapis.com/auth/firebase.messaging',
            json_decode(file_get_contents($credentialsPath), true)
        );

        $token = $credentials->fetchAuthToken();
        return $token['access_token'];
    }
}