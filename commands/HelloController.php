<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Yii;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        $CrawlerDetect = new CrawlerDetect;

// Check the user agent of the current 'visitor'
        if($CrawlerDetect->isCrawler()) {
            // true if crawler user agent detected
        }

// Pass a user agent as a string
        if($CrawlerDetect->isCrawler('Mozilla/5.0 (compatible; Sosospider/2.0; +http://help.soso.com/webspider.htm)')) {
            // true if crawler user agent detected
        }

// Output the name of the bot that matched (if any)
        echo $CrawlerDetect->getMatches();
    }
}
