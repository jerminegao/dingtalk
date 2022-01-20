<?php

/*
 * This file is part of the mingyoung/dingtalk.
 *
 * (c) 张铭阳 <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyDingTalk\Messages;

class Link extends Message
{
    public function __construct($title,$text,$messageUrl,$picUrl = '')
    {
        $this->setMessage($title,$text,$messageUrl,$picUrl);
    }

    public function setMessage($title,$text,$messageUrl,$picUrl = ''){
        $this->message  = [
            'msgtype' => 'link',
            'link' => [
                'text' => $text,
                'title' => $title,
                'picUrl' => $picUrl,
                'messageUrl' => $messageUrl
            ]
        ];
    }
}
