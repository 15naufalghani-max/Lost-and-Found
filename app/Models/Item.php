<?php

namespace App\Models;

use Database\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    /** @use HasFactory<ItemFactory> */
    use HasFactory;

    public const TYPE_LOST = 'lost';

    public const TYPE_FOUND = 'found';

    public const TYPES = [
        self::TYPE_LOST,
        self::TYPE_FOUND,
    ];

    public const STATUS_OPEN = 'open';

    public const STATUS_RESOLVED = 'resolved';

    public const STATUS_EXPIRED = 'expired';

    public const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_RESOLVED,
        self::STATUS_EXPIRED,
    ];

    public const CATEGORIES = [
        'Elektronik',
        'Dokumen & KTM',
        'Dompet & Tas',
        'Kunci',
        'Aksesoris',
        'Lainnya',
    ];

    public const LOCATIONS = [
        'Gedung FIK',
        'Lab Komputer FIK',
        'Perpustakaan',
        'Kantin',
        'Masjid',
        'Parkiran',
        'Ruang Kelas',
        'Lainnya',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'type',
        'category',
        'location',
        'description',
        'image',
        'status',
        'event_at',
        'expires_at',
        'verification_question',
        'verification_answer',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'verification_answer',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopeLost(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_LOST);
    }

    public function scopeFound(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_FOUND);
    }

    public function claimants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'claims')
            ->withPivot('id', 'answer', 'proof_image', 'status', 'reviewed_at')
            ->withTimestamps();
    }

    /**
     * Filter items by keyword, type, category, location, and status.
     *
     * @param array<string, mixed> $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $keyword = trim((string) ($filters['search'] ?? $filters['q'] ?? ''));
        $type = (string) ($filters['type'] ?? '');
        $category = (string) ($filters['category'] ?? '');
        $location = (string) ($filters['location'] ?? '');
        $status = (string) ($filters['status'] ?? '');

        return $query
            ->when($keyword !== '', function (Builder $q) use ($keyword) {
                $like = '%' . addcslashes($keyword, '%_\\') . '%';
                $q->where(function (Builder $sub) use ($like) {
                    $sub->where('title', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhere('location', 'like', $like)
                        ->orWhere('category', 'like', $like);
                });
            })
            ->when(in_array($type, self::TYPES, true), fn (Builder $q) => $q->where('type', $type))
            ->when($category !== '' && $category !== 'all', fn (Builder $q) => $q->where('category', $category))
            ->when($location !== '' && $location !== 'all', fn (Builder $q) => $q->where('location', $location))
            ->when(in_array($status, self::STATUSES, true), fn (Builder $q) => $q->where('status', $status));
    }

    /**
     * Transition any open items past their expires_at timestamp to expired.
     */
    public static function expireOverdue(): int
    {
        return static::query()
            ->where('status', self::STATUS_OPEN)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update(['status' => self::STATUS_EXPIRED]);
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED
            || ($this->status === self::STATUS_OPEN && $this->expires_at !== null && $this->expires_at->isPast());
    }
}
