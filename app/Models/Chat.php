<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class Chat extends Model
{
    use HasFactory;

    public $fillable = ['embed_collection_id', 'title'];
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function embed_collection(): BelongsTo
    {
        return $this->belongsTo(EmbedCollection::class);
    }
}
