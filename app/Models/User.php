<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'position_title', 
        'phone', 
        'image', 
        'is_admin', 
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // get all contacts assigned to user
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'assigned_user_id');
    }

    // get all leads assigned to user
    public function leads(): HasMany
    {
       return $this->hasMany(Contact::class, 'assigned_user_id')->where('status', ContactStatus::where('name', config('seed_data.contact_status')[0])->first()->id);
    }

    // get all opportunities assigned to user
    public function opportunities(): HasMany
    {
      return $this->hasMany(Contact::class, 'assigned_user_id')->where('status', ContactStatus::where('name', config('seed_data.contact_status')[1])->first()->id);
    }

    // get all customers assigned to user
    public function customers(): HasMany
    {
        return $this->hasMany(Contact::class, 'assigned_user_id')->where('status', ContactStatus::where('name', config('seed_data.contact_status')[2])->first()->id);
    }

    // get all closed archives customers assigned to user
    public function archives(): HasMany
    {
        return $this->hasMany(Contact::class, 'assigned_user_id')->where('status', ContactStatus::where('name', config('seed_data.contact_status')[3])->first()->id);
    }

    // get all documents assigned to user   
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'assigned_user_id');
    }

    // get all tasks assigned to user
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_user_id');
    }

    // get all completed tasks assigned to user
    public function completedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_user_id')->where('status', TaskStatus::where('name', config('seed_data.task_statuses')[2])->first()->id);
    }

    // get all pending tasks assigned to user
    public function pendingTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_user_id')->where('status', TaskStatus::whereIn('name', [config('seed_data.task_statuses')[0], config('seed_data.task_statuses')[1]])->first()->id);
    }

}
