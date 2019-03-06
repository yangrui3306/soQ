<?php
namespace App\Domain;

use App\Model\Note as ModelNote;
use App\Model\Notecategory as ModelCate;


class Note {
	
    /**
     * 根据分类Id查找笔记
		 * @param cateid 分类id
         * @param num 获取前几个
     */
    public function getNotesByCateId($uid,$cateid, $num = 0)
    {
      $m=new ModelNote();
      $cm=new ModelCate();
      if($cm->judgeCateForUser($uid,$cateid)>0){
        return $m->getNotesByCateId($cateid,$num);
      }
      else return [];
    }

    /**根据关键字查找用户笔记 */
    public function getNotesByKeywords($uid, $keys)
    {
      $m=new ModelNote;
      return $m->getNotesByKeywords($uid,$keys);
    }
}