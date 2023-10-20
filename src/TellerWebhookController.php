<?php

namespace LeviZoesch\TellerSDK;

use App\Models\TellerAccount;
use App\Models\TellerWebhooks;
use Illuminate\Http\Request;
use JsonException;

class TellerWebhookController extends Controller
{

    public function __construct()
    {

    }

    /**
     * @throws JsonException
     */
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // Store Webhook
        TellerWebhooks::createWebhookRecord($payload);

        // Handle Webhook
        $found = TellerAccount::where('enrollmentId', $payload['payload']['enrollment_id'])->first();

        if ($found) {

            $status = match ($payload['payload']['reason']) {
                'disconnected' => 'Disconnected',
                'disconnected.account_locked' => 'Account Locked',
                'disconnected.enrollment_inactive' => 'Inactive',
                'disconnected.credentials_invalid' => 'Invalid Credentials',
                'disconnected.user_action.captcha_required' => 'Captcha Required',
                'disconnected.user_action.mfa_required' => 'MFA Required',
                'disconnected.user_action.web_login_required' => 'Login Required',
                default => 'Unknown',
            };

            TellerAccount::where('enrollmentId', $payload['payload']['enrollment_id'])->update([
                'status' => $status
            ]);
        }

        return $payload;
    }
}
