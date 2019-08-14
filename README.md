Yii2 dingtalk robot
===================
Use dingtalk robot to send message.

Installation
------------

Either run

```
composer require pandaxnm/yii2-dingtalk
```

or add

```
"pandaxnm/yii2-dingtalk": "*"
```

to the require section of your `composer.json` file.

Settings
-----

Edit `pathto/config/main.php` and add  :

```php
    'components' => [
        'robot' => [
            'class' => 'Pandaxnm\DingTalk\Robot',
            'accessToken' => 'YOUR_ACCESS_TOKEN',
        ],
        //other component
    ]
```

Usage
-----

#### Text message

```php
    //send text msg
    Yii::$app->robot->sendTextMsg("I'm a robot!")
    
    //send text msg and @ all
    Yii::$app->robot->sendTextMsg("I'm a robot!", '*')

    //send text msg and @ some persons
    $mobiles = ['13800138000', '13900139000']
    Yii::$app->robot->sendTextMsg("I'm a robot!", $mobiles)
```

#### Link message

```php    
    //send link msg
    Yii::$app->robot->sendLinkMsg('this is title','some text','http://baidu.com','http://xxx.com/1.png');
```

#### Markdown message

```php    
    //send markdown msg
    Yii::$app->robot->sendMarkdownMsg("杭州天气", "#### 杭州天气\n 9度，西北风1级，空气良89，相对温度73%");
    
    //send markdown mag and @ all
    Yii::$app->robot->sendMarkdownMsg("杭州天气", "#### 杭州天气\n 9度，西北风1级，空气良89，相对温度73%", '*');
    
    //send markdown msg and @ some persons
    $mobiles = ['13800138000'];
    Yii::$app->robot->sendMarkdownMsg("杭州天气", "#### 杭州天气 @13800138000\n 9度，西北风1级，空气良89，相对温度73%", $mobiles);
```

#### ActionCard message

```php
    //send actionCard msg
    Yii::$app->robot->sendActionCardMsg("今日新闻", "![screenshot](@lADOpwk3K80C0M0FoA) 
     ### 乔布斯 20 年前想打造的苹果咖啡厅", 'https://dingtalk.com');
     
    //send single actionCard msg
    $btns = [
     [
         'title' => '同意',
         'actionUrl' => 'https://xxx.com/agree'
     ],
     [
         'title' => '拒绝',
         'actionUrl' => 'https://xxx.com/disagree'
     ],
    ];
    Yii::$app->robot->sendSingleActionCardMsg("会议邀请", "### 你有一个会议邀请\n 时间:7月10日 15:00 地点:第一会议室 主题:xxx", $btns, 1, 1);
```

#### FeedCard
```php
    $btns = [
        [
            'title' => '标题一',
            'messageUrl' => 'https://gogole.com/1',
            'picURL' => 'https://gogole.com/images/1.jpg'
        ],
        [
            'title' => '标题二',
            'messageUrl' => 'https://gogole.com/2',
            'picURL' => 'https://gogole.com/images/2.jpg'
        ],
    ];
    Yii::$app->robot->sendFeedCardMsg($btns);
```


More info, visit [Dingtalk](https://ding-doc.dingtalk.com/doc#/serverapi2/qf2nxq)