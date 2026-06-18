<?php

// Self-healing bootstrap for hosts (e.g. cPanel) where the app is uploaded
// and visited for the first time without ever running an artisan command
// from a shell. Without this, the very first request would crash before
// the installer could even render: Laravel's default web middleware
// resolves the encrypter (for cookie encryption) on every request, which
// throws if APP_KEY is missing.
$basePath = dirname(__DIR__);
$envPath = $basePath.'/.env';

if (! file_exists($envPath) && file_exists($basePath.'/.env.example')) {
    copy($basePath.'/.env.example', $envPath);
}

if (file_exists($envPath)) {
    $env = file_get_contents($envPath);

    if (! preg_match('/^APP_KEY=.+$/m', $env)) {
        $key = 'base64:'.base64_encode(random_bytes(32));

        $env = preg_match('/^APP_KEY=.*$/m', $env)
            ? preg_replace('/^APP_KEY=.*$/m', 'APP_KEY='.$key, $env, 1)
            : rtrim($env)."\nAPP_KEY={$key}\n";

        file_put_contents($envPath, $env);
    }
}
