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
}
