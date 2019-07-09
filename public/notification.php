<?php

declare(strict_types=1);

require_once __DIR__ . '/../pimple_setting.php';

// Githubのセキュリティチェック
if (!\Kojirock5260\GithubMention\Github\GithubSecurityChecker::isValid(
    $iniSetting['github']['secret_key'],
    $_SERVER['HTTP_X_HUB_SIGNATURE']
)) {
    throw new \InvalidArgumentException('github token error');
}

// チャット通知クラスを取得
$notifier = $container['notifier'];

// 通知実行
$notifier->send();
