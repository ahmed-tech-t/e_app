<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER trk_check_batch_limit_insert
            BEFORE INSERT ON product_batches
            FOR EACH ROW
            BEGIN
                IF NEW.remaining_quantity > NEW.initial_quantity THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'The remaining quantity cannot be greater than the initial quantity.';
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trk_check_batch_limit_update
            BEFORE UPDATE ON product_batches
            FOR EACH ROW
            BEGIN
                IF NEW.remaining_quantity > NEW.initial_quantity THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'The remaining quantity cannot be greater than the initial quantity.';
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trk_check_batch_limit_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trk_check_batch_limit_update');
    }
};



// CREATE TRIGGER [trigger_name]
// [BEFORE/AFTER] [INSERT/UPDATE/DELETE] ON [table_name]
// FOR EACH ROW
// BEGIN
//     IF NEW.[field_name] > NEW.[field_name] THEN
//         -- logic here
//     END IF;
// END