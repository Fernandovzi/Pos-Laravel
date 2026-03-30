<?php

if (! function_exists('mime_content_type')) {
    /**
     * Polyfill for environments where ext-fileinfo is not enabled.
     */
    function mime_content_type(string $filename): string|false
    {
        if (! is_file($filename) || ! is_readable($filename)) {
            return false;
        }

        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);

            if ($finfo !== false) {
                $mimeType = finfo_file($finfo, $filename);
                finfo_close($finfo);

                if ($mimeType !== false) {
                    return $mimeType;
                }
            }
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return match ($extension) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            default => 'application/octet-stream',
        };
    }
}
