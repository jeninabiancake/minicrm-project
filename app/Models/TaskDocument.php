<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDocument extends Model
{
    use HasFactory;

    protected $table = "task_document";
    protected $fillable = ["task_id", "document_id"];
}
