<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'last_name',
        'email',
        'phone',
        'language',
        'profile_photo',
        'company_name',
        'residential_roof_types',
        'commercial_roof_types',
        'states_you_can_work',
        'all_states',
        'years_experience',
        'password',
        'company_documents',

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
        'residential_roof_types' => 'array',
        'commercial_roof_types' => 'array',
        'states_you_can_work' => 'array',
        'all_states' => 'boolean',
        'company_documents' => 'array',

    ];


    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function leadFiles()
    {
        return $this->hasMany(LeadFile::class);
    }

    public function leadFinanzas()
    {
        return $this->hasMany(LeadFinanza::class);
    }

    public function leadImages()
    {
        return $this->hasMany(LeadImage::class);
    }

    public function leadMessages()
    {
        return $this->hasMany(LeadMessage::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }


    
}
