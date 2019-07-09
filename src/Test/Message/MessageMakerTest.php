<?php

declare(strict_types=1);

namespace Kojirock5260\GithubMention\Test\Message;

use Kojirock5260\GithubMention\Message\MessageMaker;
use PHPUnit\Framework\TestCase;

class MessageMakerTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * @test
     */
    public function test_getBody(): void
    {
        $bodyMakerMock  = \Mockery::mock('Kojirock5260\GithubMention\Message\BodyMaker');
        $titleMakerMock = \Mockery::mock('Kojirock5260\GithubMention\Message\TitleMaker');
        $bodyMakerMock->shouldReceive('getBodyString')->andReturn('body');
        $titleMakerMock->shouldReceive('getTitleString')->andReturn('title');
        $maker          = new MessageMaker($bodyMakerMock, $titleMakerMock);
        $this->assertSame('body', $maker->getBody());
    }

    /**
     * @test
     */
    public function test_getTitle(): void
    {
        $bodyMakerMock  = \Mockery::mock('Kojirock5260\GithubMention\Message\BodyMaker');
        $titleMakerMock = \Mockery::mock('Kojirock5260\GithubMention\Message\TitleMaker');
        $bodyMakerMock->shouldReceive('getBodyString')->andReturn('body');
        $titleMakerMock->shouldReceive('getTitleString')->andReturn('title');
        $maker          = new MessageMaker($bodyMakerMock, $titleMakerMock);
        $this->assertSame('title', $maker->getTitle());
    }
}
