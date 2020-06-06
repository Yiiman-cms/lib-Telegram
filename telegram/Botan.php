<?php

namespace system\lib\telegram;

use system\lib\telegram\Types\Message;

class Botan
{

    /**
     * @var string Tracker url
     */
    const BASE_URL = 'https://api.botan.io/track';

    /**
     * CURL object
     *
     * @var
     */
    protected $curl;


    /**
     * Yandex AppMetrica application api_key
     *
     * @var string
     */
    protected $token;

    /**
     * Botan constructor
     *
     * @param string $token
     *
     * @throws \Exception
     */
    public function __construct($token)
    {
        if (!function_exists('curl_version')) {
            throw new Exception('CURL not installed');
        }

        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Token should be a string');
        }

        $this->token = $token;
        $this->curl = curl_init();
    }

    /**
     * Event tracking
     *
     * @param \system\lib\telegram\Types\Message $message
     * @param string $eventName
     *
     * @throws \system\lib\telegram\Exception
     * @throws \system\lib\telegram\HttpException
     */
    public function track(Message $message, $eventName = 'Message')
    {
        $uid = $message->getFrom()->getId();

        $options = [
            CURLOPT_URL => self::BASE_URL . "?token={$this->token}&uid={$uid}&name={$eventName}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => $message->toJson()
        ];

        curl_setopt_array($this->curl, $options);
        $result = Telegram::jsonValidate( curl_exec( $this->curl), true);

        Telegram::curlValidate( $this->curl);

        if ($result['status'] !== 'accepted') {
            throw new Exception('Error Processing Request');
        }
    }

    /**
     * Destructor. Close curl
     */
    public function __destruct()
    {
        $this->curl && curl_close($this->curl);
    }
}
