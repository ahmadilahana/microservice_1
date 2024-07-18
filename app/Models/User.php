<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = "users";
    public $incrementing = false;
    protected $fillable = [
        'id', 'name', 'email',
    ];

    protected $primaryKey = 'id';

    public $timestamps = true;

    public function tasks()
    {
        return $this->hasMany(TaskModel::class, 'created_by');
    }
}
