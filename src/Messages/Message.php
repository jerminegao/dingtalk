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

abstract class Message
{
    protected $message = [];
    protected $at;

    public function getMessage(){
        return $this->message;
    }

    protected function makeAt($mobiles = [],$atAll = false){
        return [
            'at' => [
                'atMobiles' => $mobiles,
                'isAtAll' => $atAll
            ]
        ];
    }

    public function sendAt($mobiles = [],$atAll = false){
        $this->at = $this->makeAt($mobiles,$atAll);
        return $this;
    }

    public function getBody(){

        if (empty($this->at)){
            $this->sendAt();
        }
        return $this->message + $this->at;
    }
}
