<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
       'name',
       'description',
    ] ;
    public function images(){
        return  $this->morphMany(Attachment::class,'attachment');
    }
}
