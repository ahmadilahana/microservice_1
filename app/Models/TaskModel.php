<?php

namespace App\Models;

use Helper\ResponseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tasks";
    protected $fillable = [
        'title', 'description', "status", "created_by"
    ];

    protected $primaryKey = 'id';

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $model = $this->where($field ?? $this->getRouteKeyName(), $value)->first();

        if (!$model) {
            return ResponseHelper::NotFoundReponse("TASKS_NOT_FOUND", "Tasks tidak ditemukan");
        }

        return $model;
    }
}
