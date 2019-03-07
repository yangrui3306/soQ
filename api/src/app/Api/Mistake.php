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
      'getByUserId' => array(
        'uid'  => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => '题目id'),
        'Number'  => array('name' => 'Number', 'desc' => '需要的数量'),
        'Page'=>array('name' => 'Page', 'default' => 1, 'desc' => '题目页数')
      ),

      //弃用 相应接口在Like与Collection文件中
      // 'like' => array(
      //   'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
      //   'MistakeId' => array('name' => 'MistakeId', 'require' => true, 'min' => 1, 'desc' => 'MistakeId'),
      //   'QuestionId' => array('name' => 'QuestionId', 'default' => 0, 'desc' => 'question id'),
      //   'StandTime' => array('name' => 'StandTime', 'default' => 0, 'desc' => '停留时间')
      // ),
      // 'collection' => array(
      //   'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
      //   'MistakeId' => array('name' => 'MistakeId', 'require' => true, 'min' => 1, 'desc' => 'MistakeId'),
      //   'QuestionId' => array('name' => 'QuestionId', 'default' => 0, 'desc' => 'question id'),
      //   'StandTime' => array('name' => 'StandTime', 'default' => 0, 'desc' => '停留时间')
      // ),
      
      'getcate' => array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
      ),
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
     * 通过用户Id查找最近几道错题整理
     * @desc 通过用户Id查找最近几道错题整理
     * @return 返回題目列表
     */

  public function getByUserId()
  {
    $num = $this->Number;
    $page=$this->Page;
    $dm = new DomainMistake();
    $re = $dm->getMistakeByUserId($this->uid,$page, $num);
    return MyStandard::gReturn(0, $re);
  }

  // /**
  //    * 添加点赞行为
  //    * @desc 点赞
  //    * @return 返回受影响行数
  //    */
  // public function like()
  // {
  //   $data = array(
  //     "MistakeId" => $this->MistakeId,
  //     "UserId" => $this->UserId,
  //     "QuestionId" => $this->QuestionId
  //   );
  //   $dm = new DomainMistake();
  //   $re = $dm->addLike($data, $this->StandTime);
  //   return Mystandard::gReturn(0, $re);
  // }
//弃用


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
}
