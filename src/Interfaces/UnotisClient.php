<?php namespace Spoob\UnotisLaravel\Interfaces;

use Throwable;

interface UnotisClient
{
    /**
     * Create message.
     *
     * @param string $subject
     * @param string $text
     * @param string|null $url
     *
     * @return string
     */
    function createMessage(string $subject, string $text, string $url = null): string;

    /**
     * Create message and send e-mail.
     *
     * @param string $addressee
     * @param string $subject
     * @param string $text
     * @param string|null $url
     *
     * @return string
     */
    function sendEmail(string $addressee, string $subject, string $text, string $url = null): string;

    /**
     * Create message and send to telegram messenger.
     *
     * @param string $subject
     * @param string $text
     * @param string|null $url
     *
     * @return string
     */
    function writeToTelegram(string $subject, string $text, string $url = null): string;

    /**
     * Catches application exception.
     *
     * @param Throwable $exception
     * @param mixed $request
     *
     * @return void
     */
    function catchException(Throwable $exception, mixed $request, string $project_token): string;
}
