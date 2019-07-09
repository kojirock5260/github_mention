<?php

declare(strict_types=1);

ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../pimple_setting.php';

// Githubのセキュリティチェック
if (!\Kojirock5260\GithubMention\Github\GithubSecurityChecker::isValid($iniSetting['github']['secret_key'])) {
    die('NG');
}

// チャット通知クラスを取得
$notifier = $container['notifier'];

// 通知実行
$notifier->send();
