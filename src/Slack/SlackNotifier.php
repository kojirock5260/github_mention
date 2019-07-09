<?php

declare(strict_types=1);

namespace Kojirock5260\GithubMention\Slack;

use Kojirock5260\GithubMention\Message\MessageMaker;
use Kojirock5260\GithubMention\Message\NotifierInterface;
use Maknz\Slack\Message as SlackMessage;

class SlackNotifier implements NotifierInterface
{
    /**
     * @var SlackMessage
     */
    private $message;

    /**
     * @var MessageMaker
     */
    private $messageMaker;

    /**
     * SlackNotifier constructor.
     * @param SlackMessage $client
     * @param MessageMaker $messageMaker
     * @param SlackMessage $message
     */
    public function __construct(SlackMessage $message, MessageMaker $messageMaker)
    {
        $this->message      = $message;
        $this->messageMaker = $messageMaker;
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

        $this->message->attach([
            'color' => '#0000FF',
            'title' => $title,
            'text'  => $body,
        ]);

        $this->message->send();
    }
}
