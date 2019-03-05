<?php
namespace App\Api\Question;

use PhalApi\Api;
use App\Domain\Examples\CURD as DomainCURD;
use App\Domain\Question\Upload as DomainUpload;
use App\Common\MyStandard;
/**
 * 数据库CURD基本操作示例
 * @author dogstar 20170612
 */

class CURD extends Api {

    public function getRules() {
        return array(
            'insert' => array(
                'Content' => array('name' => 'Content', 'require' => true,  'desc' => '题目'),
                'CategoryId' => array('name' => 'CategoryId','type' => 'int',  'desc' => '分类'),
                'KeyWords' => array('name' => 'KeyWords',  'default' => "", 'desc' => '关键字'),
                'Analysis'=>array('name' => 'Analysis',  'desc' => '解析'),
                'Text'=>array('name' => 'Text',  'max' => 2000, 'desc' => '文本'),
              ),
            'get' => array(
              'username'
            ),
           
        );
    }

    /**
     * 插入数据
     * @desc 向数据库插入一条纪录数据
     * @return int id 新增的ID
     */
    public function insert() {
        $rs = array();
        
        $newData = array(
            'Content' => $this->Content,
            'CategoryId' => $this->CategoryId,
            'KeyWords' => $this->KeyWords,
            'Analysis'=>$this->Analysis,
            'Text'=>$this->Text,
        );

        $domain = new DomainUpload();
        $id = $domain->upQuestion($newData);

        $rs['Id'] = $id;
        return MyStandard::gReturn(0,$rs);
    }

    /**
     * 更新数据
     * @desc 根据ID更新数据库中的一条纪录数据
     * @return int code 更新的结果，1表示成功，0表示无更新，false表示失败
     */
    public function update() {
        $rs = array();

        $newData = array(
            'title' => $this->title,
            'content' => $this->content,
            'state' => $this->state,
        );

        $domain = new DomainCURD();
        $code = $domain->update($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 获取数据
     * @desc 根据ID获取数据库中的一条纪录数据
     * @return int      id          主键ID
     * @return string   title       标题
     * @return string   content     内容
     * @return int      state       状态
     * @return string   post_date   发布日期
     */
    public function get() {
        return "hello";
    }

}
