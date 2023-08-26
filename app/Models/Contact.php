<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = "contact";

    protected $fillable = ["first_name", "middle_name", "last_name", "status", "referral_source", "position_title", "industry",
    "project_type", "company", "project_description", "description", "budget", "website", "linkedin",
    "address_street", "address_city", "address_state", "address_country", "address_zipcode", "created_by_id",
    "modified_by_id", "assigned_user_id"];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function modifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modified_by_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function getStatus(): BelongsTo
    {
        return $this->belongsTo(ContactStatus::class, 'status');
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'contact_document', 'contact_id', 'document_id');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(ContactEmail::class, 'contact_id');
    }

    public function phones(): HasMany
    {
        return $this->hasMany(ContactPhone::class, 'contact_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'contact_id');
    }

    public function getName()
    {
        return $this->first_name .  (!empty($this->middle_name)?" " . $this->middle_name . " ":"") . (!empty($this->last_name)?" " . $this->last_name:"");
    }

}
