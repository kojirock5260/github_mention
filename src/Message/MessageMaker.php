<?php

declare(strict_types=1);

namespace Kojirock5260\GithubMention\Message;

class MessageMaker
{
    /**
     * @var BodyMaker
     */
    private $bodyMaker;

    /**
     * @var TitleMaker
     */
    private $titleMaker;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $body;

    /**
     * MessageMaker constructor.
     * @param BodyMaker  $bodyMaker
     * @param TitleMaker $titleMaker
     */
    public function __construct(BodyMaker $bodyMaker, TitleMaker $titleMaker)
    {
        $this->bodyMaker  = $bodyMaker;
        $this->titleMaker = $titleMaker;
        $this->makeMessage();
    }

    /**
     * ボディを取得.
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * タイトルを取得.
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * メッセージを作成する.
     */
    private function makeMessage(): void
    {
        $this->title = $this->titleMaker->getTitleString();
        $this->body  = $this->bodyMaker->getBodyString();
    }
}
