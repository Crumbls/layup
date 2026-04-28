<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected function tableName(): string
    {
        return config('layup.pages.table', 'layup_pages');
    }

    public function up(): void
    {
        $table = $this->tableName();

        Schema::table($table, function (Blueprint $blueprint): void {
            $blueprint->foreignId('parent_id')
                ->nullable()
                ->after('id')
                ->constrained($this->tableName())
                ->nullOnDelete();

            $blueprint->string('path')->nullable()->after('slug');
        });

        Schema::table($table, function (Blueprint $blueprint): void {
            $blueprint->dropUnique(['slug']);
            $blueprint->index('slug');
        });

        // Backfill: existing rows have no parent yet, so path mirrors slug.
        // Slugs that already contain "/" (legacy nested-as-slug pages) become
        // single-row paths until manually split into parent/child rows.
        DB::table($table)
            ->whereNull('path')
            ->update(['path' => DB::raw('slug')]);

        Schema::table($table, function (Blueprint $blueprint): void {
            $blueprint->string('path')->nullable(false)->change();
            $blueprint->unique('path');
        });
    }
};
