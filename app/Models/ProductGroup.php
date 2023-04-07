<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'description'];

    public function productCategory()
    {
        return $this->hasMany(ProductCategory::class, 'group_id', 'id');
    }
}
