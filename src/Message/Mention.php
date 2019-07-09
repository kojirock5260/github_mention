<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/08/02
 * Time: 10:27.
 */

namespace Kojirock5260\GithubMention\Message;

class Mention
{
    /**
     * @var array
     */
    private $engineers;

    /**
     * @var array
     */
    private $designers;

    /**
     * @var array
     */
    private $planners;

    /**
     * @var array
     */
    private $pullRequestAutoMentionIgnore;

    /**
     * Mention constructor.
     * @param array $engineers
     * @param array $designers
     * @param array $planners
     * @param array $pullRequestAutoMentionIgnore
     */
    public function __construct(array $engineers, array $designers, array $planners, array $pullRequestAutoMentionIgnore)
    {
        $this->engineers                     = $engineers;
        $this->designers                     = $designers;
        $this->planners                      = $planners;
        $this->pullRequestAutoMentionIgnore  = $pullRequestAutoMentionIgnore;
    }

    /**
     * 自分が所属するグループのリストを取得.
     * @param string $userName
     * @return array
     */
    public function getMyGroupMentionList(string $userName): array
    {
        $mentionList = [];

        if (isset($this->engineers[$userName])) {
            $mentionList = $this->engineers;
        } elseif (isset($this->designers[$userName])) {
            $mentionList = $this->designers;
        }

        return $this->compilePullRequestAutoMentionIgnore($mentionList);
    }

    /**
     * 指定の文章にメンションがついていれば飛ばすメンションを追加する.
     * @param array  $mentionList
     * @param string $targetString
     * @return array
     */
    public function addMention(array $mentionList, string $targetString): array
    {
        // 指定の文章にメンションを書いているかどうかを調べる
        $pattern = implode('|', $this->getGithubMentionList());
        preg_match_all("/{$pattern}/", $targetString, $matches);

        if (!isset($matches[0])) {
            return $mentionList;
        }

        // 指定の文章にメンションがある場合は、そのユーザーにもメンションを飛ばすため配列に追加する
        foreach ($matches[0] as $match) {
            $mentionUserName = substr($match, 1);
            $allMentionList  = $this->getAllMentionList();

            if (isset($allMentionList[$mentionUserName])) {
                $mentionList[$mentionUserName] = $allMentionList[$mentionUserName];
            }
        }

        return $mentionList;
    }

    /**
     * 指定の文章に否定メンションがある場合は、そのユーザーへのメンションは行わない.
     * @param array  $mentionList
     * @param string $targetString
     * @return array
     */
    public function ignoreMention(array $mentionList, string $targetString): array
    {
        // 指定の文章に否定メンションがある場合は、そのユーザーへのメンションは行わない
        $pattern = implode('|-', $this->getGithubMentionList());
        preg_match_all("/-{$pattern}/", $targetString, $matches);

        if (!isset($matches[0])) {
            return $mentionList;
        }

        foreach ($matches[0] as $match) {
            $mentionUserName = substr($match, 2);

            if (isset($mentionList[$mentionUserName])) {
                unset($mentionList[$mentionUserName]);
            }
        }

        return $mentionList;
    }

    /**
     * Githubメンションを通知先チャットへのメンションへ置き換える.
     * @param string $comment
     * @return string
     */
    public function replaceMentionGithub2Chat(string $comment): string
    {
        $githubMention = $this->getGithubMentionList();
        $targetMention = array_values($this->getAllMentionList());

        return str_replace($githubMention, $targetMention, $comment);
    }

    /**
     * Githubのメンションリストを取得.
     * @return array
     */
    public function getGithubMentionList(): array
    {
        $mentionList   = $this->getAllMentionList();
        $githubMention = array_map(function ($val) {
            return '@' . $val;
        }, array_keys($mentionList));

        return $githubMention;
    }

    /**
     * 全メンションリストを取得.
     * @return array
     */
    public function getAllMentionList(): array
    {
        return $this->engineers + $this->designers + $this->planners;
    }

    /**
     * 該当のメンションリストに自動メンション除外設定を適用する.
     * @param array $mentionList
     * @return array
     */
    private function compilePullRequestAutoMentionIgnore(array $mentionList): array
    {
        // 自動メンション除外リストにある名前を削除
        foreach ($this->pullRequestAutoMentionIgnore as $ignoreName) {
            if (isset($mentionList[$ignoreName])) {
                unset($mentionList[$ignoreName]);
            }
        }

        return $mentionList;
    }
}
