<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PragmaRX\Google2FA\Google2FA;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google2fa_secret',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ==================== METHOD ROLE BARU ====================

    /**
     * Check user role based on email (BACKWARD COMPATIBLE)
     */
    public function isUser()
    {
        return !$this->isAdmin() && !$this->isPetugasPengajuan() &&
               !$this->isManajerPersetujuan() && !$this->isPetugasBarangKeluar();
    }

    public function isAdmin()
    {
        return $this->email === 'admin@storage.com';
    }

    public function isPetugasPengajuan()
    {
        return $this->email === 'admin1@storage.com' || $this->isAdmin();
    }

    public function isManajerPersetujuan()
    {
        return $this->email === 'admin2@storage.com' || $this->isAdmin();
    }

    public function isPetugasBarangKeluar()
    {
        return $this->email === 'admin3@storage.com' || $this->isAdmin();
    }

    /**
     * Get role name for display
     */
    public function getRoleName()
    {
        if ($this->isAdmin()) return 'Main Admin';
        if ($this->isPetugasPengajuan()) return 'Petugas Pengajuan';
        if ($this->isManajerPersetujuan()) return 'Manajer Persetujuan';
        if ($this->isPetugasBarangKeluar()) return 'Petugas Barang Keluar';
        return 'User';
    }

    // ==================== RELATIONSHIPS PEMINJAMAN BARU ====================

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

    // ==================== METHOD EXISTING (SEMUA DIPERTAHANKAN) ====================

    /**
     * Enable 2FA for the user
     */
    public function enable2FA()
    {
        $google2fa = app('pragmarx.google2fa');
        $this->google2fa_secret = $google2fa->generateSecretKey();
        $this->save();

        return $this->google2fa_secret;
    }

    /**
     * Verify 2FA code
     */
    public function verify2FACode($code)
    {
        $google2fa = app('pragmarx.google2fa');
        return $google2fa->verifyKey($this->google2fa_secret, $code);
    }

    /**
     * Check if 2FA is enabled
     */
    public function is2FAEnabled()
    {
        return !empty($this->google2fa_secret);
    }

    /**
     * Relationship with Laporan
     */
    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }

    /**
     * Get the user's notifications
     */
    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    /**
     * Get the user's unread notifications
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        $this->unreadNotifications()->update(['read_at' => now()]);
    }

    /**
     * Get user's recent activities
     */
    public function getRecentActivities($limit = 5)
    {
        return $this->laporan()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get user's statistics
     */
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

    /**
     * Scope for admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('email', 'admin@storage.com');
    }

    /**
     * Scope for regular users
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('email', '!=', 'admin@storage.com');
    }

    /**
     * Get user's notification preferences
     */
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

    /**
     * Check if user can delete laporan
     */
    public function canDeleteLaporan($laporan)
    {
        return $this->isAdmin() || $this->id === $laporan->user_id;
    }

    /**
     * Check if user can edit laporan
     */
    public function canEditLaporan($laporan)
    {
        return $this->isAdmin() || $this->id === $laporan->user_id;
    }

    /**
     * Get user's display name with role
     */
    public function getDisplayNameWithRole()
    {
        $role = $this->getRoleName();
        return "{$this->name} ({$role})";
    }

    /**
     * Get user's initial for avatar
     */
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
