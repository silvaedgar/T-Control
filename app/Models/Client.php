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

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function UserClient()
    {
        return $this->hasMany(UserClient::class, 'client_id', 'id');
    }

    // public function scopeLoadClients() {
    //     return Client::where('clients.status','Activo')->orderby('names');
    // }

    // public function scopeLoadUserClients($query,$id) {
    //     return
        // Client::select('*')->join('user_clients','clients.id','user_clients.client_id')
        //     ->where('user_clients.user_id',$id)->get(); consulta original
    // }


}
