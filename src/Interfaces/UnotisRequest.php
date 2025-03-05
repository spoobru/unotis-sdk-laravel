<?php namespace Spoob\UnotisLaravel\Interfaces;

interface UnotisRequest
{
    function post(string $url, array $data): string;

    function get(string $url, array $data): string;
}
