<?php namespace BookStack\Orz;

use BookStack\Entities\Entity;

class SpaceBook extends Entity
{
    protected $table = 'space_book';
    
    
    /**
     * Get the morph class for this model.
     * @return string
     */
    public function getMorphClass()
    {
        return 'BookStack\\Orz\\SpaceBook';
    }
    
}
