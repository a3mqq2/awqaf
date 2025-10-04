<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    public function examinees()
    {
        return $this->hasMany(Examinee::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'cluster_user');
    }

}
