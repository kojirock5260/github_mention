<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/08/02
 * Time: 10:22.
 */

namespace Kojirock5260\GithubMention\Message;

use Kojirock5260\GithubMention\Github\GithubWebhookParameter;

class BodyMaker
{
    /**
     * @var GithubWebhookParameter
     */
    private $parameter;

    /**
     * @var Mention
     */
    private $mention;

    /**
     * @var string
     */
    private $pullRequestImage;

    /**
     * BodyMaker constructor.
     * @param GithubWebhookParameter $parameter
     * @param Mention                $mention
     * @param string                 $pullRequestImage
     */
    public function __construct(GithubWebhookParameter $parameter, Mention $mention, string $pullRequestImage)
    {
        $this->parameter        = $parameter;
        $this->mention          = $mention;
        $this->pullRequestImage = $pullRequestImage;
    }

    /**
     * 通知用本文を返す.
     * @return string
     */
    public function getBodyString(): string
    {
        if ($this->parameter->isIssue()) {
            // issueの場合
            $result = $this->getBodyStringByIssue();
        } elseif ($this->parameter->isSubmittedAction() && $this->parameter->isReview()) {
            // review評価時の場合
            $result = $this->getBodyStringByReview();
        } else {
            if ($this->parameter->isOpenedAction()) {
                // pull request open時
                $result = $this->getBodyStringByPullRequestOpen();
            } elseif ($this->parameter->isClosedAction()) {
                // pull request close時
                $result = $this->getBodyStringByPullRequestClose();
            } else {
                // 通常のコメント時
                $result = $this->getBodyStringByPullRequest();
            }
        }

        return $result;
    }

    /**
     * issue時のボディを返す.
     * @return string
     */
    public function getBodyStringByIssue(): string
    {
        $title = $this->parameter->getIssueTitle();
        $url   = $this->parameter->getIssueUrl();
        $body  = $this->getBodyFormat($title, $url);

        return $this->mergeComment($body);
    }

    /**
     * pull request時のボディを返す.
     * @return string
     */
    public function getBodyStringByPullRequest(): string
    {
        $title = $this->parameter->getPullRequestTitle();
        $url   = $this->parameter->getPullRequestUrl();
        $body  = $this->getBodyFormat($title, $url);

        return $this->mergeComment($body);
    }

    /**
     * review評価時のボディを返す.
     * @return string
     */
    public function getBodyStringByReview(): string
    {
        // プルリクエストのデータも来ているはず
        $title   = $this->parameter->getPullRequestTitle();
        $url     = $this->parameter->getPullRequestUrl();
        $body    = $this->getBodyFormat($title, $url);

        // レビューコメントを取得
        $comment = $this->getCommentFormat($this->parameter->getReviewBody());

        return $body . $comment;
    }

    /**
     * プルリクエストのオープン時のボディを返す.
     * @return string
     */
    public function getBodyStringByPullRequestOpen(): string
    {
        $title = $this->parameter->getPullRequestTitle();
        $url   = $this->parameter->getPullRequestUrl();
        $body  = $this->getBodyFormat($title, $url);

        // メンションを取得
        $mentionString = $this->getPullRequestMentionListString();

        return $mentionString . "\n\nレビューお願いします！\n" . $body;
    }

    /**
     * プルリクエストのクローズかブランチマージ時のボディを返す.
     * @return string
     */
    public function getBodyStringByPullRequestClose(): string
    {
        $title = $this->parameter->getPullRequestTitle();
        $url   = $this->parameter->getPullRequestUrl();
        $body  = $this->getBodyFormat($title, $url);

        if ($this->parameter->isMerged()) {
            // ブランチマージ時
            return $this->pullRequestImage . "\n" . $body;
        }
        // プルリククローズの時は絵文字はつけない
        return $body;
    }

    /**
     * プルリク時にメンションをつけたいリスト文字列を返す.
     * @return string
     */
    public function getPullRequestMentionListString(): string
    {
        // プルリクエストを作ったユーザーを取得
        $user_name = $this->parameter->getPullRequestUserName();

        // 自分が所属するグループのメンションのリストを取得
        $mentionList = $this->mention->getMyGroupMentionList($user_name);

        if (count($mentionList) === 0) {
            return '';
        }

        // 本文データを取得
        $bodyString = $this->parameter->getPullRequestBody();

        // 本文中にメンションを書いているかどうかを調べて、あればメンションを追加する
        $mentionList = $this->mention->addMention($mentionList, $bodyString);

        // 本文中に否定メンションがある場合は、そのユーザーへのメンションは行わない
        $mentionList = $this->mention->ignoreMention($mentionList, $bodyString);

        if (isset($mentionList[$user_name])) {
            // 自分は除く
            unset($mentionList[$user_name]);
        }

        if (count($mentionList) === 0) {
            // 他にメンションを飛ばす人がいなくなったらなにもしない
            return '';
        }

        return implode("\n", $mentionList);
    }

    /**
     * コメントをマージする.
     * @param $bodyString
     * @return string
     */
    public function mergeComment($bodyString): string
    {
        $comment = $this->parameter->getComment();

        if (!strlen($comment)) {
            // コメントがあればつける
            return $bodyString;
        }

        // githubメンションを通知先チャットのメンションへ置き換える
        $comment = $this->mention->replaceMentionGithub2Chat($comment);
        $comment = $this->getCommentFormat($comment);

        return $bodyString . $comment;
    }

    /**
     * ボディのフォーマットを返す.
     * @param string $title
     * @param string $url
     * @return string
     */
    public function getBodyFormat($title, $url): string
    {
        return "タイトル: {$title}\nURL: {$url}";
    }

    /**
     * コメントのフォーマットを返す.
     * @param string $comment
     * @return string
     */
    public function getCommentFormat($comment): string
    {
        return "\n\nコメント: {$comment}";
    }
}
