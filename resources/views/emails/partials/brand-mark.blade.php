@php
    $w = (int) ($width ?? 44);
    $h = (int) ($height ?? 44);
    $logoHref = filled(config('fevourd.brand.logo_url'))
        ? (string) config('fevourd.brand.logo_url')
        : asset(config('fevourd.brand.logo_public_path'));
@endphp
<img
    src="{{ $logoHref }}"
    alt="FEVOURD-K Logo"
    width="{{ $w }}"
    height="{{ $h }}"
    style="display:block;border:0;outline:none;text-decoration:none;background:#ffffff;border-radius:8px;padding:4px;"
/>
