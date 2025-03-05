<?php namespace Spoob\UnotisLaravel\Serializers;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class RequestSerializer
{
    public function serialize(SymfonyRequest $request): string
    {
        $data = [
            'method' => $request->getMethod(),
            'uri' => $request->getRequestUri(),
            'query' => $request->query->all(),       // Параметры GET
            'request' => $request->request->all(),   // Параметры POST
            'attributes' => $request->attributes->all(),
            'cookies' => $request->cookies->all(),
            'files' => array_map(function ($file) {
                return $file ? [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType(),
                ] : null;
            }, $request->files->all()),
            'server' => $request->server->all(),
            'headers' => $request->headers->all(),
        ];

        return serialize($data);
    }

    public function unserialize(string $serializedData): SymfonyRequest
    {
        $data = unserialize($serializedData);

        $request = SymfonyRequest::create(
            $data['uri'],                     // URI
            $data['method'],                  // HTTP метод
            $data['request'] ?? [],           // POST параметры
            $data['cookies'] ?? [],           // Cookies
            $data['files'] ?? [],             // Файлы
            $data['server'] ?? [],            // Server переменные
            ''                                // Content (пустой, если не используется)
        );

        foreach ($data['headers'] as $header => $values) {
            foreach ($values as $value) {
                $request->headers->set($header, $value);
            }
        }

        foreach ($data['query'] ?? [] as $key => $value) {
            $request->query->set($key, $value);
        }

        foreach ($data['attributes'] ?? [] as $key => $value) {
            $request->attributes->set($key, $value);
        }

        return $request;
    }
}
