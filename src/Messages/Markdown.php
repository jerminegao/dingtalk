<?php
/**
 * Created by : PhpStorm
 * User: Jermine
 * Date: 2022/1/17
 * Time: 11:37
 */

namespace EasyDingTalk\Messages;


class Markdown
{
    public function __construct($title,$markdown)
    {
        $this->setMessage($title,$markdown);
    }

    public function setMessage($title,$markdown){
        $this->message  = [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $title,
                'text' => $markdown
            ]
        ];
    }

}