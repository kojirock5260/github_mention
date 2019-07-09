<?php

declare(strict_types=1);

namespace Kojirock5260\GithubMention\Chatwork;

use Kojirock5260\GithubMention\Message\MessageMaker;
use Kojirock5260\GithubMention\Message\NotifierInterface;
use Polidog\Chatwork\Chatwork;

class ChatworkNotifier implements NotifierInterface
{
    /**
     * @var Chatwork
     */
    private $chatwork;

    /**
     * @var MessageMaker
     */
    private $messageMaker;

    /**
     * @var int
     */
    private $roomId;

    /**
     * ChatworkNotifier constructor.
     * @param Chatwork     $chatwork
     * @param int          $roomId
     * @param MessageMaker $messageMaker
     */
    public function __construct(Chatwork $chatwork, MessageMaker $messageMaker, int $roomId)
    {
        $this->chatwork     = $chatwork;
        $this->messageMaker = $messageMaker;
        $this->roomId       = $roomId;
    }

    /**
     * チャットワーク通知実行.
     */
    public function send(): void
    {
        $title = $this->messageMaker->getTitle();
        $body  = $this->messageMaker->getBody();

        if (strlen($title) === 0) {
            echo 'no title';
            exit;
        }

        $messageString          = "[info][title]{$title}[/title]{$body}[/info]";
        $messageEntity          = new \Polidog\Chatwork\Entity\Message();
        $messageEntity->account = $this->chatwork->me()->show();
        $messageEntity->body    = $messageString;
        $this->chatwork->rooms()->messages($this->roomId)->create($messageEntity);
    }
}
