<?php

declare(strict_types=1);

namespace Kojirock5260\GithubMention\Github;

class GithubWebhookParameter
{
    const ACTION_OPENED        = 'opened';
    const ACTION_CLOSED        = 'closed';
    const ACTION_CREATED       = 'created';
    const ACTION_SUBMITTED     = 'submitted';
    const REVIEW_STATE_APPROVE = 'approved';
    const REVIEW_STATE_REJECT  = 'changes_requested';

    /**
     * @var array
     */
    private $params = [];

    /**
     * GithubWebhookParameter constructor.
     * @param string $param_json
     */
    public function __construct($param_json)
    {
        $this->params = json_decode($param_json, true);
    }

    /**
     * レポジトリ名を取得.
     * @return string
     */
    public function getRepositoryName():string
    {
        return $this->params['repository']['name'];
    }

    /**
     * ブランチ名を取得.
     * @return string
     */
    public function getBranchName():string
    {
        return substr($this->params['ref'], 11);
    }

    /**
     * レポジトリのラベルを取得.
     * @return string
     */
    public function getRepositoryLabel():string
    {
        return "【{$this->getRepositoryName()}】";
    }

    /**
     * issueかどうか.
     * @return bool
     */
    public function isIssue(): bool
    {
        return isset($this->params['issue']);
    }

    /**
     * reviewかどうか.
     * @return bool
     */
    public function isReview(): bool
    {
        return isset($this->params['review']);
    }

    /**
     * マージしたかどうか.
     * @return bool
     */
    public function isMerged(): bool
    {
        return isset($this->params['pull_request']['merged']);
    }

    /**
     * issueのタイトルを取得.
     * @return string
     */
    public function getIssueTitle(): string
    {
        return $this->params['issue']['title'];
    }

    /**
     * つけられたコメントを取得.
     * @return string
     */
    public function getComment(): string
    {
        if (!isset($this->params['comment'])) {
            return '';
        }

        return isset($this->params['comment']['body']) ? $this->params['comment']['body'] : '';
    }

    /**
     * issueのurlを取得.
     * @return string
     */
    public function getIssueUrl(): string
    {
        return $this->params['issue']['html_url'];
    }

    /**
     * pull requestのタイトルを取得.
     * @return string
     */
    public function getPullRequestTitle(): string
    {
        return $this->params['pull_request']['title'];
    }

    /**
     * pull requestの本文を取得.
     * @return string
     */
    public function getPullRequestBody(): string
    {
        return $this->params['pull_request']['body'];
    }

    /**
     * pull requestのurlを取得.
     * @return string
     */
    public function getPullRequestUrl(): string
    {
        return $this->params['pull_request']['html_url'];
    }

    /**
     * pull requestを作成したユーザー名を取得.
     * @return string
     */
    public function getPullRequestUserName(): string
    {
        return $this->params['pull_request']['user']['login'];
    }

    /**
     * レビューの文言を取得.
     * @return string
     */
    public function getReviewBody(): string
    {
        return $this->params['review']['body'];
    }

    /**
     * アクション名を取得.
     * @return string
     */
    public function getAction(): string
    {
        return isset($this->params['action']) ? $this->params['action'] : '';
    }

    /**
     * 新規オープンアクションかどうか.
     * @return bool
     */
    public function isOpenedAction(): bool
    {
        return $this->getAction() === self::ACTION_OPENED;
    }

    /**
     * 新規作成アクションかどうか.
     * @return bool
     */
    public function isCreatedAction(): bool
    {
        return $this->getAction() === self::ACTION_CREATED;
    }

    /**
     * 終了アクションかどうか.
     * @return bool
     */
    public function isClosedAction(): bool
    {
        return $this->getAction() === self::ACTION_CLOSED;
    }

    /**
     * サブミットアクションかどうか.
     * @return bool
     */
    public function isSubmittedAction(): bool
    {
        return $this->getAction() === self::ACTION_SUBMITTED;
    }

    /**
     * プルリクが許可されたかどうか.
     * @return bool
     */
    public function isPullRequestApprove(): bool
    {
        return $this->params['review']['state'] === self::REVIEW_STATE_APPROVE;
    }

    /**
     * プルリクが拒絶されたかどうか.
     * @return bool
     */
    public function isPullRequestReject(): bool
    {
        return $this->params['review']['state'] === self::REVIEW_STATE_REJECT;
    }
}
