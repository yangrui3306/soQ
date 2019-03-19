<?php
namespace App\Api;

use PhalApi\Api;
use App\Common\Match as Match;

use App\Domain\Mistake as DomainMistake;
use App\Common\MyStandard;

/**
 * 错题整理部分
 * @author: goodtimp 2019-03-06
 */
class Mistake extends Api
{
  public function getRules()
  {
    return array(
      'add' => array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
        'QuestionId' => array('name' => 'QuestionId', 'default' => 0, 'desc' => 'question id'),
        'Correct' => array('name' => 'Correct', 'default' => ' ', 'desc' => '错题整理'),
        'QuestionContent' => array('name' => 'QuestionContent', 'default' => '', 'desc' => '题目内容'),
        'MistakeCateId'  => array('name' => 'MistakeCateId', 'require' => true, 'min' => 1, 'desc' => '错题分类'),
        'StandTime' => array('name' => 'StandTime', 'default' => 0, 'desc' => '停留时间')
      ),
      'update' => array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
        'Id' => array('name' => 'Id', 'require' => true, 'min' => 1, 'desc' => 'id'),
        'Correct' => array('name' => 'Correct', 'desc' => '错题整理'),
        'QuestionContent' => array('name' => 'QuestionContent', 'desc' => '题目内容'),
        'MistakeCateId'  => array('name' => 'MistakeCateId',   'desc' => '错题分类'),
        'StandTime' => array('name' => 'StandTime', 'default' => 0, 'desc' => '停留时间')
      ),
      'getByQuestionId' => array(
        'qid'  => array('name' => 'QuestionId', 'require' => true, 'min' => 1, 'desc' => '题目id'),
        'Number'  => array('name' => 'Number', 'default' => 5, 'desc' => '需要的数量'),
        'Page'=>array('name' => 'Page', 'default' => 1, 'desc' => '题目页数')
      ),
      'getByCateId' => array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
        'MistakeCateId'  => array('name' => 'MistakeCateId', 'require' => true, 'min' => 1, 'desc' => '题目id'),
        'Number'  => array('name' => 'Number', 'desc' => '需要的数量'),
        'Page'=>array('name' => 'Page', 'default' => 1, 'desc' => '题目页数')
      ),
      'getMistake'=>array(
        'Id' => array('name' => 'Id', 'require' => true, 'min' => 1, 'desc' => 'id'),
        'UserId' => array('name' => 'UserId', 'default'=>0, 'desc' => 'user id'),
      ),
      'getByKeys'=>array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
				'MistakeCateId'   => array('name' => 'MistakeCateId', 'default'=>0, 'desc' => '分类Id'),
        'key' => array('name' => 'keys', 'require' => true, 'min' => 1, 'max' => 50, 'desc' => '关键字'),
      ),     
      'getcate' => array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
      ),
      'delete'=>array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
        'Id' => array('name' => 'Id', 'require' => true, 'min' => 1, 'desc' => 'id'),
      ),
      'getByUserId'=>array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
      ),
      'addCate'=>array(
        'Name'=>array('name'=>'Name','require' => true, 'min' => 1, 'max'=>20,'desc'=>'分类名称'),
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
        'Intro'=>array('name'=>'Intro','default'=>"",'desc'=>'分类名称'),
      ),
      'updateCate'=>array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
				'MistakeCateId'   => array('name' => 'MistakeCateId','require' => true, 'default'=>0, 'desc' => '分类Id'),
        'Name'=>array('name'=>'Name','desc'=>'分类名称'),
        'Intro'=>array('name'=>'Intro','desc'=>'分类名称'),
      ), 
      'deleteCate'=>array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
				'MistakeCateId'   => array('name' => 'MistakeCateId','require' => true, 'default'=>0, 'desc' => '分类Id'),
      )
    );
  }
  /**
     * 修改错题整理
     * @desc 更新错题整理
     * @return App\Model\更新异常时返回bool，数据无变化时返回0，成功更新返回1
     */
  public function update()
  {
    $data = array(
      'UserId' => $this->UserId,
      'Id' => $this->Id,
    );
    if ($this->Correct) $data["Correct"] = $this->Correct;
    if ($this->QuestionContent) $data["QuestionContent"] = $this->QuestionContent;
    if ($this->MistakeCateId) $data["MistakeCateId"] = $this->MistakeCateId;

    $dm = new DomainMistake();
    $re = $dm->updateMistake($data, $this->StandTime);
    return MyStandard::gReturn(0, $re);
  }


  /**
     * 添加错题整理
     * @desc 添加错题整理
     * @return 返回插入数据ID
     */
  public function add()
  {
    $data = array(
      'UserId' => $this->UserId,
      'QuestionId' => $this->QuestionId,
      'Correct' => $this->Correct,
      'QuestionContent' => $this->QuestionContent,
      'MistakeCateId'  => $this->MistakeCateId,
    );
    $dm = new DomainMistake();
    $re = $dm->addMistake($data, $this->StandTime);
    return MyStandard::gReturn(0, $re);
  }
  /**
     * 通过题目Id查找热度最大的几道的错题
     * @desc 通过题目Id查找热度最大的几道的错题
     * @return 返回題目列表
     */
  public function getByQuestionId()
  {
    $num = $this->Number;
    $page=$this->Page;
    $dm = new DomainMistake();
    $re = $dm->getMistakeByQuestionId($this->qid,$page, $num);
    return MyStandard::gReturn(0, $re);
  }
  /**
     * 通过分类Id，分类为0得到用户所有错题查找最近几道错题整理
     * @desc 通过用户Id查找最近几道错题整理
     * @return 返回題目列表
     */

  public function getByCateId()
  {
    $num = $this->Number;
    $page=$this->Page;
    $dm = new DomainMistake();
    $re = $dm->getMistakeByUserId($this->UserId,$this->MistakeCateId,$page, $num);
    return MyStandard::gReturn(0, $re);
  }
  /**
     * 得到用户所有的错题分类
     * @desc 用户所有错题分类
     * @return 分类数组
     */
  public function getcate()
  {
    $userId = $this->UserId;
    $dm = new DomainMistake();
    $re = $dm->getCategory($userId);
    return MyStandard::gReturn(0, $re);
  }

  /**
   * 错题详情
   */
  public function getMistake()
  {
    $dm=new DomainMistake();
    $re=$dm->getById($this->Id,$this->UserId);
    return MyStandard::gReturn(0, $re);
  }
  /**
   * 根据关键字查找错题
   */
  public function getByKeys()
	{
		$dn = new DomainMistake();
		$uid=$this->UserId;
		$re = $dn->getByKeywords($uid, $this->MistakeCateId,$this->key);

		return MyStandard::gReturn(0, $re);
  }
  
  /**
   * 错题删除
   */
  public function delete()
  {
    $dn = new DomainMistake();
    $re=$dn->deleteMistake($this->UserId,$this->Id);
    return MyStandard::gReturn(0, $re);
  }

  /**
   * 添加错题分类
   */
  public function addCate(){
    $data=array(
      "Name"=>$this->Name,
      "UserId"=>$this->UserId,
      "Intro"=>$this->Intro,
    );
    $mm=new DomainMistake();
    $re=$mm->addCategory($data);
    return MyStandard::gReturn(0,$re,($re==-1?"该分类已经存在":"添加成功"));
  }
  /**
   * 修改错题分类
   */
  public function updateCate(){
    $data=array(
      "UserId"=>$this->UserId,
      "Id"=>$this->MistakeCateId
    );
    if($this->Name)  $data["Name"]=$this->Name;
    if($this->Intro) $data["Intro"]=$this->Intro;
    $mm=new DomainMistake();
    $re=$mm->updateCategory($data);
    return MyStandard::gReturn(0,$re,($re==-1?"该分类不存在":"修改成功"));
  }
  /**
   * 删除分类，并将所有错题删除
   */
  public function deleteCate(){
    $data=array(
      "UserId"=>$this->UserId,
      "Id"=>$this->MistakeCateId,
    );
    $mm=new DomainMistake();
    $re=$mm->deleteCategory($data);
    return MyStandard::gReturn(0,$re);
  }
}
