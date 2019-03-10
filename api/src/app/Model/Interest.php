<?php

/**
 * @author : goodtimp
 * @time : 2019-3-10
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class Interest extends NotORM
{
    const like=1;

    protected function getTableName($id)
    {
        return 'interest';
    }
    /** 
     * 添加记录，如果存在则相加
     * 
     * @return 不返回 
     */
    public function addInterest($data)
    {
        $re = $this->getORM()
            ->where("UserId", $data["UserId"])
            ->where("QuestionId", $data["QuestionId"]);
        if ($re->count() > 0) {
                $re->update(array('Interestingness' => new \NotORM_Literal("Interestingness + " . $data["Interestingness"])));
            } else {
            $re->insert($data);
        }
    }






}
