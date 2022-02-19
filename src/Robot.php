<?php

/*
 * This file is part of the mingyoung/dingtalk.
 *
 * (c) 张铭阳 <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyDingTalk;

use EasyDingTalk\Kernel\Traits\HasHttpRequests;
use EasyDingTalk\Kernel\Traits\ResponseCastable;
use EasyDingTalk\Kernel\Traits\CreatesDefaultHttpClient;
use EasyDingTalk\Messages\Message;
use EasyDingTalk\Messages\Text;
use EasyDingTalk\Messages\Link;
use EasyDingTalk\Messages\Markdown;

class Robot
{
    use HasHttpRequests, ResponseCastable, CreatesDefaultHttpClient;

    protected $config;

    /**
     * @var Message
     */
    protected $message;
    /**
     * @var array
     */
    protected $mobiles = [];
    /**
     * @var bool
     */
    protected $atAll = false;

    /**
     * 机器人 AccessToken
     *
     * @var string
     */
    protected $accessToken;

    /**
     * 加签 没有勾选，不用填写
     *
     * @var string
     */
    protected $secret;

    /**
     * @param string      $accessToken
     * @param string|null $secret
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param string $robot
     * @return $this
     */
    public function with($robot = 'default')
    {
        $this->accessToken =  $this->config[$robot]['token'];
        $this->secret =  $this->config[$robot]['secret'];
        return $this;
    }

    /**
     * @param string      $accessToken
     * @param string|null $secret
     *
     * @return self
     */
    public static function create($accessToken, $secret = null)
    {
        return new static($accessToken, $secret);
    }

    /**
     * 发送消息
     *
     * @param array $message
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($message)
    {
        $url = 'https://oapi.dingtalk.com/robot/send?access_token='.$this->accessToken;

        if ($this->secret) {
            $timestamp = time().'000';
            $url .= sprintf(
                '&sign=%s&timestamp=%s',
                urlencode(base64_encode(hash_hmac('sha256', $timestamp."\n".$this->secret, $this->secret, true))), $timestamp
            );
        }
        $response = $this->getHttpClient()->request(
            'POST', $url, ['json' => $message]
        );
        return $this->castResponseToType($response);
    }

    /**
     * @param $content
     * @return $this
     */
    public function text($content)
    {
        $this->message = new Text($content);
        $this->message->sendAt($this->mobiles, $this->atAll);
        return $this->send($this->message->getBody());
    }

    /**
     * @param $title
     * @param $text
     * @param $messageUrl
     * @param string $picUrl
     * @return $this
     */
    public function link($title, $text, $messageUrl, $picUrl = '')
    {
        $this->message = new Link($title, $text, $messageUrl, $picUrl);
        $this->message->sendAt($this->mobiles, $this->atAll);
        return $this->send($this->message->getBody());
    }
}
