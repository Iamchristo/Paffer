<?php

namespace App\Support;

trait WritesEnvFile
{
    /**
     * @param  array<string, string>  $values
     */
    private function writeEnv(array $values): void
    {
        $path = base_path('.env');
        $env = file_exists($path) ? file_get_contents($path) : '';

        foreach ($values as $key => $value) {
            $line = $key.'='.$this->formatEnvValue($value);

            $env = preg_match('/^'.$key.'=.*$/m', $env)
                ? preg_replace('/^'.$key.'=.*$/m', $line, $env, 1)
                : rtrim($env)."\n".$line."\n";
        }

        file_put_contents($path, $env);
    }

    private function formatEnvValue(string $value): string
    {
        return $value === '' || preg_match('/\s/', $value) ? '"'.$value.'"' : $value;
    }
}
