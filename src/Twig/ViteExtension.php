<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'getViteAsset'], ['is_safe' => ['html']]),
        ];
    }

    public function getViteAsset(string $asset): string
    {
        // В режиме разработки используйте сервер Vite, иначе используйте сгенерированные файлы
        if ($_ENV['APP_ENV'] === 'dev') {
            return sprintf('http://localhost:3000/%s', $asset);
        }

        // Для продакшена используйте файл манифеста Vite
        $manifest = json_decode(file_get_contents(__DIR__ . '/../../public/build/manifest.json'), true);

        return sprintf('/build/%s', $manifest[$asset]['file']);
    }
}
