<?php namespace Spoob\UnotisLaravel;

use Illuminate\Support\Facades\Config;
use Illuminate\Container\Container;
use Spoob\UnotisLaravel\Bridge\UnotisRequest;
use Spoob\UnotisLaravel\Serializers\ExceptionSerializer;
use Spoob\UnotisLaravel\Serializers\RequestSerializer;
use Spoob\UnotisLaravel\Exceptions\ProjectTokenNotSpecified;
use Spoob\UnotisLaravel\Interfaces\UnotisClient as iClient;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Throwable;

/**
 * Unotis client
 *
 * @author SPOOB <info@spoob.ru>
 * @package UnotisLaravel
 * @version 2.1.0
 */
class UnotisClient implements iClient
{
    /**
     * @var string
     */
    const API_URL = 'https://formando.unotis.ru/api/';

    /**
     * @var string
     */
    private string $version = '1';

    /**
     * @var string
     */
    private string $token;

    /**
     * @var bool
     */
    private bool $use_curl;

    private ExceptionSerializer $exceptionSerializer;
    private RequestSerializer $requestSerializer;

    /**
     * @param string $token
     * @param bool $use_curl
     */
    public function __construct(string $token, bool $use_curl = true)
    {
        $this->token = $token;
        $this->use_curl = $use_curl;
        $this->exceptionSerializer = new ExceptionSerializer();
        $this->requestSerializer = new RequestSerializer();
    }

    /**
     * Create message in service.
     *
     * @param string $subject
     * @param string $text
     * @param string|null $url
     *
     * @return string
     */
    public function createMessage(string $subject, string $text, string $url = null): string
    {
        return $this->postRequest('send/message', compact('subject', 'text', 'url'));
    }

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
    public function sendEmail(string $addressee, string $subject, string $text, string $url = null): string
    {
        return $this->postRequest('send/email', compact('addressee', 'subject', 'text', 'url'));
    }

    /**
     * Create message and send to telegram messenger.
     *
     * @param string $subject
     * @param string $text
     * @param string|null $url
     *
     * @return string
     */
    public function writeToTelegram(string $subject, string $text, string $url = null): string
    {
        return $this->postRequest('send/telegram', compact('subject', 'text', 'url'));
    }

    /**
     * Catches application exception.
     *
     * @param Throwable   $exception
     * @param mixed       $request
     * @param null|string $project_token
     *
     * @return string
     *
     * @throws ProjectTokenNotSpecified
     */
    public function catchException(Throwable $exception, ?SymfonyRequest $request = null, ?string $project_token = null): string
    {
        if (empty($project_token)) {
            $projectTokenFromConfig = Config::get('unotis.project_token');

            if (empty($projectTokenFromConfig)) throw new ProjectTokenNotSpecified();

            $project_token = $projectTokenFromConfig;
        }

        if (empty($request)) {
            $request = Container::getInstance()->make('request', []);
        }

        $exception = $this->exceptionSerializer->serialize($exception);
        $request = $this->requestSerializer->serialize($request);

        return $this->postRequest('issue/catch', compact('exception', 'request', 'project_token'));
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getApiUrl(string $type): string
    {
        return self::API_URL . 'v' . $this->version . '/' . $type;
    }

    /**
     * Raw request.
     *
     * @param string $type
     * @param array<array{exception: string, request: string, project_token: string}> $data
     *
     * @return string
     */
    private function postRequest(string $type, array $data): string
    {
        $data['token'] = $this->token;
        $url = $this->getApiUrl($type);

        $request = new UnotisRequest($this->use_curl);

        return $request->post($url, $data);
    }
}
