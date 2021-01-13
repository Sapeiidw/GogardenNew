<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;


class Plant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "plants";
    protected $dates = ["deleted_at"];
    protected $guarded = [];

   

    public function photoUrl()
    {
        if ($this->photo != null) {
            return Storage::disk($this->profilePhotoDisk())->url($this->photo);
        } else {
            return "https://media.sproutsocial.com/uploads/2017/02/10x-featured-social-media-image-size.png";
        }
    }

    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function profilePhotoDisk()
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : 'public';
    }
}
