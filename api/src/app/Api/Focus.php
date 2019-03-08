<?php
namespace App\Api;

use PhalApi\Api;

use App\Domain\Focus as DomainFocus;
use App\Common\MyStandard;

/**
 * 关注
 * @author: goodtimp 2019-03-07
 */
class Focus extends Api
{
  public function getRules()
  {
    return array(
      'add' => array(
        'UserId' => array('name' => 'FolloweeId', 'require' => true, 'min' => 1, 'desc' => '关注人id'),
        'FanId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => '用户id'),  
      ),
      'getFans'=>array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => '用户id'),
        'Number'  => array('name' => 'Number', 'default' => 10, 'desc' => '需要的数量'),
        'Page'=>array('name' => 'Page', 'default' => 1, 'desc' => '题目页数')
      ),
      'getFollowee'=>array(
        'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => '用户id'),
        'Number'  => array('name' => 'Number', 'default' => 10, 'desc' => '需要的数量'),
        'Page'=>array('name' => 'Page', 'default' => 1, 'desc' => '题目页数')
      )
    );
  }
  /**
     * 添加关注
     * @return id，-1为已经关注,
     */
  public function add()
  {
    $dm = new DomainFocus();
    $re = $dm->add($this->UserId,$this->FanId);
    return MyStandard::gReturn(0, $re);
  }
  /**
     * 添加得到用户粉丝
     * @desc 得到用户粉丝
     * @return 粉丝列表
     */
    public function getFans()
    {
      $page=$this->Page;
      $num=$this->Number;
      $dm = new DomainFocus();
      $re = $dm->getUserFans($this->UserId,$page,$num);
      return MyStandard::gReturn(0, $re);
    }
    /**
       * 添加得到用户关注的人
       * @return 关注人列表
       */
    public function getFollowee()
    {
      $page=$this->Page;
      $num=$this->Number;
      $dm = new DomainFocus();
      $re = $dm->getUserFollowee($this->UserId,$page,$num);
      return MyStandard::gReturn(0, $re);
    }
}
