<?php

namespace Domain\Product\Models;

use Domain\Product\Collections\PropertyCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function newCollection(array $models = []):PropertyCollection
    {
        return new PropertyCollection($models);
    }
    
    //для норм работы в moonShine
    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'value' => $this->pivot->value ?? null // для pivot данных
        ];
    }
}
