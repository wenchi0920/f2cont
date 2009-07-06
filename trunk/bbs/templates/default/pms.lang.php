<?php

// P.M. Pack for Discuz! Version 1.0
// Translated by Crossday

// ATTENTION: Please add slashes(\) before (') and (")

$language = array
(

	'reason_moderate_subject' => '您發表的主題被執行管理操作',
	'reason_moderate_message' => '這是由論壇系統自動發送的通知短消息。

[b]以下您所發表的主題被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 執行 {$modaction} 操作。[/b]

[b]主題:[/b] [url={$boardurl}viewthread.php?tid={$thread[tid]}]{$thread[subject]}[/url]
[b]發表時間:[/b] {$thread[dateline]}
[b]所在版塊:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]

[b]操作理由:[/b] {$reason}

如果您對本管理操作有異議，請與我取得聯繫。',

	'reason_merge_subject' => '您發表的主題被執行合併操作',
	'reason_merge_message' => '這是由論壇系統自動發送的通知短消息。

[b]以下您所發表的主題被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 執行 {$modaction} 操作。[/b]

[b]主題:[/b] {$thread[subject]}
[b]發表時間:[/b] {$thread[dateline]}
[b]所在版塊:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]

[b]合併後的主題:[/b] [url={$boardurl}viewthread.php?tid={$thread[tid]}]{$other[subject]}[/url]

[b]操作理由:[/b] {$reason}

如果您對本管理操作有異議，請與我取得聯繫。',
	'reason_delete_post_subject' => '您發表的回復被執行管理操作',
	'reason_delete_post_message' => '這是由論壇系統自動發送的通知短消息。

[b]以下您所發表的回復被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 執行 {$modaction} 操作。[/b]
[quote]{$post[message]}[/quote]

[b]發表時間:[/b] {$post[dateline]}
[b]所在版塊:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]

[b]操作理由:[/b] {$reason}

如果您對本管理操作有異議，請與我取得聯繫。',

	'reason_ban_post_subject' => '您發表的回復被執行管理操作',
	'reason_ban_post_message' => '這是由論壇系統自動發送的通知短消息。

[b]以下您所發表的帖子被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 執行 {$modaction} 操作。[/b]
[quote]{$post[message]}[/quote]

[b]發表時間:[/b] {$post[dateline]}
[b]所在版塊:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]

[b]操作理由:[/b] {$reason}

如果您對本管理操作有異議，請與我取得聯繫。',

	'reason_warn_post_subject' => '您發表的帖子被執行管理操作',
	'reason_warn_post_message' => '這是由論壇系統自動發送的通知短消息。

[b]以下您所發表的帖子被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 執行 {$modaction} 操作。[/b]
[quote]{$post[message]}[/quote]
連續 $warningexpiration 天內累計 $warninglimit 次警告，您將被自動禁止發帖 $warningexpiration 天。
截至目前，您已被警告 $authorwarnings 次，請注意！

[b]發表時間:[/b] {$post[dateline]}
[b]所在版塊:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]

[b]操作理由:[/b] {$reason}

如果您對本管理操作有異議，請與我取得聯繫。',

	'reason_move_subject' => '您發表的主題被執行管理操作',
	'reason_move_message' => '這是由論壇系統自動發送的通知短消息。

[b]以下您所發表的主題被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 執行 移動 操作。[/b]

[b]主題:[/b] [url={$boardurl}viewthread.php?tid={$thread[tid]}]{$thread[subject]}[/url]
[b]發表時間:[/b] {$thread[dateline]}
[b]原論壇:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]
[b]目標論壇:[/b] [url={$boardurl}forumdisplay.php?fid={$toforum[fid]}]{$toforum[name]}[/url]

[b]操作理由:[/b] {$reason}

如果您對本管理操作有異議，請與我取得聯繫。',

	'rate_reason_subject' => '您發表的帖子被評分',
	'rate_reason_message' => '這是由論壇系統自動發送的通知短消息。

[b]以下您所發表的帖子被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 評分。[/b]
[quote]{$post[message]}[/quote]

