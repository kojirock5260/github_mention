<?php

declare(strict_types=1);

namespace Kojirock5260\GithubMention\Test\Github;

use Kojirock5260\GithubMention\Github\GithubWebhookParameter;
use PHPUnit\Framework\TestCase;

class GithubWebhookParameterTest extends TestCase
{
    /**
     * @test
     */
    public function test_getRepositoryName(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertSame('repository_name', $parameter->getRepositoryName());
    }

    /**
     * @test
     */
    public function test_getRepositoryLabel(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertSame('【repository_name】', $parameter->getRepositoryLabel());
    }

    /**
     * @test
     */
    public function test_isIssue(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertFalse($parameter->isIssue());
    }

    /**
     * @test
     */
    public function test_isReview(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertTrue($parameter->isReview());
    }

    /**
     * @test
     */
    public function test_isMerged(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertTrue($parameter->isMerged());
    }

    /**
     * @test
     */
    public function test_getIssueTitle(): void
    {
        $parameter = new GithubWebhookParameter($this->getIssueJson());
        $this->assertSame('xxxxxxxxxxxx', $parameter->getIssueTitle());
    }

    /**
     * @test
     */
    public function test_getComment(): void
    {
        $parameter = new GithubWebhookParameter($this->getIssueJson());
        $this->assertSame('xxxxxxxxxxxxxxxxxxxxxxxx', $parameter->getComment());
    }

    /**
     * @test
     */
    public function test_getIssueUrl(): void
    {
        $parameter = new GithubWebhookParameter($this->getIssueJson());
        $this->assertSame('https://github.com/pull/1', $parameter->getIssueUrl());
    }

    /**
     * @test
     */
    public function test_getPullRequestTitle(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertSame('pull request 1', $parameter->getPullRequestTitle());
    }

    /**
     * @test
     */
    public function test_getPullRequestBody(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertSame('xxxxxxxxxxxxxxxxxxxxx', $parameter->getPullRequestBody());
    }

    /**
     * @test
     */
    public function test_getPullRequestUrl(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertSame('https://github.com/xxxxxx/pull/1', $parameter->getPullRequestUrl());
    }

    /**
     * @test
     */
    public function test_getPullRequestUserName(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertSame('kojirock5260', $parameter->getPullRequestUserName());
    }

    /**
     * @test
     */
    public function test_getReviewBody(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertSame('ＬＧＴＭ', $parameter->getReviewBody());
    }

    /**
     * @test
     */
    public function test_getAction(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertSame('submitted', $parameter->getAction());
    }

    /**
     * @test
     */
    public function test_isOpenedAction(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertFalse($parameter->isOpenedAction());
    }

    /**
     * @test
     */
    public function test_isCreatedAction(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertFalse($parameter->isCreatedAction());
    }

    /**
     * @test
     */
    public function test_isClosedAction(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertFalse($parameter->isClosedAction());
    }

    /**
     * @test
     */
    public function test_isSubmittedAction(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertTrue($parameter->isSubmittedAction());
    }

    /**
     * @test
     */
    public function test_isPullRequestApprove(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertTrue($parameter->isPullRequestApprove());
    }

    /**
     * @test
     */
    public function test_isPullRequestReject(): void
    {
        $parameter = new GithubWebhookParameter($this->getJson());
        $this->assertFalse($parameter->isPullRequestReject());
    }

    /**
     * @return string
     */
    private function getJson(): string
    {
        return <<<'JSON'
{
  "action": "submitted",
  "review": {
    "body": "ＬＧＴＭ",
    "state": "approved"
  },
  "pull_request": {
    "html_url": "https://github.com/xxxxxx/pull/1",
    "title": "pull request 1",
    "user": {
      "login": "kojirock5260"
    },
    "body": "xxxxxxxxxxxxxxxxxxxxx",
    "merged": true
  },
  "repository": {
    "name": "repository_name"
  }
}
JSON;
    }

    /**
     * @return string
     */
    private function getIssueJson(): string
    {
        return <<<'JSON'
{
  "action": "created",
  "issue": {
    "html_url": "https://github.com/pull/1",
    "title": "xxxxxxxxxxxx"
  },
  "comment": {
    "body": "xxxxxxxxxxxxxxxxxxxxxxxx"
  }
}
JSON;
    }
}
