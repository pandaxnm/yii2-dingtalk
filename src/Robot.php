<?php
/**
 * Created by PhpStorm.
 * User: chaojie.xiong
 * Date: 2019/8/13
 * Time: 10:27 AM
 */

namespace Pandaxnm\DingTalk;

use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\httpclient\Client;

class Robot extends Component {

    /**
     * @var string
     */
    public $apiUrl = 'https://oapi.dingtalk.com/robot/send';

    /**
     * @var string
     */
    public $accessToken;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if($this->accessToken === null){
            throw new InvalidConfigException('access token can not be empty');
        }
    }

    /**
     * 发送文本消息
     * @param $content
     * @param array $at
     * @return bool
     * @throws \Exception
     */
    public function sendTextMsg($content, $at = [])
    {
        $msg = [
            'msgtype' => 'text',
            'text' => [
                'content' => $content,
            ],
        ];

        $this->getAt($at, $msg);
        return $this->send($msg);
    }

    /**
     * 发送链接消息
     * @param $title
     * @param $text
     * @param $messageUrl
     * @param string $picUrl
     * @return bool
     * @throws \Exception
     */
    public function sendLinkMsg($title, $text, $messageUrl, $picUrl = '')
    {
        $msg = [
            'msgtype' => 'link',
            'link' => [
                'title' => $title,
                'text' => $text,
                'messageUrl' => $messageUrl,
                'picUrl' => $picUrl,
            ]
        ];

        return $this->send($msg);
    }

    /**
     * 发送MarkDown消息
     * @param $title
     * @param $text
     * @param $at
     * @return bool
     * @throws \Exception
     */
    public function sendMarkdownMsg($title, $text, $at)
    {
        $msg = [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $title,
                'text' => $text,
            ]
        ];
        $this->getAt($at, $msg);
        return $this->send($msg);
    }

    /**
     * 发送整体跳转ActionCard消息
     * @param $title
     * @param $text
     * @param $singleURL
     * @param string $singleTitle
     * @param int $hideAvatar
     * @param int $btnOrientation
     * @return bool
     * @throws \Exception
     */
    public function sendActionCardMsg($title, $text, $singleURL, $singleTitle = '阅读更多', $hideAvatar = 0, $btnOrientation = 0)
    {
        $msg = [
            'msgtype' => 'actionCard',
            'actionCard' => [
                'title' => $title,
                'text' => $text,
                'singleURL' => $singleURL,
                'singleTitle' => $singleTitle,
                'btnOrientation' => $btnOrientation,
                'hideAvatar' => $hideAvatar,
            ]
        ];
        return $this->send($msg);
    }

    /**
     * 发送独立跳转ActionCard消息
     * @param $title
     * @param $content
     * @param array $btns
     * @param int $hideAvatar
     * @param int $btnOrientation
     * @return bool
     * @throws \Exception
     */
    public function sendSingleActionCardMsg($title, $content, array $btns=[], $hideAvatar = 0, $btnOrientation = 0)
    {
        $msg = [
            'msgtype' => 'actionCard',
            'actionCard' => [
                'title' => $title,
                'text' => $content,
                'btns' => $btns,
                'hideAvatar' => $hideAvatar,
                'btnOrientation' => $btnOrientation,
            ],
        ];
        return $this->send($msg);
    }

    /**
     * 发送FeedCard消息
     * @param array $links
     * @return bool
     * @throws \Exception
     */
    public function sendFeedCardMsg(array $links = [])
    {
        $msg = [
            'msgtype' => 'feedCard',
            'feedCard' => [
                'links'=> $links,
            ],
        ];
        return $this->send($msg);
    }


    /**
     * @param $at
     * @param $msg
     */
    protected function getAt($at, &$msg)
    {
        $newAt = [
            'atMobiles' => [],
            'isAtAll' => false,
        ];

        if(empty($at)){
            $msg['at'] =  $newAt;
        }

        if($at === '*'){
            $newAt = [
                'atMobiles' => [],
                'isAtAll' => true,
            ];
        }else if(is_array($at) && count($at) > 0){
            $newAt = [
                'atMobiles' => $at,
                'isAtAll' => false,
            ];
        }

        $msg['at'] = $newAt;
    }

    /**
     * Get request Url
     * @return string
     */
    protected function getUrl()
    {
        return $this->apiUrl . '?' . http_build_query(['access_token' => $this->accessToken]);
    }

    /**
     * Send http request
     * @param array $data
     * @return bool
     * @throws
     */
    protected function send(array $data)
    {
        $client = new Client(['baseUrl' => $this->getUrl()]);

        $res = $client->createRequest()
            ->setMethod('post')
            ->setHeaders(['Content-type' => 'application/json'])
            ->setFormat(Client::FORMAT_JSON)
            ->setContent(Json::encode($data))
            ->send();

        if($res->isOk){
            $data = Json::decode($res->content);
            if (isset($data['errcode']) && $data['errcode'] == 0) {
                return true;
            }
            throw new \Exception($data['errmsg']);
        }

        return false;
    }


}