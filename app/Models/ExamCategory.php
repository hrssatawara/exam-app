<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamCategory extends Model
{
    use HasFactory;

    protected $table="exam_categories";

    protected $primaryKey="id";

    protected $fillable=['name','status'];
}
