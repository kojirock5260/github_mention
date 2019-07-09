<?php

declare(strict_types=1);

namespace Kojirock5260\GithubMention\Test\Message;

use Kojirock5260\GithubMention\Message\BodyMaker;
use PHPUnit\Framework\TestCase;

class BodyMakerTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * @test
     */
    public function test_getBodyString_issue(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $parameterMock->shouldReceive('getIssueTitle')->andReturn('issue title');
        $parameterMock->shouldReceive('getIssueUrl')->andReturn('https://github.com/kojirock5260');
        $parameterMock->shouldReceive('isIssue')->andReturn(true);
        $parameterMock->shouldReceive('getComment')->andReturn('comment comment');
        $mentionMock->shouldReceive('replaceMentionGithub2Chat')->andReturn('CommentCommentComment');
        $maker = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("タイトル: issue title\nURL: https://github.com/kojirock5260\n\nコメント: CommentCommentComment", $maker->getBodyString());
    }

    /**
     * @test
     */
    public function test_getBodyString_review評価(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $parameterMock->shouldReceive('isIssue')->andReturn(false);
        $parameterMock->shouldReceive('isSubmittedAction')->andReturn(true);
        $parameterMock->shouldReceive('isReview')->andReturn(true);
        $parameterMock->shouldReceive('getPullRequestTitle')->andReturn('title title');
        $parameterMock->shouldReceive('getPullRequestUrl')->andReturn('https://github.com/kojirock5260');
        $parameterMock->shouldReceive('getReviewBody')->andReturn('review body');
        $maker = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("タイトル: title title\nURL: https://github.com/kojirock5260\n\nコメント: review body", $maker->getBodyString());
    }

    /**
     * @test
     */
    public function test_getBodyString_pull_request_open(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $parameterMock->shouldReceive('isIssue')->andReturn(false);
        $parameterMock->shouldReceive('isSubmittedAction')->andReturn(false);
        $parameterMock->shouldReceive('isOpenedAction')->andReturn(true);
        $parameterMock->shouldReceive('getPullRequestTitle')->andReturn('title title');
        $parameterMock->shouldReceive('getPullRequestUrl')->andReturn('https://github.com/kojirock5260');
        $parameterMock->shouldReceive('getPullRequestUserName')->andReturn('kojirock');
        $parameterMock->shouldReceive('getPullRequestBody')->andReturn('pull request body');
        $mentionMock->shouldReceive('getMyGroupMentionList')->andReturn(['kojirock' => 'kojirock', 'user1' => 'AAAAA', 'user2' => 'BBBBB']);
        $mentionMock->shouldReceive('addMention')->andReturn(['user1' => 'AAAAA', 'user2' => 'BBBBB']);
        $mentionMock->shouldReceive('ignoreMention')->andReturn(['user1' => 'AAAAA']);
        $maker = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("AAAAA\n\nレビューお願いします！\nタイトル: title title\nURL: https://github.com/kojirock5260", $maker->getBodyString());
    }

    /**
     * @test
     */
    public function test_getBodyString_pull_request_close(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $parameterMock->shouldReceive('isIssue')->andReturn(false);
        $parameterMock->shouldReceive('isSubmittedAction')->andReturn(false);
        $parameterMock->shouldReceive('isOpenedAction')->andReturn(false);
        $parameterMock->shouldReceive('isClosedAction')->andReturn(true);
        $parameterMock->shouldReceive('getPullRequestTitle')->andReturn('title title');
        $parameterMock->shouldReceive('getPullRequestUrl')->andReturn('https://github.com/kojirock5260');
        $parameterMock->shouldReceive('isMerged')->andReturn(true);
        $maker = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("pullRequestImage\nタイトル: title title\nURL: https://github.com/kojirock5260", $maker->getBodyString());
    }

    /**
     * @test
     */
    public function test_getBodyString_pull_request_general(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $parameterMock->shouldReceive('isIssue')->andReturn(false);
        $parameterMock->shouldReceive('isSubmittedAction')->andReturn(false);
        $parameterMock->shouldReceive('isOpenedAction')->andReturn(false);
        $parameterMock->shouldReceive('isClosedAction')->andReturn(false);
        $parameterMock->shouldReceive('getPullRequestTitle')->andReturn('title title');
        $parameterMock->shouldReceive('getPullRequestUrl')->andReturn('https://github.com/kojirock5260');
        $parameterMock->shouldReceive('getComment')->andReturn('comment comment');
        $mentionMock->shouldReceive('replaceMentionGithub2Chat')->andReturn('CommentCommentComment');
        $maker = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("タイトル: title title\nURL: https://github.com/kojirock5260\n\nコメント: CommentCommentComment", $maker->getBodyString());
    }

    /**
     * @test
     */
    public function test_getBodyStringByIssue(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $parameterMock->shouldReceive('getIssueTitle')->andReturn('issue title');
        $parameterMock->shouldReceive('getIssueUrl')->andReturn('https://github.com/kojirock5260');
        $parameterMock->shouldReceive('getComment')->andReturn('comment comment');
        $mentionMock->shouldReceive('replaceMentionGithub2Chat')->andReturn('CommentCommentComment');
        $maker = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("タイトル: issue title\nURL: https://github.com/kojirock5260\n\nコメント: CommentCommentComment", $maker->getBodyStringByIssue());
    }

    /**
     * @test
     */
    public function test_getBodyStringByPullRequest(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $parameterMock->shouldReceive('getPullRequestTitle')->andReturn('title title');
        $parameterMock->shouldReceive('getPullRequestUrl')->andReturn('https://github.com/kojirock5260');
        $parameterMock->shouldReceive('getComment')->andReturn('comment comment');
        $mentionMock->shouldReceive('replaceMentionGithub2Chat')->andReturn('CommentCommentComment');
        $maker = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("タイトル: title title\nURL: https://github.com/kojirock5260\n\nコメント: CommentCommentComment", $maker->getBodyStringByPullRequest());
    }

    /**
     * @test
     */
    public function test_getBodyStringByReview(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $parameterMock->shouldReceive('getPullRequestTitle')->andReturn('title title');
        $parameterMock->shouldReceive('getPullRequestUrl')->andReturn('https://github.com/kojirock5260');
        $parameterMock->shouldReceive('getReviewBody')->andReturn('review body');
        $maker = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("タイトル: title title\nURL: https://github.com/kojirock5260\n\nコメント: review body", $maker->getBodyStringByReview());
    }

    /**
     * @test
     */
    public function test_getBodyStringByPullRequestOpen(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $parameterMock->shouldReceive('getPullRequestTitle')->andReturn('title title');
        $parameterMock->shouldReceive('getPullRequestUrl')->andReturn('https://github.com/kojirock5260');
        $parameterMock->shouldReceive('getPullRequestUserName')->andReturn('kojirock');
        $parameterMock->shouldReceive('getPullRequestBody')->andReturn('pull request body');
        $mentionMock->shouldReceive('getMyGroupMentionList')->andReturn(['kojirock' => 'kojirock', 'user1' => 'AAAAA', 'user2' => 'BBBBB']);
        $mentionMock->shouldReceive('addMention')->andReturn(['user1' => 'AAAAA', 'user2' => 'BBBBB']);
        $mentionMock->shouldReceive('ignoreMention')->andReturn(['user1' => 'AAAAA']);
        $maker = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("AAAAA\n\nレビューお願いします！\nタイトル: title title\nURL: https://github.com/kojirock5260", $maker->getBodyStringByPullRequestOpen());
    }

    /**
     * @test
     */
    public function test_getBodyStringByPullRequestClose(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $parameterMock->shouldReceive('getPullRequestTitle')->andReturn('title title');
        $parameterMock->shouldReceive('getPullRequestUrl')->andReturn('https://github.com/kojirock5260');
        $parameterMock->shouldReceive('isMerged')->andReturn(true);
        $maker = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("pullRequestImage\nタイトル: title title\nURL: https://github.com/kojirock5260", $maker->getBodyStringByPullRequestClose());
    }

    /**
     * @test
     */
    public function test_getPullRequestMentionListString(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $parameterMock->shouldReceive('getPullRequestUserName')->andReturn('kojirock');
        $parameterMock->shouldReceive('getPullRequestBody')->andReturn('pull request body');
        $mentionMock->shouldReceive('getMyGroupMentionList')->andReturn(['kojirock' => 'kojirock', 'user1' => 'AAAAA', 'user2' => 'BBBBB']);
        $mentionMock->shouldReceive('addMention')->andReturn(['user1' => 'AAAAA', 'user2' => 'BBBBB']);
        $mentionMock->shouldReceive('ignoreMention')->andReturn(['user1' => 'AAAAA']);
        $maker = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame('AAAAA', $maker->getPullRequestMentionListString());
    }

    /**
     * @test
     */
    public function test_mergeComment(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $parameterMock->shouldReceive('getComment')->andReturn('pppp');
        $mentionMock->shouldReceive('replaceMentionGithub2Chat')->andReturn('CommentCommentComment');
        $maker         = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("AAAAAAAAA\n\nコメント: CommentCommentComment", $maker->mergeComment('AAAAAAAAA'));
    }

    /**
     * @test
     */
    public function test_getBodyFormat(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $maker         = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("タイトル: AAAAAAAAA\nURL: https://github.com/kojirock5260", $maker->getBodyFormat('AAAAAAAAA', 'https://github.com/kojirock5260'));
    }

    /**
     * @test
     */
    public function test_getCommentFormat(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $mentionMock   = \Mockery::mock('Kojirock5260\GithubMention\Message\Mention');
        $maker         = new BodyMaker($parameterMock, $mentionMock, 'pullRequestImage');
        $this->assertSame("\n\nコメント: AAAAAAAAA", $maker->getCommentFormat('AAAAAAAAA'));
    }
}
