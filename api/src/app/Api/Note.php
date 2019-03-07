<?php
namespace App\Api;

use PhalApi\Api;


use App\Common\MyStandard;
use App\Domain\Behavior\Statistics as ModelStatistics;
use App\Model\Notecategory as ModelNoteCategory;
use App\Domain\Note as DomainNote;

/**
 * 笔记
 * @author: goodtimp 2019-03-06
 */
class Note extends Api
{
  public function getRules()
  {
    return array(
      'cates' => array(),
      'notesByKeys' => array(
				'key'=> array('name' => 'keys', 'require' => true, 'min' => 1, 'max' => 50, 'desc' => '关键字', 'source' => 'post'),
      ),
      'notesByCate'=>array(
        'cateid'=>array('name'=>'cateid','require'=>true,'desc'=>'分类Id'),
        'num'=> array('name' => 'num',  'min' => 1,  'desc' => '分页'),
        )
    );
  }

  /**
     * 得到所有笔记分类信息
     */
  public function cates()
  {
    $mc = new ModelNoteCategory();
    $re=$mc->getNotesCategoryByUserId($uid); //用户Id 需修改
    return MyStandard::gReturn(0,$re);
  }


  /**
   * 根据关键字搜索用户笔记
   */
  public function getNotesByKeys()
  {
    $dn=new DomainNote();
    
    $re=$dn->getNotesByKeywords($uid,$this->key);
 
    return MyStandard::gReturn(0,$re);
  }

  /** 根据用户分类得到所有notes */
  public function notesByCate(){
    $dn=new DomainNote();
    //分页
    $re=$dn->getNotesByCateId($uid,$this->cateid);
    
    return MyStandard::gReturn(0,$re);
  }
}
