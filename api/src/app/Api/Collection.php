<?php
namespace App\Api;

use PhalApi\Api;

use App\Domain\Collection as DomainCollection;
use App\Common\MyStandard;

/**
 * 收藏
 * @author: goodtimp 2019-03-07
 */
class Collection extends Api
{
  public function getRules()
  {
    return array(
      'add' => array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => '用户id'),
        'QuestionId' => array('name' => 'QuestionId', 'default' => 0, 'desc' => '题目id'),
        'MistakeId'  => array('name' => 'MistakeId', 'default' => 0, 'desc' => '错题分类'),
        'StandTime' => array('name' => 'StandTime', 'default' => 0, 'desc' => '停留时间')
      ),
      'delete'=>array(
        'UserId' => array('name' => 'UserId', 'desc' => '用户id'),
        'Id'=>array('name' => 'Id', 'require' => true, 'min' => 1, 'desc' => '收藏id'),
      ),
      'all'=>array(
        'UserId' => array('name' => 'UserId', 'desc' => '用户id'),
        'Number'  => array('name' => 'Number', 'default' => 5, 'desc' => '需要的数量'),
        'Page'=>array('name' => 'Page', 'default' => 1, 'desc' => '题目页数')
      )
    );
  }
  /**
     * 添加收藏
     * @desc 添加收藏
     * @return 收藏的Id
     */
  public function add()
  {
    $data = array(
      'UserId' => $this->UserId,
      'QuestionId' => $this->QuestionId,
      'MistakeId' => $this->MistakeId
    );
    $dm = new DomainCollection();
    $re = $dm->add($data, $this->StandTime);
    return MyStandard::gReturn(0, $re);
  }

  /**
   * 删除收藏
   * @desc 删除收藏
   * @return 1成功
   *  */
  public function delete()
  {
    $data = array(
      'UserId' => $this->UserId,
      'Id' => $this->Id
    );
    $dm=new DomainCollection();
    $re=$dm->delete($data);
    return MyStandard::gReturn(0, $re);
  }

  /**查找所有收藏
   * @desc 用户收藏
   */
  public function all(){
    $num=$this->Number;
    $page=$this->Page;

    $cd=new DomainCollection();
    $re=$cd->getAllByUserId($this->UserId,$page,$num);
    
    return MyStandard::gReturn(0,$re);
  }
}
