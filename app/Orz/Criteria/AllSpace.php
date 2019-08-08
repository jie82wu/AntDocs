<?php namespace BookStack\Orz\Criteria;

use BookStack\Orz\Contract\RepositoryInterface;

class AllSpace extends Criteria {
    
    /**
     * @param $model
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        //owned
        $query = $model->where(['type'=>1])->where(['created_by'=>user()->id]);
        return $query;
    }
}