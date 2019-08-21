<?php namespace BookStack\Orz;

use BookStack\Auth\User;
use BookStack\Entities\Entity;
use BookStack\Entities\Book;
use BookStack\Auth\SpaceRole;
use BookStack\Uploads\Image;

class Space extends Entity
{
    protected $table = 'space';
    
    protected $fillable = ['name', 'description', 'image_id'];
    
    /**
     * Get the morph class for this model.
     * @return string
     */
    public function getMorphClass()
    {
        return 'BookStack\\Orz\\Space';
    }
    
    public function getUrl($path = false)
    {
        if ($path !== false) {
            return baseUrl('/space/' . $this->id . '/' . trim($path, '/'));
        }
        return baseUrl('/space/' . $this->id);
    }
    
    public function cover()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
    
    public function books()
    {
        return $this->belongsToMany(Book::class,'space_book','space_id','book_id');
    }
    
    public function roles()
    {
        return $this->hasMany(SpaceRole::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'space_user','created_by');
    }
    
    public function getCover($width = 440, $height = 250)
    {
        $default = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
        if (!$this->image_id) {
            return $default;
        }
        
        try {
            $cover = $this->cover ? baseUrl($this->cover->getThumb($width, $height, false)) : $default;
        } catch (\Exception $err) {
            $cover = $default;
        }
        return $cover;
    }
}
