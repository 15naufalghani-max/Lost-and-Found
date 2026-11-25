<?php

namespace App\Models;

use Database\Factories\ClaimFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    /** @use HasFactory<ClaimFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'item_id',
        'user_id',
        'answer',
        'proof_image',
        'status',
        'reviewed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Compare a claimant's submitted answer against the item's secret verification answer.
     */
    public static function matchesSecretAnswer(string $submitted, ?string $expected): bool
    {
        $normalizedExpected = self::normalizeText((string) $expected);
        $normalizedSubmitted = self::normalizeText($submitted);

        if ($normalizedExpected === '' || $normalizedSubmitted === '') {
            return false;
        }

        return $normalizedSubmitted === $normalizedExpected
            || str_contains($normalizedSubmitted, $normalizedExpected);
    }

    private static function normalizeText(string $value): string
    {
        return preg_replace('/\s+/u', ' ', mb_strtolower(trim($value))) ?? '';
    }
}
