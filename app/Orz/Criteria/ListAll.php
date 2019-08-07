<?php namespace BookStack\Orz\Criteria;

use BookStack\Orz\Contract\RepositoryInterface;

class ListAll extends Criteria {
    
    /**
     * @param $model
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model->where(['type'=>1]);
        return $query;
    }
}