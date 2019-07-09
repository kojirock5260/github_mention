<?php

declare(strict_types=1);

namespace Kojirock5260\GithubMention\Test\Chatwork;

use Kojirock5260\GithubMention\Chatwork\ChatworkNotifier;
use PHPUnit\Framework\TestCase;

class ChatworkNotifierTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * @test
     */
    public function test_send_ok(): void
    {
        $chatworkMock      = \Mockery::mock('Polidog\Chatwork\Chatwork');
        $messageMakerMock  = \Mockery::mock('Kojirock5260\GithubMention\Message\MessageMaker');
        $messagesMock      = \Mockery::mock('Polidog\Chatwork\Api\Rooms\Messages');
        $messagesMock->shouldReceive('create');
        $chatworkMeMock    = \Mockery::mock('Polidog\Chatwork\Api\Me');
        $chatworkMeMock->shouldReceive('show');
        $chatworkRoomsMock = \Mockery::mock('Polidog\Chatwork\Api\Rooms');
        $chatworkRoomsMock->shouldReceive('messages')->andReturn($messagesMock);
        $chatworkMock->shouldReceive('me')->andReturn($chatworkMeMock);
        $chatworkMock->shouldReceive('rooms')->andReturn($chatworkRoomsMock);
        $chatworkMock->shouldReceive('send');
        $messageMakerMock->shouldReceive('getTitle')->andReturn('title');
        $messageMakerMock->shouldReceive('getBody')->andReturn('body');
        $notifier = new ChatworkNotifier($chatworkMock, $messageMakerMock, 100);
        $notifier->send();
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function test_send_ng(): void
    {
        $this->expectException(\LogicException::class);
        $chatworkMock      = \Mockery::mock('Polidog\Chatwork\Chatwork');
        $messageMakerMock  = \Mockery::mock('Kojirock5260\GithubMention\Message\MessageMaker');
        $messagesMock      = \Mockery::mock('Polidog\Chatwork\Api\Rooms\Messages');
        $messagesMock->shouldReceive('create');
        $chatworkMeMock    = \Mockery::mock('Polidog\Chatwork\Api\Me');
        $chatworkMeMock->shouldReceive('show');
        $chatworkRoomsMock = \Mockery::mock('Polidog\Chatwork\Api\Rooms');
        $chatworkRoomsMock->shouldReceive('messages')->andReturn($messagesMock);
        $chatworkMock->shouldReceive('me')->andReturn($chatworkMeMock);
        $chatworkMock->shouldReceive('rooms')->andReturn($chatworkRoomsMock);
        $chatworkMock->shouldReceive('send');
        $messageMakerMock->shouldReceive('getTitle')->andReturn('');
        $messageMakerMock->shouldReceive('getBody')->andReturn('body');
        $notifier = new ChatworkNotifier($chatworkMock, $messageMakerMock, 100);
        $notifier->send();
    }
}
