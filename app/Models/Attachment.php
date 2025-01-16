<?php

namespace App\Models;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    protected $fillable = [
        "extra_identifier","title","path","size","type","attachment",
    ] ;
    public function url(): Attribute
    {
        return Attribute::make(fn(): string => URL::to('storage/' . $this->path));
    }
    public function attachment(): MorphTo
    {
        return $this->morphTo();
    }
}