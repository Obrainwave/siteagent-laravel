<?php

namespace ZuqoLab\SiteAgent;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class StateManager
{
    protected string $filePath;
    protected string $redisKey;

    public function __construct()
    {
        $this->filePath = storage_path('framework/siteagent_state.json');
        $this->redisKey = config('siteagent.redis.key_prefix', 'siteagent:state:') . config('siteagent.api_key');
    }

    /**
     * Get the current state of the site.
     */
    public function getState(): array
    {
        // 1. Try Redis First
        if (config('siteagent.redis.enabled')) {
            try {
                $redis = Redis::connection(config('siteagent.redis.connection', 'default'));
                $content = $redis->get($this->redisKey);
                if ($content) {
                    $data = json_decode($content, true);
                    if ($data && isset($data['status'])) {
                        return $data;
                    }
                }
            } catch (\Exception $e) {
                Log::warning('SiteAgent: Redis connection failed, falling back to file. ' . $e->getMessage());
            }
        }

        // 2. Fallback to File
        if (!File::exists($this->filePath)) {
            return $this->setState('suspended');
        }

        $content = File::get($this->filePath);
        $data = json_decode($content, true);

        if (!$data || !isset($data['status'])) {
            return $this->setState('suspended');
        }

        // Optional: Sync back to Redis if it was missing but file exists
        if (config('siteagent.redis.enabled')) {
             try {
                Redis::connection(config('siteagent.redis.connection', 'default'))
                    ->set($this->redisKey, json_encode($data));
            } catch (\Exception $e) {
                // Silently fail sync
            }
        }

        return $data;
    }

    /**
     * Update the site state.
     */
    public function setState(string $status): array
    {
        $data = [
            'status' => $status,
            'updated_at' => time(),
        ];

        $encoded = json_encode($data, JSON_PRETTY_PRINT);

        // 1. Always update File (Fallback base)
        try {
            File::ensureDirectoryExists(dirname($this->filePath));
            File::put($this->filePath, $encoded);
        } catch (\Exception $e) {
            Log::error('SiteAgent: Failed to save state to file: ' . $e->getMessage());
        }

        // 2. Update Redis (Primary)
        if (config('siteagent.redis.enabled')) {
            try {
                Redis::connection(config('siteagent.redis.connection', 'default'))
                    ->set($this->redisKey, $encoded);
            } catch (\Exception $e) {
                Log::error('SiteAgent: Failed to save state to Redis: ' . $e->getMessage());
            }
        }

        return $data;
    }

    /**
     * Check if the site is suspended.
     */
    public function isSuspended(): bool
    {
        $state = $this->getState();
        return $state['status'] === 'suspended';
    }
}
