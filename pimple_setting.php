<?php

declare(strict_types=1);

/*
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/08/02
 * Time: 10:05.
 */
use Kojirock5260\GithubMention\Chatwork\ChatworkNotifier;
use Kojirock5260\GithubMention\Github\GithubWebhookParameter;
use Kojirock5260\GithubMention\Message\BodyMaker;
use Kojirock5260\GithubMention\Message\Mention;
use Kojirock5260\GithubMention\Message\MessageMaker;
use Kojirock5260\GithubMention\Message\TitleMaker;
use Kojirock5260\GithubMention\Slack\SlackNotifier;
use Maknz\Slack\Client as SlackClient;
use Pimple\Container;

if (!file_exists(__DIR__ . '/config/setting.ini')) {
    throw new \LogicException("setting.ini not found");
}

$iniSetting = parse_ini_file(__DIR__ . '/config/setting.ini', true);
$container  = new Container();

/*
 * Slackメッセージ
 * @return \Maknz\Slack\Message
 */
$container['slack_message'] = function () use ($iniSetting) {
    $hook_url = $iniSetting['slack']['hook_url'];
    return (new SlackClient($hook_url, [
        'channel'      => $iniSetting['slack']['channel'],
        'link_names'   => (int) $iniSetting['slack']['link_names'],
        'unfurl_links' => (int) $iniSetting['slack']['unfurl_links'],
    ]))->createMessage();
};

/*
 * 通知クラス
 * @param Container $c
 * @return \Kojirock5260\GithubMention\Message\NotifierInterface
 */
$container['notifier'] = function ($c) use ($iniSetting) {
    switch ($iniSetting['chat_service']) {
        case 'slack':
            $notifier = new SlackNotifier($c['slack_message'], $c['message_maker']);
            break;
        case 'chatwork':
            $notifier = new ChatworkNotifier(
                \Polidog\Chatwork\Chatwork::create($iniSetting['chatwork']['token']),
                $c['message_maker'],
                (int) $iniSetting['chatwork']['room_id']
            );
            break;
        default:
            throw new \InvalidArgumentException("invalid chat service {$iniSetting['chat_service']}");
    }

    return $notifier;
};

/*
 * MessageMakerクラス
 * @param Container $c
 * @return MessageMaker
 */
$container['message_maker'] = function ($c) {
    return new MessageMaker($c['body_maker'], $c['title_maker']);
};

/*
 * Githubパラメータクラス
 * @return GithubWebhookParameter
 */
$container['github_webhook_parameter'] = function () {
    return new GithubWebhookParameter($_POST['payload']);
};

/*
 * Mentionクラス
 * @return Mention
 */
$container['message_mention'] = function () use ($iniSetting) {
    return new Mention(
        $iniSetting[$iniSetting['chat_service']]['mention_engineers'],
        $iniSetting[$iniSetting['chat_service']]['mention_designers'],
        $iniSetting[$iniSetting['chat_service']]['mention_planners'],
        $iniSetting['github']['pull_request_auto_mention_ignore']
    );
};

/*
 * BodyMakerクラス
 * @param Container $c
 * @return BodyMaker
 */
$container['body_maker'] = function ($c) use ($iniSetting) {
    return new BodyMaker(
        $c['github_webhook_parameter'],
        $c['message_mention'],
        $iniSetting[$iniSetting['chat_service']]['pull_request_image']
    );
};

/*
 * TitleMakerクラス
 * @param Container $c
 * @return TitleMaker
 */
$container['title_maker'] = function ($c) {
    return new TitleMaker($c['github_webhook_parameter']);
};
