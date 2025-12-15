<?php

namespace IdGenerator;

use Illuminate\Support\Facades\DB;

class IdGenerator
{
    public function daily(
        string $sequenceKey,   // Model::class or any string
        string $prefix,        // INV, BK, ORD
        string $idColumn,      // invoice_number, booking_id
        ?string $date = null,  // YYYY-MM-DD
        int $pad = 5
    ): string {
        $date = $date ?? now()->toDateString();
        $formatted = date('dmY', strtotime($date));

        DB::statement("
            INSERT INTO id_sequences
                (model, prefix, seq_date, id_column, counter, created_at, updated_at)
            VALUES
                (?, ?, ?, ?, 1, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                counter = LAST_INSERT_ID(counter + 1),
                updated_at = NOW()
        ", [$sequenceKey, $prefix, $date, $idColumn]);

        $next = (int) DB::selectOne(
            "SELECT LAST_INSERT_ID() AS seq"
        )->seq;

        return "{$prefix}-{$formatted}-" .
            str_pad((string) $next, $pad, '0', STR_PAD_LEFT);
    }
}
