<?php

namespace App\Support;

class Seo
{
    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    public static function page(string $title, string $description, ?string $canonicalPath = null, array $extra = []): array
    {
        $path = $canonicalPath ?? '/'.ltrim((string) request()->path(), '/');

        return array_merge([
            'title' => $title,
            'description' => $description,
            'canonicalPath' => $path === '' ? '/' : $path,
        ], $extra);
    }
}
