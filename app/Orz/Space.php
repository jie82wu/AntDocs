<?php namespace BookStack\Orz;

use BookStack\Entities\Entity;

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
            return baseUrl('/books/' . urlencode($this->slug) . '/' . trim($path, '/'));
        }
        return baseUrl('/space');
    }
    
    public function cover()
    {
        return $this->belongsTo(Space::class, 'image_id');
    }
    
    public function getBookCover($width = 440, $height = 250)
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