[b]發表時間:[/b] {$post[dateline]}
[b]所在版塊:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]
[b]所在主題:[/b] [url={$boardurl}viewthread.php?tid={$tid}&page={$page}#pid{$pid}]{$thread[subject]}[/url]

[b]評分分數:[/b] {$ratescore}
[b]操作理由:[/b] {$reason}',

	'rate_removereason_subject' => '您發表的帖子的評分被撤銷',
	'rate_removereason_message' => '這是由論壇系統自動發送的通知短消息。

[b]以下您所發表帖子的評分被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 撤銷。[/b]
[quote]{$post[message]}[/quote]

[b]發表時間:[/b] {$post[dateline]}
[b]所在版塊:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]
[b]所在主題:[/b] [url={$boardurl}viewthread.php?tid={$tid}&page={$page}#pid{$pid}]{$thread[subject]}[/url]

[b]評分分數:[/b] {$ratescore}
[b]操作理由:[/b] {$reason}',

	'transfer_subject' => '您收到一筆積分轉賬',
	'transfer_message' => '這是由論壇系統自動發送的通知短消息。

[b]您收到一筆來自他人的積分轉賬。[/b]

[b]來自:[/b] [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url]
[b]時間:[/b] {$transfertime}
[b]積分:[/b] {$extcredits[$creditstrans][title]} {$amount} {$extcredits[$creditstrans][unit]}
[b]淨收入:[/b] {$extcredits[$creditstrans][title]} {$netamount} {$extcredits[$creditstrans][unit]}

[b]附言:[/b] {$transfermessage}

詳情請[url={$boardurl}memcp.php?action=creditslog]點擊這裡[/url]訪問您的積分轉賬與兌換記錄。',

	'reportpost_subject'	=> '$discuz_userss 向您報告一篇帖子',
	'reportpost_message'	=> '[i]{$discuz_userss}[/i] 向您報告以下的帖子，詳細內容請訪問:
[url]{$posturl}[/url]

他/她的報告理由是: {$reason}',

	'addfunds_subject' => '積分充值成功完成',
	'addfunds_message' => '這是由論壇系統自動發送的通知短消息。

[b]您提交的積分充值請求已成功完成，相應數額的積分已經存入您的積分賬戶。[/b]

[b]訂單號:[/b] {$order[orderid]}
[b]提交時間:[/b] {$submitdate}
[b]確認時間:[/b] {$confirmdate}

[b]支出:[/b] 人民幣 {$order[price]} 元
[b]收入:[/b] {$extcredits[$creditstrans][title]} {$order[amount]} {$extcredits[$creditstrans][unit]}

詳情請[url={$boardurl}memcp.php?action=creditslog]點擊這裡[/url]訪問您的積分轉賬與兌換記錄。',

	'trade_seller_send_subject' => '有買家購買您的商品',
	'trade_seller_send_message' => '這是由論壇系統自動發送的通知短消息。

買家 {$user} 購買您的商品 {$itemsubject}

買家已付款，等待您發貨，請[url={$boardurl}trade.php?orderid={$orderid}]點擊這裡[/url]查看詳情。',

	'trade_buyer_confirm_subject' => '您購買的商品已經發貨',
	'trade_buyer_confirm_message' => '這是由論壇系統自動發送的通知短消息。

您購買的商品 {$itemsubject}

賣家 {$user} 已發貨，等待您的確認，請[url={$boardurl}trade.php?orderid={$orderid}]點擊這裡[/url]查看詳情。',

	'trade_fefund_success_subject' => '您購買的商品已成功退款',
	'trade_fefund_success_message' => '這是由論壇系統自動發送的通知短消息。

商品 {$itemsubject} 已退款成功，請[url={$boardurl}trade.php?orderid={$orderid}]點擊這裡[/url]給對方評分。',

	'trade_success_subject' => '商品交易已成功完成',
	'trade_success_message' => '這是由論壇系統自動發送的通知短消息。

商品 {$itemsubject} 已交易成功，請[url={$boardurl}trade.php?orderid={$orderid}]點擊這裡[/url]給對方評分。',

	'activity_apply_subject' => '活動的申請已通過批准',
	'activity_apply_message' => '這是由論壇系統自動發送的通知短消息。

活動 [b]{$activity_subject}[/b] 的發起者已批准您參加此活動，請[url={$boardurl}viewthread.php?tid={$tid}]點擊這裡[/url]查看詳情。',

	'activity_delete_subject' => '您申請的活動被發起者拒絕',
	'activity_delete_message' => '這是由論壇系統自動發送的通知短消息。

您申請的活動 [b]{$activity_subject}[/b] 已被發起者拒絕，請[url={$boardurl}viewthread.php?tid={$tid}]點擊這裡[/url]查看詳情。',

	'reward_question_subject' => '您發表的懸賞被設置了最佳答案',
	'reward_question_message' => '這是由論壇系統自動發送的通知短消息。

[b]您發表的懸賞被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 設置了 最佳答案。[/b]

[b]懸賞:[/b] [url={$boardurl}viewthread.php?tid={$thread[tid]}]{$thread[subject]}[/url]
[b]發表時間:[/b] {$thread[dateline]}
[b]所在版塊:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forum[name]}[/url]

