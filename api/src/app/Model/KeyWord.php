<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class KeyWord extends NotORM {

    protected function getTableName($id) {
        return 'keyword';
		}
		
    /**
     * 查找所有KeyWords
     * @return 返回数据库可操作类型
     */
    public function gesAllKeyWord() {
        return $this->getORM()
            ->select('*');
    }

    /**
     * 根据KeyWord字段得到关键字信息
     * @param string类型，逗号分隔类似"1,2,3,"
     * @return 关键字文字
     */
    public function gesKeyWordsById($idarr) {
      $commond="SELECT * FROM keyword where Id in (".$idarr.')';
      return $this->getORM()->queryAll($commond);
  }
}
