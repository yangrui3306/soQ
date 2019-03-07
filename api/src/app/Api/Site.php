<?php
namespace App\Api;
use PhalApi\Api;
use App\Common\Match as Match;

use App\Common\Tools as Tools;
use App\Model\KeyWord as KeyWord;
use App\Domain\Question\Upload;
use App\Domain\Question\Recommend;
use App\Domain\Question\Basic;
use App\Model\Question\Search as ModelSearchQ;
use App\Domain\Behavior\Statistics as ModelStatistics;
/**
 * 测试接口
 */
class Site extends Api {
    public function getRules() {
        return array(
            'index' => array(
                'username'  => array('name' => 'username', 'default' => 'PhalApi', 'desc' => '用户名'),
            ),
        );
    }

    /**
     * 默认接口服务
     * @desc 默认接口服务，当未指定接口服务时执行此接口服务
     * @return string title 标题
     * @return string content 内容
     * @return string version 版本，格式：X.X.X
     * @return int time 当前时间戳
     * @exception 400 非法请求，参数传递错误
     */
    public function index() {
        $a=new ModelStatistics();
        $b= new ModelSearchQ();
        $c=new Upload();
        $qs=$b->getQuestionsByCategoryId(1);
        $q=array("CategoryId"=>"1",
            "Content"=>"　二元一次 编辑删除 3 全等三角形 编辑删除 4 相反数 编辑删除5 倒数       ",
            "Analysis"=>" 当然是1 ",
            "Type"=>"2",
            "KeyWords"=>"",
            "Schools"=>"1",
            "Text"=>" 二元一次 编辑删除 3 全等三角形 编辑删除 4 相反数 编辑删除5 倒数     ");
        return array(
            //'title' => $a->getStatisticsBehavior(1)
            //'title'=>Basic::searchQuestion(array("Text"=>"二元一次 编辑删除 3 全等三角形 编辑删除"))
            'title' => $c->upQuestion($q)           
        );
    }
}
