<?php

declare(strict_types=1);

namespace Kojirock5260\GithubMention\Test\Slack;

use Kojirock5260\GithubMention\Slack\SlackNotifier;
use PHPUnit\Framework\TestCase;

class SlackNotifierTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * @test
     */
    public function test_send_OK(): void
    {
        $slackMessageMock = \Mockery::mock('Maknz\Slack\Message');
        $messageMakerMock = \Mockery::mock('Kojirock5260\GithubMention\Message\MessageMaker');
        $slackMessageMock->shouldReceive('attach');
        $slackMessageMock->shouldReceive('send');
        $messageMakerMock->shouldReceive('getTitle')->andReturn('title');
        $messageMakerMock->shouldReceive('getBody')->andReturn('body');
        $notifier         = new SlackNotifier($slackMessageMock, $messageMakerMock);
        $notifier->send();
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function test_send_NG(): void
    {
        $this->expectException(\LogicException::class);
        $slackMessageMock = \Mockery::mock('Maknz\Slack\Message');
        $messageMakerMock = \Mockery::mock('Kojirock5260\GithubMention\Message\MessageMaker');
        $slackMessageMock->shouldReceive('attach');
        $slackMessageMock->shouldReceive('send');
        $messageMakerMock->shouldReceive('getTitle')->andReturn('');
        $messageMakerMock->shouldReceive('getBody')->andReturn('body');
        $notifier         = new SlackNotifier($slackMessageMock, $messageMakerMock);
        $notifier->send();
    }
}
