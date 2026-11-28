<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FeatureContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_tables_have_columns_that_claim_and_matching_depend_on(): void
    {
        $this->assertTrue(Schema::hasColumns('items', [
            'user_id', 'type', 'category', 'location', 'description',
            'status', 'event_at', 'verification_question', 'verification_answer',
        ]));
        $this->assertTrue(Schema::hasColumns('claims', [
            'item_id', 'user_id', 'answer', 'proof_image', 'status', 'reviewed_at',
        ]));
    }

    public function test_item_model_exposes_what_claim_and_matching_use(): void
    {
        foreach (['STATUS_OPEN', 'STATUS_RESOLVED', 'STATUS_EXPIRED', 'TYPE_LOST', 'TYPE_FOUND'] as $const) {
            $this->assertTrue(defined(Item::class.'::'.$const), "Item::{$const} hilang");
        }
        foreach (['scopeFound', 'scopeOpen', 'claims', 'claimants', 'isExpired'] as $method) {
            $this->assertTrue(method_exists(Item::class, $method), "Item::{$method}() hilang");
        }
    }
}