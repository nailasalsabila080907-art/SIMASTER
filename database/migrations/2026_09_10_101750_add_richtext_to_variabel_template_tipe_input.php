<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
 
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE variabel_template MODIFY COLUMN tipe_input ENUM(
            'text', 'textarea', 'date', 'number', 'select', 'richtext'
        ) NOT NULL");
    }
 
    public function down(): void
    {
        DB::statement("ALTER TABLE variabel_template MODIFY COLUMN tipe_input ENUM(
            'text', 'textarea', 'date', 'number', 'select'
        ) NOT NULL");
    }
};
 