<?php

/**
 * @author : goodtimp
 * @time : 2019-3-3
*/

namespace App\Model\Question;

use PhalApi\Model\NotORMModel as NotORM;


class Add extends NotORM
{
  /**---------------- m开头为返回数据库可操作类型-------------------- */

  protected function getTableName($id)
  {
    return 'question';
  }
  /**添加题目 */
  public function AddQuestion($q)
  {
    $orm = $this->getORM();
    $orm->insert($q);

    // 返回新增的ID（注意，这里不能使用连贯操作，因为要保持同一个ORM实例）
    return $orm->insert_id();
  }

  
}
