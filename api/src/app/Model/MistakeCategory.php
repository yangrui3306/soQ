<?php

/**
 * @author : goodtimp
 * @time : 2019-3-6
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class MistakeCategory extends NotORM
{

  protected function getTableName($id)
  {
    return 'mistakecategory';
  }
  /**得到所有分类信息 */
  public function getCategoryByUserId($uid){
    return $this->getORM()->where("UserId",$uid)->fetchAll();
  }
  /**
   * 添加错题分类，-1为已存在 返回id
   */
  public function addCategory($data)
  {
    $orm = $this->getORM();
    $judge=$orm->where("UserId",$data["UserId"])->where("Name",$data["Name"]);
    if($judge->count()>0) return -1;
    $orm->insert($data);
  
    // 返回新增的ID（注意，这里不能使用连贯操作，因为要保持同一个ORM实例）
    return $orm->insert_id();
  }

  /**
   * 更新错题分类，没有返回-1
   */
  public function updateCategory($data){
    $orm=$this->getORM();
    
    $judge=$orm->where("UserId",$data["UserId"])->where("Id",$data["Id"]);
    if($judge->count()>0) 
    {
      return $judge->update($data);
    }
    return -1;
  }
  /**
   * 删除错题
   */
  public function deleteCategory($data)
  {
    $orm=$this->getORM();
    
    return $orm->where("UserId",$data["UserId"])->where("Id",$data["Id"])->delete();
    
	}
	
	/**
	 * @author ipso
	 */
	public function getAll(){
		$model = $this -> getORM();
		return $model -> select("Id, Name, UserId, Intro") -> fetchAll();
	}

}
