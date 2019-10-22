<?php namespace BookStack\Orz;

use BookStack\Auth\User;
use BookStack\Entities\Entity;
use BookStack\Entities\Book;
use BookStack\Uploads\Image;
use Illuminate\Support\Facades\DB;

class Market extends Entity
{
    protected $table = 'market';    
    
    /**
     * Get the morph class for this model.
     * @return string
     */
    public function getMorphClass()
    {
        return 'BookStack\\Orz\\Market';
    }
    
}
