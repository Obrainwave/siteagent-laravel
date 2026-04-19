<?php

namespace ZuqoLab\SiteAgent\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ZuqoLab\SiteAgent\StateManager;
use ZuqoLab\SiteAgent\Security\HmacVerifier;

class SiteControlController extends Controller
{
    public function __construct(protected StateManager $stateManager)
    {
    }

    /**
     * Handle the control command from the central server.
     */
    public function handle(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');
        $signature = $request->header('X-SIGNATURE');
        $timestamp = $request->header('X-TIMESTAMP');

        // 1. Validate headers
        if (!$apiKey || !$signature || !$timestamp) {
            return response()->json(['message' => 'Missing security headers'], 403);
        }

        // 2. Validate API Key
        if ($apiKey !== config('siteagent.api_key')) {
            return response()->json(['message' => 'Invalid API Key'], 403);
        }

        // 3. Verify Signature
        $rawBody = $request->getContent();
        $secret = config('siteagent.secret');

        if (!HmacVerifier::verify($rawBody, $timestamp, $secret, $signature)) {
            return response()->json(['message' => 'Security signature mismatch'], 403);
        }

        // 4. Process Command
        $data = $request->json()->all();
        $action = $data['action'] ?? null;
        $commandId = $data['command_id'] ?? null;

        if (!$action || !$commandId) {
            return response()->json(['message' => 'Incomplete command payload'], 400);
        }

        // 5. Update State (Idempotent)
        try {
            if ($action === 'suspend') {
                $this->stateManager->setState('suspended');
            } elseif ($action === 'activate') {
                $this->stateManager->setState('active');
            } else {
                return response()->json(['message' => 'Unknown action'], 400);
            }

            return response()->json([
                'status' => 'success',
                'command_id' => $commandId
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SiteAgent State Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update site state. Check storage permissions.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
