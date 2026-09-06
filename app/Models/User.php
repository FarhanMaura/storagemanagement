<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isUser()
    {
        return $this->role === 'user' || empty($this->role);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPetugasPengajuan()
    {
        return $this->isAdmin();
    }

    public function isManajerPersetujuan()
    {
        return $this->isAdmin();
    }

    public function isPetugasBarangKeluar()
    {
        return $this->isAdmin();
    }

    public function getRoleName()
    {
        return $this->isAdmin() ? 'Admin Utama' : 'Pegawai / User';
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function validatedPeminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'validated_by');
    }

    public function approvedPeminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'approved_by');
    }

    public function completedPeminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'completed_by');
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }

    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function markAllNotificationsAsRead()
    {
        $this->unreadNotifications()->update(['read_at' => now()]);
    }

    public function getRecentActivities($limit = 5)
    {
        return $this->laporan()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getStatistics()
    {
        return [
            'total_laporan' => $this->laporan()->count(),
            'laporan_masuk' => $this->laporan()->where('jenis_laporan', 'masuk')->count(),
            'laporan_keluar' => $this->laporan()->where('jenis_laporan', 'keluar')->count(),
            'total_barang_masuk' => $this->laporan()->where('jenis_laporan', 'masuk')->sum('jumlah'),
            'total_barang_keluar' => $this->laporan()->where('jenis_laporan', 'keluar')->sum('jumlah'),
        ];
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeRegularUsers($query)
    {
        return $query->where('role', '!=', 'admin');
    }

    public function getNotificationPreferences()
    {
        return [
            'email_notifications' => true,
            'push_notifications' => true,
            'laporan_created' => true,
            'laporan_updated' => true,
            'laporan_deleted' => true,
        ];
    }

    public function canDeleteLaporan($laporan)
    {
        return $this->isAdmin();
    }

    public function canEditLaporan($laporan)
    {
        return $this->isAdmin();
    }

    public function getDisplayNameWithRole()
    {
        $role = $this->getRoleName();
        return "{$this->name} ({$role})";
    }

    public function getInitials()
    {
        $names = explode(' ', $this->name);
        $initials = '';

        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }

        return substr($initials, 0, 2);
    }
}
