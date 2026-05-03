<?php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function app_url(string $path = ''): string
{
    return absolute_url($path);
}

function site_base_url(): string
{
    $config = $GLOBALS['config'] ?? [];
    $baseUrl = trim((string) ($config['site']['base_url'] ?? ''));

    if ($baseUrl !== '') {
        return rtrim($baseUrl, '/');
    }

    $host = (string) ($_SERVER['HTTP_HOST'] ?? '');
    if ($host === '') {
        return '';
    }

    $https = (string) ($_SERVER['HTTPS'] ?? '');
    $forwardedProto = (string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '');
    $isSecure = $https === 'on' || $https === '1' || strtolower($forwardedProto) === 'https';
    $scheme = $isSecure ? 'https' : 'http';

    return $scheme . '://' . $host;
}

function absolute_url(string $path = ''): string
{
    if ($path === '') {
        return site_base_url() !== '' ? site_base_url() . '/' : '/';
    }

    if (preg_match('/^https?:\/\//i', $path) === 1) {
        return $path;
    }

    $normalizedPath = '/' . ltrim($path, '/');
    $baseUrl = site_base_url();

    if ($baseUrl === '') {
        return $normalizedPath;
    }

    return $baseUrl . $normalizedPath;
}

function nav_items(): array
{
    return [
        'home' => ['label' => 'Home', 'url' => 'index.php'],
        'about' => ['label' => 'About Us', 'url' => 'about.php'],
        'outbound' => ['label' => 'Outbound Packages', 'url' => 'outbound-packages.php'],
        'inbound' => ['label' => 'Inbound Packages', 'url' => 'inbound-packages.php'],
        'contact' => ['label' => 'Contact Us', 'url' => 'contact.php'],
    ];
}

function active_banners(array $config): array
{
    return array_values(array_filter(
        $config['banners'],
        static fn(array $banner): bool => (int) ($banner['status'] ?? 0) === 1
    ));
}

function active_packages(array $config, ?string $type = null, ?bool $popularOnly = null): array
{
    $packages = array_filter($config['packages'], static function (array $package) use ($type, $popularOnly): bool {
        if ((int) ($package['status'] ?? 0) !== 1) {
            return false;
        }

        if ($type !== null && ($package['type'] ?? '') !== $type) {
            return false;
        }

        if ($popularOnly !== null && (bool) ($package['is_popular'] ?? false) !== $popularOnly) {
            return false;
        }

        return true;
    });

    return array_values($packages);
}

function package_by_slug(array $config, string $slug): ?array
{
    foreach ($config['packages'] as $package) {
        if (($package['slug'] ?? '') === $slug && (int) ($package['status'] ?? 0) === 1) {
            return $package;
        }
    }

    return null;
}

function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_input(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(?string $token): bool
{
    if (!is_string($token) || !isset($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function flash_get(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function old_set(array $data): void
{
    $_SESSION['old'] = $data;
}

function old_clear(): void
{
    unset($_SESSION['old']);
}

function old_value(string $key, string $default = ''): string
{
    return (string) ($_SESSION['old'][$key] ?? $default);
}

function redirect_to(string $url): void
{
    header('Location: ' . $url);
    exit;
}
