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

}
