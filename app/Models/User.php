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

  
        
        'years_experience',
        'password',
        'company_documents',
        'is_admin',
        'is_active', 

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
        'is_admin' => 'boolean', // ✅ Importante para validación de acceso
        'is_active' => 'boolean', 



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

    public function approval()
    {
        return $this->hasOne(lead_approvals::class);
    }

    public function notes()
    {
        return $this->hasMany(EventNote::class);
    }

public function companyLocations()
    {
        return $this->hasMany(CompanyLocation::class, 'user_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }
    
}
