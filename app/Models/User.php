<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'blocked',
        'last_login',
        'created_at',
        'updated_at'
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

    private function reverseIp($ip, $blacklist)
    {
        return implode('.', array_reverse(explode('.', $ip))) . '.' . $blacklist;
    }

    public function checkIp()
    {
        $adm_blacklist = ['b.barracudacentral.org', 'misc.dnsbl.sorbs.net'];
        $ip = trim($this->preferences->ip);
        if (strlen($ip) > 0) {
            foreach ($adm_blacklist as $blacklist) {
                if (checkdnsrr($this->reverseIp($ip, $blacklist), 'ANY')) {
                    return $blacklist;
                }
            }
        }
        return '';
    }

    public function getLoginDiff()
    {
        return $this->created_at->diffAsCarbonInterval($this->last_login);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function preferences(): HasOne
    {
        return $this->hasOne(Preference::class);
    }
}
