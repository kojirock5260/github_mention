<?php

declare(strict_types=1);

namespace Kojirock5260\GithubMention\Test\Message;

use Kojirock5260\GithubMention\Message\TitleMaker;
use PHPUnit\Framework\TestCase;

class TitleMakerTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * @test
     */
    public function test_getTitleString_isOpenedAction(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $parameterMock->shouldReceive('isOpenedAction')->andReturn(true);
        $parameterMock->shouldReceive('isIssue')->andReturn(false);
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\n新たにPull Requestが作成されました。", $maker->getTitleString());
    }

    /**
     * @test
     */
    public function test_getTitleString_isCreatedAction(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $parameterMock->shouldReceive('isOpenedAction')->andReturn(false);
        $parameterMock->shouldReceive('isCreatedAction')->andReturn(true);
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\nコメントが追加されました。", $maker->getTitleString());
    }

    /**
     * @test
     */
    public function test_getTitleString_isClosedAction(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $parameterMock->shouldReceive('isOpenedAction')->andReturn(false);
        $parameterMock->shouldReceive('isCreatedAction')->andReturn(false);
        $parameterMock->shouldReceive('isClosedAction')->andReturn(true);
        $parameterMock->shouldReceive('isIssue')->andReturn(true);
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\nIssueがクローズされました！お疲れ様でした！", $maker->getTitleString());
    }

    /**
     * @test
     */
    public function test_getTitleString_isSubmittedAction_and_isReview(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $parameterMock->shouldReceive('isOpenedAction')->andReturn(false);
        $parameterMock->shouldReceive('isCreatedAction')->andReturn(false);
        $parameterMock->shouldReceive('isClosedAction')->andReturn(false);
        $parameterMock->shouldReceive('isSubmittedAction')->andReturn(true);
        $parameterMock->shouldReceive('isReview')->andReturn(true);
        $parameterMock->shouldReceive('isPullRequestApprove')->andReturn(true);
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\nPull Requestが許可されました！", $maker->getTitleString());
    }

    /**
     * @test
     */
    public function test_getTitleStringByCreate_issue(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $parameterMock->shouldReceive('isIssue')->andReturn(true);
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\n新たにIssueが作成されました。", $maker->getTitleStringByCreate());
    }

    /**
     * @test
     */
    public function test_getTitleStringByCreate_other(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $parameterMock->shouldReceive('isIssue')->andReturn(false);
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\n新たにPull Requestが作成されました。", $maker->getTitleStringByCreate());
    }

    /**
     * @test
     */
    public function test_getTitleStringByComment(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\nコメントが追加されました。", $maker->getTitleStringByComment());
    }

    /**
     * @test
     */
    public function test_getTitleStringByClose_isIssue(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $parameterMock->shouldReceive('isIssue')->andReturn(true);
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\nIssueがクローズされました！お疲れ様でした！", $maker->getTitleStringByClose());
    }

    /**
     * @test
     */
    public function test_getTitleStringByClose_isMerged(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $parameterMock->shouldReceive('isIssue')->andReturn(false);
        $parameterMock->shouldReceive('isMerged')->andReturn(true);
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\nPull Requestがマージされました！お疲れ様でした！", $maker->getTitleStringByClose());
    }

    /**
     * @test
     */
    public function test_getTitleStringByClose_close(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $parameterMock->shouldReceive('isIssue')->andReturn(false);
        $parameterMock->shouldReceive('isMerged')->andReturn(false);
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\nPull Requestがクローズされました。", $maker->getTitleStringByClose());
    }

    /**
     * @test
     */
    public function test_getTitleStringByReview_isPullRequestApprove(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $parameterMock->shouldReceive('isPullRequestApprove')->andReturn(true);
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\nPull Requestが許可されました！", $maker->getTitleStringByReview());
    }

    /**
     * @test
     */
    public function test_getTitleStringByReview_isPullRequestReject(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $parameterMock->shouldReceive('isPullRequestApprove')->andReturn(false);
        $parameterMock->shouldReceive('isPullRequestReject')->andReturn(true);
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\nPull Requestが却下されました！\n確認してください！", $maker->getTitleStringByReview());
    }

    /**
     * @test
     */
    public function test_getTitleStringByReview_isPullRequestComment(): void
    {
        $parameterMock = \Mockery::mock('Kojirock5260\GithubMention\Github\GithubWebhookParameter');
        $parameterMock->shouldReceive('getRepositoryLabel')->andReturn('【AAAAAAA】');
        $parameterMock->shouldReceive('isPullRequestApprove')->andReturn(false);
        $parameterMock->shouldReceive('isPullRequestReject')->andReturn(false);
        $parameterMock->shouldReceive('getComment')->andReturn('test');
        $maker = new TitleMaker($parameterMock);
        $this->assertSame("【AAAAAAA】\nPull Requestにコメントがつきました。", $maker->getTitleStringByReview());
    }
}
