<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "task";
    protected $fillable = ["name", "priority", "status", "type_id", "start_date", "end_date", "complete_date", "contact_type", "contact_id", "description", "created_by_id", "modified_by_id", "assigned_user_id"];

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
        return $this->belongsTo(TaskStatus::class, 'status');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TaskType::class, 'type_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'task_document', 'task_id', 'document_id');
    }
}
