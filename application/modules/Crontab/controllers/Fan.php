<?php

/**
 * Created by PhpStorm.
 * User: Jaackie
 * Date: 2017/11/22
 * Time: 15:31
 */
class FanController extends base_controllerCron
{
    const LIMIT_COMMENT = 3;
    const LIMIT_REPLY = 3;
    const LIMIT_LIKE = 3;
    const LIMIT_SHARE = 2;
    const LIMIT_OPEN = 1;

    public function autoAction()
    {
        $config = new Yaf_Config_Ini(APPLICATION_PATH . '/conf/fan.ini', 'user');

        $fan = new fanModel($config->token, $config->uid);

        //打开应用
        for ($i = 0; $i < self::LIMIT_OPEN; $i++) {
            $res_share = $fan->addAction($fan::TYPE_ACTION_OPEN);
            $msg = $fan->isSuccess() ? '成功' : '失败(' . json_encode($res_share) . ')';
            self::__message('打开应用: ' . $msg);
        }

        //获取章节列表
        $res = $fan->getChapters(1, 30);
        if (!$fan->isSuccess()) {
            self::__halt('获取列表失败');
        }

        $chapter_id = 0;

        //点赞评论
        $like_count = 0;
        $comment_count = 0;
        foreach ($res['list'] as $chapter) {
            if (!$chapter['is_approve'] && $like_count < self::LIMIT_LIKE) {
                $res_like_chapter = $fan->addLike($chapter['id'], $fan::TYPE_LIKE_CHAPTER);
                $like_count++;
                $msg = $fan->isSuccess() ? '成功' : '失败(' . json_encode($res_like_chapter) . ')';
                self::__message('章节点赞: ' . $msg);
            }
            if ($comment_count < self::LIMIT_COMMENT) {
                $comment_count++;
                $comment = self::_randMessage();
                $res_comment = $fan->addComment($chapter['id'], $comment);
                $msg = $fan->isSuccess() ? "成功({$comment})" : '失败(' . json_encode($res_comment) . ')';
                self::__message('评论: ' . $msg);
            }
            if (!$chapter_id) {
                $chapter_id = $chapter['id'];
            }
            if ($comment_count >= self::LIMIT_COMMENT && $like_count >= self::LIMIT_LIKE) {
                break;
            }
        }

        if (!$like_count) {
            self::__message('章节未点赞');
        }

        //分享
        for ($i = 0; $i < self::LIMIT_SHARE; $i++) {
            $res_share = $fan->addAction($fan::TYPE_ACTION_SHARE);
            $msg = $fan->isSuccess() ? '成功' : '失败(' . json_encode($res_share) . ')';
            self::__message('分享: ' . $msg);
        }

        //回复他人/点赞
        $reply_count = 0;
        if ($chapter_id) {
            $comments = $fan->getComments($chapter_id, 1, 10);
            if (!$fan->isSuccess()) {
                self::__message('评论列表获取失败');
            } else {
                foreach ($comments['list'] as $comment) {
                    if ($reply_count < self::LIMIT_REPLY) {
                        $reply_count++;
                        $message = self::_randMessage();
                        $res_reply = $fan->addReply($comment['id'], $message, $comment['uid']);
                        $msg = $fan->isSuccess() ? "成功({$message})" : '失败(' . json_encode($res_reply) . ')';
                        self::__message('回复他人: ' . $msg);
                    }
                    if ($like_count < self::LIMIT_LIKE && !$comment['is_approve']) {
                        $res_like_chapter = $fan->addLike($comment['id'], $fan::TYPE_LIKE_COMMENT);
                        $like_count++;
                        $msg = $fan->isSuccess() ? '成功' : '失败(' . json_encode($res_like_chapter) . ')';
                        self::__message('评论点赞: ' . $msg);
                    }
                    if ($reply_count >= self::LIMIT_REPLY && $like_count >= self::LIMIT_LIKE) {
                        break;
                    }
                }
            }
        }

        //答题
        $questions = $fan->getQuestions();
        $msg = $questions['list'] ? '成功!' : '失败!';
        self::__message("获取答题列表: {$msg}");
        foreach ($questions['list'] as $question) {
            $res_answer = $fan->doAnswer($question['id'], rand(1, 4), $question['position']);
            self::__message('题目: ' . json_encode($question, JSON_UNESCAPED_UNICODE));
            if ($fan->isSuccess()) {
                self::__message('结果: ' . $res_answer['msg']);
            } else {
                self::__message('答题失败!');
            }
        }

        self::__halt('完成!');
    }


    /**
     * 随机字符串
     * @return string
     */
    private static function _randMessage()
    {
        $str1 = ['厉害了', '碉堡了', '牛逼了', '老铁了',];
        $str2 = ['我的', '你的', '大家的', '我家的', '南宫婉的',];
        $str3 = ['韩立', '韩老四', '韩老魔', '韩跑跑', '韩天尊', '韩侥幸', '二愣子'];
        $str4 = ['哟', '哈哈', '呵呵', '嘿', '',];
        $str5 = ['!', '', '..', '666'];
        return self::_r($str1) . self::_r($str2) . self::_r($str3) . self::_r($str4) . self::_r($str5);
    }

    /**
     * 数组随机字符串
     * @param $arr
     * @return mixed
     */
    private static function _r($arr)
    {
        return $arr[rand(0, count($arr) - 1)];
    }

}