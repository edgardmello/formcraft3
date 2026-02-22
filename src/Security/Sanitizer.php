<?php

namespace FormCraft\Security;

/**
 * Security Sanitizer
 *
 * Centralizes sanitization, escaping, and HTTP request helpers.
 * Used progressively from legacy.php as we migrate each function.
 *
 * @since 3.9.13
 */
class Sanitizer
{
    /**
     * Verify a FormCraft nonce, die on failure.
     * Drop-in replacement for the repetitive pattern in legacy.php.
     */
    public static function verifyNonce(string $action = 'formcraft3_wpnonce'): void
    {
        $nonce = sanitize_text_field(wp_unslash($_REQUEST[$action] ?? ''));
        if (!wp_verify_nonce($nonce, $action)) {
            wp_send_json_error(['message' => __('Security check failed.', 'formcraft')], 403);
        }
    }

    /**
     * Sanitize an integer from request data.
     * Returns 0 if the value is not a valid integer.
     */
    public static function intFromRequest(string $key, string $source = 'POST'): int
    {
        $bag = strtoupper($source) === 'GET' ? $_GET : $_POST;
        return intval($bag[$key] ?? 0);
    }

    /**
     * Sanitize a text field from request data.
     */
    public static function textFromRequest(string $key, string $source = 'POST'): string
    {
        $bag = strtoupper($source) === 'GET' ? $_GET : $_POST;
        return sanitize_text_field(wp_unslash($bag[$key] ?? ''));
    }

    /**
     * Sanitize an email from request data.
     */
    public static function emailFromRequest(string $key, string $source = 'GET'): string
    {
        $bag = strtoupper($source) === 'GET' ? $_GET : $_POST;
        return sanitize_email(wp_unslash($bag[$key] ?? ''));
    }

    /**
     * Decode JSON stored in the database, replacing the unsafe stripcslashes() pattern.
     *
     * @param string|null $json The raw database value
     * @param bool        $assoc Return associative array (default true)
     * @return mixed
     */
    public static function decodeDbJson(?string $json, bool $assoc = true): mixed
    {
        if ($json === null || $json === '') {
            return $assoc ? [] : null;
        }
        return json_decode(wp_unslash($json), $assoc);
    }

    /**
     * Make a remote HTTP GET request using WordPress HTTP API.
     * Replaces the direct curl_init() usage in the codebase.
     *
     * @param string $url  The URL to request.
     * @param array  $args Optional WP_Http args.
     * @return array|WP_Error Response array or WP_Error.
     */
    public static function remoteGet(string $url, array $args = []): array|\WP_Error
    {
        $defaults = [
            'timeout'   => 15,
            'sslverify' => true,
        ];
        return wp_remote_get($url, array_merge($defaults, $args));
    }

    /**
     * Make a remote HTTP POST request using WordPress HTTP API.
     *
     * @param string $url  The URL to request.
     * @param array  $body POST body as key-value array.
     * @param array  $args Optional WP_Http args.
     * @return array|WP_Error Response array or WP_Error.
     */
    public static function remotePost(string $url, array $body = [], array $args = []): array|\WP_Error
    {
        $defaults = [
            'timeout'   => 15,
            'sslverify' => true,
            'body'      => $body,
        ];
        return wp_remote_post($url, array_merge($defaults, $args));
    }
}
