<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable=['user_id','document_type','document','names','address'];

    public function Sale()
    {
        return $this->hasMany(Sale::class, 'client_id', 'id');
    }

}
