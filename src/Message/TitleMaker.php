<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/08/02
 * Time: 9:49.
 */

namespace Kojirock5260\GithubMention\Message;

use Kojirock5260\GithubMention\Github\GithubWebhookParameter;

class TitleMaker
{
    /**
     * @var GithubWebhookParameter
     */
    private $parameter;

    /**
     * TitleMaker constructor.
     * @param GithubWebhookParameter $parameter
     */
    public function __construct(GithubWebhookParameter $parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * 通知用タイトルを作成して返す.
     * @return string
     */
    public function getTitleString(): string
    {
        $title_string = '';

        if ($this->parameter->isOpenedAction()) {
            // 新規登録時
            $title_string = $this->getTitleStringByCreate();
        } elseif ($this->parameter->isCreatedAction()) {
            // コメント追加時
            $title_string = $this->getTitleStringByComment();
        } elseif ($this->parameter->isClosedAction()) {
            // クローズ時
            $title_string = $this->getTitleStringByClose();
        } elseif ($this->parameter->isSubmittedAction() && $this->parameter->isReview()) {
            // レビュー評価時
            $title_string = $this->getTitleStringByReview();
        }

        return $title_string;
    }

    /**
     * レポジトリラベルを返す.
     * @return string
     */
    public function getRepositoryLabel(): string
    {
        return $this->parameter->getRepositoryLabel();
    }

    /**
     * 新規作成時のタイトル文字を返す.
     * @return string
     */
    public function getTitleStringByCreate(): string
    {
        $result = $this->getRepositoryLabel() . "\n";

        if ($this->parameter->isIssue()) {
            $result .= '新たにIssueが作成されました。';
        } else {
            $result .= '新たにPull Requestが作成されました。';
        }

        return $result;
    }

    /**
     * コメント追加時のタイトル文字を返す.
     * @return string
     */
    public function getTitleStringByComment(): string
    {
        return $this->getRepositoryLabel() . "\nコメントが追加されました。";
    }

    /**
     * issue・プルリクのクローズ時とブランチマージ時のタイトル文字を返す.
     * @return string
     */
    public function getTitleStringByClose(): string
    {
        $result = $this->getRepositoryLabel() . "\n";

        if ($this->parameter->isIssue()) {
            $result .= 'Issueがクローズされました！';
        } elseif ($this->parameter->isMerged()) {
            $result .= 'Pull Requestがマージされました！';
        } else {
            $result .= 'Pull Requestがクローズされました。';
            return $result;
        }
        $result .= 'お疲れ様でした！';

        return $result;
    }

    /**
     * レビュー時のタイトル文字を返す.
     * @return string
     */
    public function getTitleStringByReview(): string
    {
        $result = $this->getRepositoryLabel() . "\n";

        if ($this->parameter->isPullRequestApprove()) {
            // レビュー許可
            $result .= 'Pull Requestが許可されました！';
        } elseif ($this->parameter->isPullRequestReject()) {
            // レビュー却下
            $result .= "Pull Requestが却下されました！\n確認してください！";
        } else {
            // その他(ただのコメント)
            if (strlen($this->parameter->getComment()) === 0) {
                // コメントがなければなにもさせない
                return '';
            }
            $result .= 'Pull Requestにコメントがつきました。';
        }

        return $result;
    }
}