如果您對本操作有異議，請與作者取得聯繫。',

	'reward_bestanswer_subject' => '您發表的回復被選為最佳答案',
	'reward_bestanswer_message' => '這是由論壇系統自動發送的通知短消息。

[b]您的回復被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 選為懸賞最佳答案。[/b]

[b]懸賞:[/b] [url={$boardurl}viewthread.php?tid={$thread[tid]}]{$thread[subject]}[/url]
[b]發表時間:[/b] {$thread[dateline]}
[b]所在版塊:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forum[name]}[/url]

如果您對本操作有異議，請與作者取得聯繫。',

	'modthreads_delete_subject' => '您發表的主題審核失敗',
	'modthreads_delete_message' => '這是由論壇系統自動發送的通知短消息。

[b]審核失敗:[/b] 您發表的主題 [b][u] {$threadsubject} [/u][/b] 沒有通過審核，現已被刪除!
[b]操作理由:[/b] {$reason}

如果您對本管理操作有異議，請與我取得聯繫。',

	'modthreads_validate_subject' => '您發表的主題已審核通過',
	'modthreads_validate_message' => '這是由論壇系統自動發送的通知短消息。

[b]審核通過:[/b] 您發表的主題 [url={$boardurl}viewthread.php?tid={$tid}]{$threadsubject}[/url] 已經審核通過!
[b]操作理由:[/b] {$reason}

如果您對本管理操作有異議，請與我取得聯繫。',

	'modreplies_delete_subject' => '您發表的回覆審核失敗',
	'modreplies_delete_message' => '這是由論壇系統自動發送的通知短消息。

[b]審核失敗:[/b] 您發表回覆沒有通過審核，現已被刪除!
[b]所在主題:[/b] [url={$boardurl}viewthread.php?tid={$tid}]點此查看[/url]
[b]回復內容:[/b]
[quote]
	$post
[/quote]
[b]操作理由:[/b] {$reason}

如果您對本管理操作有異議，請與我取得聯繫。',

	'modreplies_validate_subject' => '您發表的回復已審核通過',
	'modreplies_validate_message' => '這是由論壇系統自動發送的通知短消息。

[b]審核通過:[/b] 您發表的回復已經審核通過。
[b]所在主題:[/b] [url={$boardurl}viewthread.php?tid={$tid}]點此查看[/url]
[b]回復內容:[/b]
[quote]
	$post
[/quote]
[b]操作理由:[/b] {$reason}

如果您對本管理操作有異議，請與我取得聯繫。',

	'magics_sell_subject' => '您的道具成功出售',
	'magics_sell_message' => '您的 {$magic[name]} 道具被 {$discuz_userss} 購買，獲得收益 {$totalcredit}',

	'magics_receive_subject' => '您收到好友送來的道具',
	'magics_receive_message' => '你收到 {$discuz_userss} 送給你 {$magicarray[$magicid][name]} 道具，請到[url={$boardurl}magic.php]我的道具箱[/url]查收',

	'reason_copy_subject' => '您發表的主題被執行複製操作',
	'reason_copy_message' => '這是由論壇系統自動發送的通知短消息。

[b]以下您所發表的主題被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 執行 {$modaction} 操作。[/b]

