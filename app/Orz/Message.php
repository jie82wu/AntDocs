<?php namespace BookStack\Orz;

use BookStack\Auth\User;
use BookStack\Entities\Entity;
use BookStack\Entities\Book;
use BookStack\Uploads\Image;
use Illuminate\Support\Facades\DB;

class Message extends Entity
{
    protected $table = 'messages';    
    
    /**
     * Get the morph class for this model.
     * @return string
     */
    public function getMorphClass()
    {
        return 'BookStack\\Orz\\Message';
    }
    
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from');
    }
    
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to');
    }
    
    public function spaceName()
    {
        $spaceIdAndUserId = explode('|', $this->rel_id);//message rel_id:space_id|user_id
        $space = Space::find($spaceIdAndUserId[0]);
        return $space?$space->name:'unkown';
    }
    
}
