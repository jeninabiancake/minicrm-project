<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactStatus extends Model
{
    use HasFactory;

    protected $table = "contact_status";
    protected $fillable = ["name"];
}
