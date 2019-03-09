<?php
namespace App\Api;

use PhalApi\Api;
use App\Domain\Examples\CURD as DomainCURD;
use App\Domain\Question\Upload as DomainUpload;
use App\Common\MyStandard;

use App\Common\Tools as Tools;
use App\Model\KeyWord as KeyWord;

use App\Domain\Question\Recommend as DomainRecommend;
use App\Domain\Question\Basic as DomainBasic;
use App\Model\Question\Search as ModelSearchQ;
use App\Domain\Behavior\Statistics as ModelStatistics;

/**
 * 题目的基本操作示例
 * @author goodtimp 20190306
 */

class Question extends Api
{

    public function getRules()
    {
        return array(
            'insert' => array(
                'Content' => array('name' => 'Content', 'require' => true,  'desc' => '题目'),
                'CategoryId' => array('name' => 'CategoryId', 'type' => 'int',  'desc' => '分类'),
                'KeyWords' => array('name' => 'KeyWords',  'default' => "", 'desc' => '关键字'),
                'Analysis' => array('name' => 'Analysis',  'desc' => '解析'),
                'Text' => array('name' => 'Text',  'max' => 2000, 'desc' => '文本'),
            ),
            'search' => array(
                'Text' => array('name' => 'Text', 'require' => true, 'max' => 2000, 'desc' => '文本'),
                'Num' => array('name' => 'Num', 'desc' => '匹配n个')
            ),
            'getById'=>array(
                'UserId'=>array('name'=>'UserId','default'=>0,'require'=>false,'desc'=>"用户id"),
                'Id'=>array('name' => 'Id', 'require' => true,'min='=>1,'desc' => '题目Id'),
            )

        );
    }

    /**
     * 插入数据
     * @desc 向数据库插入一条纪录数据
     * @return int id 新增的ID
     */
    public function insert()
    {
        $rs = array();

        $newData = array(
            'Content' => $this->Content,
            'CategoryId' => $this->CategoryId,
            'KeyWords' => $this->KeyWords,
            'Analysis' => $this->Analysis,
            'Text' => $this->Text,
        );

        $domain = new DomainUpload();
        $id = $domain->upQuestion($newData);

        $rs['Id'] = $id;
        return MyStandard::gReturn(0, $rs);
    }

    /**
     * 搜索题目
     * @desc 匹配题目
     * @return 题目信息
     */
    public function search()
    {
        $q = array('Text' => $this->Text);
        $reslut = DomainBasic::searchQuestion($q, 3); //查找前三个
        return MyStandard::gReturn(0, $reslut);
    }
    /**
     * 通过Id得到题目
     * @desc 通过Id得到相应题目信息
     */
    public function getById()
    {
        $mq=new DomainBasic();

        $re=$mq::findQuestionById($this->Id,$this->UserId);
        return MyStandard::gReturn(0,$re);
    }
}
