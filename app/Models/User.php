<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['name','email','password','role_id','phone','address'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    public function reclamations()
    {
        return $this->hasMany(Reclamation::class);
    }
}
