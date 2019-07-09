<?php

declare(strict_types=1);

namespace Kojirock5260\GithubMention\Test\Message;

use Kojirock5260\GithubMention\Message\Mention;
use PHPUnit\Framework\TestCase;

class MentionTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
        \Carbon\Carbon::setTestNow();
    }

    /**
     * @test
     */
    public function test_getMyGroupMentionList(): void
    {
        $mention = new Mention(['AAAAA' => 'aaaaa'], ['BBBBB' => 'bbbbb'], ['CCCCC' => 'ccccc'], '10:00:00', '19:00:00', []);
        $results = $mention->getMyGroupMentionList('BBBBB');
        $this->assertSame(['BBBBB' => 'bbbbb'], $results);
    }

    /**
     * @test
     */
    public function test_addMention(): void
    {
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse('2019-01-21 15:00:00', 'Asia/Tokyo'));
        $mention = new Mention(['AAAAA' => 'aaaaa'], ['BBBBB' => 'bbbbb'], ['CCCCC' => 'ccccc'], '10:00:00', '19:00:00', []);
        $results = $mention->addMention(['AAAAA' => 'aaaaa', 'BBBBB' => 'bbbbb'], '@CCCCC');
        $this->assertSame(['AAAAA' => 'aaaaa', 'BBBBB' => 'bbbbb', 'CCCCC' => 'ccccc'], $results);
    }

    /**
     * @test
     */
    public function test_ignoreMention(): void
    {
        $mention = new Mention(['AAAAA' => 'aaaaa'], ['BBBBB' => 'bbbbb'], ['CCCCC' => 'ccccc'], '10:00:00', '19:00:00', []);
        $results = $mention->ignoreMention(['AAAAA' => 'aaaaa', 'BBBBB' => 'bbbbb'], '-@AAAAA');
        $this->assertSame(['BBBBB' => 'bbbbb'], $results);
    }

    /**
     * @test
     */
    public function test_replaceMentionGithub2Chat(): void
    {
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse('2019-01-21 15:00:00', 'Asia/Tokyo'));
        $mention = new Mention(['AAAAA' => 'aaaaa'], ['BBBBB' => 'bbbbb'], ['CCCCC' => 'ccccc'], '10:00:00', '19:00:00', []);
        $results = $mention->replaceMentionGithub2Chat('@AAAAA Comment Comment');
        $this->assertSame('aaaaa Comment Comment', $results);
    }

    /**
     * @test
     */
    public function test_getGithubMentionList(): void
    {
        $mention = new Mention(['AAAAA' => 'aaaaa'], ['BBBBB' => 'bbbbb'], ['CCCCC' => 'ccccc'], '10:00:00', '19:00:00', []);
        $results = $mention->getGithubMentionList();
        $this->assertSame(['@AAAAA', '@BBBBB', '@CCCCC'], $results);
    }

    /**
     * @test
     */
    public function test_getAllMentionList(): void
    {
        $mention = new Mention(['AAAAA' => 'aaaaa'], ['BBBBB' => 'bbbbb'], ['CCCCC' => 'ccccc'], '10:00:00', '19:00:00', []);
        $results = $mention->getAllMentionList();
        $this->assertSame(['AAAAA' => 'aaaaa', 'BBBBB' => 'bbbbb', 'CCCCC' => 'ccccc'], $results);
    }

    /**
     * @test
     */
    public function test_compilePullRequestAutoMentionIgnore(): void
    {
        $mention = new Mention([], [], [], '10:00:00', '19:00:00', ['AAAAA', 'BBBBB']);
        $results = $mention->compilePullRequestAutoMentionIgnore([
            'AAAAA' => 'aaaaa',
            'BBBBB' => 'bbbbb',
            'CCCCC' => 'ccccc',
            'DDDDD' => 'ddddd',
        ]);
        $this->assertSame(['CCCCC' => 'ccccc', 'DDDDD' => 'ddddd'], $results);
    }

    /**
     * @test
     */
    public function test_isAvailableTime_false(): void
    {
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse('2019-01-21 09:00:00', 'Asia/Tokyo'));
        $mention = new Mention([], [], [], '10:00:00', '19:00:00', []);
        $this->assertFalse($mention->isAvailableTime());
    }

    /**
     * @test
     */
    public function test_isAvailableTime_true(): void
    {
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse('2019-01-21 15:00:00', 'Asia/Tokyo'));
        $mention = new Mention([], [], [], '10:00:00', '19:00:00', []);
        $this->assertTrue($mention->isAvailableTime());
    }
}
