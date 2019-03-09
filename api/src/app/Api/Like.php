<?php
namespace App\Api;

use PhalApi\Api;

use App\Domain\Like as DomainLike;
use App\Common\MyStandard;

/**
 * 点赞
 * @author: goodtimp 2019-03-07
 */
class Like extends Api
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
    );
  }
  /**
     * 添加点赞
     * @desc 添加点赞
     * @return 点赞的Id
     */
  public function add()
  {
    $data = array(
      'UserId' => $this->UserId,
      'QuestionId' => $this->QuestionId,
      'MistakeId' => $this->MistakeId
    );
    
    $dm = new DomainLike();
    $re = $dm->add($data, $this->StandTime);
    return MyStandard::gReturn(0, $re);
  }
}
