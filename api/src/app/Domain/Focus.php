<?php
namespace App\Domain;
use App\Model\Focus as ModelFocus;
use App\Model\User as ModelUser;

use App\Common\Tools;
class Focus
{
  public function add($uid,$fid)
  {
  
    $fm=new ModelFocus();
    $id=$fm->insertOne($uid,$fid);
    return $id;
  }
  /**得到用户的粉丝 */
  public function getUserFans($uid,$page=1,$num=10)
  {
    $fm=new ModelFocus();
    $min=Tools::getPageRange($page,$num);
    $re=$fm->gesFansByUserId($uid,$min,$num);
    $um=new ModelUser();
    $um->replaceUserId($re,'FanId');
    return $re;
  }
	
  /**得到用户关注的人 */
  public function getUserFollowee($uid,$page=1,$num=10)
  {
    $fm=new ModelFocus();
    $min=Tools::getPageRange($page,$num);
    $re=$fm->gesFolloweeByUserId($uid,$min,$num);
    $um=new ModelUser();
    $um->replaceUserId($re);
    return $re;
  }
}