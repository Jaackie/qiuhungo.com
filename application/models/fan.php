<?php

/**
 * Created by PhpStorm.
 * User: Jaackie
 * Date: 2017/12/22 0022
 * Time: 10:46
 */
class fanModel extends base_model
{
    const DOMAIN = 'http://xjzm.mopian.tv';

    const TYPE_LIKE_CHAPTER = 3;         //评论章节
    const TYPE_LIKE_COMMENT = 5;           //点赞回复

    const TYPE_ACTION_OPEN = 5;     //登录
    const TYPE_ACTION_SHARE = 4;    //分享

    private $_token = '';
    private $_uid = 0;
    private $_is_success = false;
    /**
     * @var Curl
     */
    private $_curl;

    public function __construct($token, $uid)
    {
        $this->_token = $token;
        $this->_uid = $uid;
        $this->_curl = Curl::getInstance()->setClear();
    }

    /**
     * 获取最新章节列表
     * @param int $page
     * @param int $pageSize
     * @return mixed
     */
    public function getChapters($page = 1, $pageSize = 30)
    {
        $uri = '/book/book/api/novel/findChapter';
        $params = [
            'page' => $page,
            'pageSize' => $pageSize,
            'sort_order' => 'desc'
        ];
        return $this->_getResult($uri, $params);
    }

    /**
     * 添加评论
     * @param $targetId
     * @param $content
     * @return mixed
     */
    public function addComment($targetId, $content)
    {
        $uri = '/book/book/api/comment/add_comment';
        $params = [
            'timestamp' => time(),
            'target_id' => $targetId,
            'type' => 3,    //3:评论, 5:回复
            'content' => $content
        ];
        return $this->_getResult($uri, $params, false);
    }

    /**
     * 添加回复
     * @param $targetId
     * @param $content
     * @param $toUid
     * @return mixed
     */
    public function addReply($targetId, $content, $toUid)
    {
        $uri = '/book/book/api/comment/add_comment';
        $params = [
            'timestamp' => time(),
            'target_id' => $targetId,
            'type' => 5,    //3:评论, 5:回复
            'content' => $content,
            'to_uid' => $toUid
        ];
        return $this->_getResult($uri, $params, false);
    }

    /**
     * 点赞
     * @param $targetId
     * @param $type
     * @return mixed
     */
    public function addLike($targetId, $type = self::TYPE_LIKE_COMMENT)
    {
        $uri = '/book/api/app/approve';
        $params = [
            'timestamp' => time(),
            'target_id' => $targetId,
            'type' => $type
        ];
        return $this->_getResult($uri, $params, false);
    }

    /**
     * 分享/打开应用
     * @param $type
     * @return mixed
     */
    public function addAction($type = self::TYPE_ACTION_SHARE)
    {
        $uri = '/book/book/api/action/addAction';
        $params = [
            'action_type' => $type
        ];
        return $this->_getResult($uri, $params);
    }

    /**
     * 获取评论列表
     * @param $targetId
     * @param int $page
     * @param int $pageSize
     * @return mixed
     */
    public function getComments($targetId, $page = 1, $pageSize = 10)
    {
        $uri = '/book/book/api/comment/list';
        $params = [
            'page' => $page,
            'pageSize' => $pageSize,
            'target_id' => $targetId,
            'type' => 3
        ];
        return $this->_getResult($uri, $params);
    }

    /**
     * 获取答题列表
     * @return mixed
     */
    public function getQuestions()
    {
        $uri = '/book/book/api/test/list';
        return $this->_getResult($uri);
    }

    /**
     * 答题
     * @param $id
     * @param $choice
     * @param $position
     * @return mixed
     */
    public function doAnswer($id, $choice, $position)
    {
        $uri = '/book/book/api/test/getAnswer';
        $params = [
            'id' => $id,
            'choice' => $choice,
            'position' => $position
        ];
        return $this->_getResult($uri, $params);
    }

    /**
     * 结果是否成功
     * @return bool
     */
    public function isSuccess()
    {
        return $this->_is_success;
    }


    /**
     * 获取结果
     * @param $uri
     * @param array $params
     * @param bool $isGet
     * @param bool $needDefaultParams
     * @param bool $isJson
     * @return mixed
     */
    private function _getResult($uri, $params = [], $isGet = true, $needDefaultParams = true, $isJson = true)
    {
        if ($needDefaultParams) {
            $params['token'] = $this->_token;
            $params['uid'] = $this->_uid;
        }
        if ($isGet) {
            $this->_curl->setGetParam($params);
        } else {
            $this->_curl->setPostParam($params);
        }
        $res = $this->_curl->setUrl(self::DOMAIN . $uri)->getResult($isJson);
        return $this->_dealResult($res);
    }

    /**
     * 结果解析
     * @param $res
     * @return mixed
     */
    private function _dealResult($res)
    {
        if (is_array($res) && $res['code'] === 0) {
            $this->_is_success = true;
            return $res['rs'];
        } else {
            $this->_is_success = false;
            return $res;
        }
    }

}