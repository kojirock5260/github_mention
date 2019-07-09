<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: yudai_fujita
 * Date: 2018/08/15
 * Time: 4:46.
 */

namespace Kojirock5260\GithubMention\Message;

interface NotifierInterface
{
    /**
     * 通知実行.
     */
    public function send(): void;
}
