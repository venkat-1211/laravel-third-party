<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use Searchable, HasFactory;

    protected $fillable = ['title', 'description', 'price', 'category'];

    /**
     * Prepare the data for the index.
     */
    public function toSearchableArray(): array
    {
        // Customize what's indexed
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => (float) $this->price,
            'category' => $this->category,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }

    // Optionally set index name:
    public function searchableAs(): string
    {
        return 'products_index';
    }
}
