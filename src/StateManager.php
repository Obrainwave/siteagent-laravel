<?php

namespace ZuqoLab\SiteAgent;

use Illuminate\Support\Facades\File;

class StateManager
{
    protected string $filePath;

    public function __construct()
    {
        $this->filePath = storage_path('framework/siteagent_state.json');
    }

    /**
     * Get the current state of the site.
     */
    public function getState(): array
    {
        if (!File::exists($this->filePath)) {
            $this->setState('suspended');
        }

        $content = File::get($this->filePath);
        $data = json_decode($content, true);

        if (!$data || !isset($data['status'])) {
            return $this->setState('suspended');
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

        File::ensureDirectoryExists(dirname($this->filePath));
        File::put($this->filePath, json_encode($data, JSON_PRETTY_PRINT));

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
