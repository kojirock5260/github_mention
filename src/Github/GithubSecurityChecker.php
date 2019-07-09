<?php

declare(strict_types=1);

namespace Kojirock5260\GithubMention\Github;

class GithubSecurityChecker
{
    /**
     * github webhook時のsignatureチェック.
     * @param string $secret
     * @return bool
     */
    public static function isValid(string $secret): bool
    {
        // Signatureの存在確認
        $headers = getallheaders();

        if (!isset($headers['X-Hub-Signature'])) {
            return false;
        }

        // Signatureの情報を取得
        list($algo, $hash) = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2) + ['', ''];

        // SecretKeyとSignatureの突き合わせ
        $raw  = file_get_contents('php://input');
        $hmac = hash_hmac($algo, $raw, $secret);

        if ($hash !== $hmac) {
            return false;
        }

        return true;
    }
}
