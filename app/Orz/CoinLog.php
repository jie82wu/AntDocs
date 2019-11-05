<?php namespace BookStack\Orz;

use BookStack\Model;

class CoinLog extends Model
{
    protected $table = 'coin_log';    
    
    /**
     * Get the morph class for this model.
     * @return string
     */
    public function getMorphClass()
    {
        return 'BookStack\\Orz\\CoinLog';
    }
}