[b]主題:[/b] {$thread[subject]}
[b]發表時間:[/b] {$thread[dateline]}
[b]所在版塊:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]

[b]複製後的主題:[/b] [url={$boardurl}viewthread.php?tid=$threadid]{$thread[subject]}[/url]

[b]操作理由:[/b] {$reason}

如果您對本管理操作有異議，請與我取得聯繫。',

	'eccredit_subject' => '商品交易的對方已經評價，請回評',
	'eccredit_message' => '這是由論壇系統自動發送的通知短消息。

[url={$boardurl}trade.php?orderid=$orderid]查看交易單[/url]

與您交易的 $discuz_userss 已經給您作了評價，請盡快評價對方。',

	'buddy_new_subject' => '{$discuz_userss} 添加您為好友',
	'buddy_new_message' => '這是由系統自動發送的通知短消息。

{$discuz_userss} 添加您為好友。
您可以[url={$boardurl}space.php?uid=$discuz_uid]點擊這裡[/url]查看他(她)的個人資料，或者[url={$boardurl}my.php?item=buddylist&newbuddyid={$discuz_uid}&buddysubmit=yes]把他(她)加為您的好友[/url]。',
	'buddy_new_uch_message' => '這是由系統自動發送的通知短消息。

{$discuz_userss} 添加您為好友。
您可以[url={$boardurl}space.php?uid=$discuz_uid]點擊這裡[/url]查看他(她)的個人資料，或者[url={$boardurl}my.php?item=buddylist&newbuddyid={$discuz_uid}&buddysubmit=yes]把他(她)加為您的好友[/url]。
也可以[url={$uchomeurl}/space.php?uid={$discuz_uid}]點擊這裡[/url]查看他(她)的個人空間。',

	'task_reward_subject' => '完成任務獎勵通知',
	'task_reward_credit_message' => '這是由論壇系統自動發送的通知短消息。
{$discuz_userss} 恭喜您完成任務 [url={$boardurl}task.php?action=view&id={$task[taskid]}]{$task[name]}[/url]
您已得到的積分獎勵如下：
	{$extcredits[$task[prize]][title]} {$task[bonus]} {$extcredits[$task[prize]][unit]}
	[url={$boardurl}memcp.php?action=credits]點這裡查看我的積分[/url] [url={$boardurl}memcp.php?action=creditslog&operation=creditslog]點這裡查看積分收益記錄[/url]
希望您再接再厲！',
	'task_reward_magic_message' => '這是由論壇系統自動發送的通知短消息。
{$discuz_userss} 恭喜您完成任務 [url={$boardurl}task.php?action=view&id={$task[taskid]}]{$task[name]}[/url]
您已得到的道具獎勵如下：
	[url={$boardurl}magic.php]{$magicname}[/url] {$task[bonus]} 枚
希望您再接再厲！',
	'task_reward_medal_message' => '這是由論壇系統自動發送的通知短消息。
{$discuz_userss} 恭喜您完成任務 [url={$boardurl}task.php?action=view&id={$task[taskid]}]{$task[name]}[/url]
您已得到的勳章獎勵如下：
	[url={$boardurl}medal.php]{$medalname}[/url] 有效期 {$task[bonus]} 天
希望您再接再厲！',
	'task_reward_invite_message' => '這是由論壇系統自動發送的通知短消息。
{$discuz_userss} 恭喜您完成任務 [url={$boardurl}task.php?action=view&id={$task[taskid]}]{$task[name]}[/url]
您已得到的邀請碼獎勵如下：
	共 [url={$boardurl}invite.php]{$task[prize]}[/url] 個 有效期 {$task[bonus]} 天
	{$rewards}
希望您再接再厲！',
	'task_reward_group_message' => '這是由論壇系統自動發送的通知短消息。
{$discuz_userss} 恭喜您完成任務 [url={$boardurl}task.php?action=view&id={$task[taskid]}]{$task[name]}[/url]
您已得到的特殊用戶組獎勵如下：
	用戶組 {$grouptitle} 有效期 {$task[bonus]} 天  [url={$boardurl}memcp.php?action=usergroups]點這裡查看當前用戶組[/url]
希望您再接再厲！',

);

?>