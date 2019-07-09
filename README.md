# githubメンション変換ツールです

## サポートしているチャット

* slack
* chatwork

それぞれ通知が出来るchannel, roomは1つだけです。

## 使用方法

1. `config/setting.ini.org` を `config/setting.ini` としてコピーを作成してください。

2. 作成した `config/setting.ini` に所定の情報を入力していってください。

    * `mention_engineers`, `mention_planners`, `mention_designers` のkeyにはgithubのユーザー名、valueにはそれぞれチャットーサビスのメンションを登録してください。

3. github webhookの通知先をこのプログラムの `public/notification.php` へ向けてください。

## 説明

* github上でメンションをつけると、使用するchatサービスのメンションに変換してくれます。
* プルリクを作成した際は、プルリクを作成したユーザーと同じグループ(エンジニアの場合は `mention_engineers`)の人たち全員に自動でメンションを付与します。
* `pull_request_auto_mention_ignore` を設定することで プルリク時の自動メンション付与を除外するユーザーを設定できます。
* `pull_request_auto_mention_ignore` を設定したくないけど、このプルリクだけは自動メンションを付与したくないという場合は、プルリク作成時に `-@xxxx` という感じで、 `-` をつけてあげるとメンション付与を除外します。
* `start_time` `end_time` を指定することで、会社の定時に合わせてメンションをつけるかどうかを設定できます。何時でメンションをつけてもいい場合は `start_time: 00:00:00` `end_time: 23:59:59` と指定してください。
