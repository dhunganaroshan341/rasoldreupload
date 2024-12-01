<?php

namespace App\Helpers;

class MenuHelper
{
    /**
     * Check if the given URL should be active based on the current URL.
     *
     * @param  string|null  $url  The URL to check against (relative to the base).
     * @param  bool  $force  Whether to force the active state.
     * @return string Returns 'active' if active, otherwise an empty string.
     */
    public static function makeActive(?string $url = null, bool $force = false): string
    {
        // If forced, always return active
        if ($force) {
            return 'active';
        }

        // If a full URL is passed, use it directly
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $url = parse_url($url, PHP_URL_PATH);
        }

        // Normalize the provided URL and current request URL
        $trimmedUrl = trim(parse_url($url, PHP_URL_PATH), '/');
        $currentPath = trim(request()->path(), '/');

        // Check if the current URL matches the provided URL or is a wildcard match
        return $url && ($currentPath === $trimmedUrl || request()->is($trimmedUrl.'*')) ? 'active' : '';
    }
}
