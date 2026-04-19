<?php

namespace ZuqoLab\SiteAgent\Security;

class HmacVerifier
{
    /**
     * Verify the HMAC signature.
     */
    public static function verify(string $rawBody, string $timestamp, string $secret, string $signature): bool
    {
        // 1. Check timestamp drift (5 minute window)
        if (abs(time() - (int) $timestamp) > 300) {
            return false;
        }

        // 2. Generate expected HMAC
        $expected = hash_hmac('sha256', $rawBody . $timestamp, $secret);

        // 3. Constant time comparison
        return hash_equals($expected, $signature);
    }
}
