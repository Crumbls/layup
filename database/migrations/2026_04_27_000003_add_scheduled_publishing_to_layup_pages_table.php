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
            $blueprint->timestamp('published_at')->nullable()->after('status');
            $blueprint->index('published_at');
        });

        // Zero-command upgrade: existing published rows get a sensible
        // publish date so legacy data participates in the new "scheduled"
        // workflow without anyone having to run a backfill command.
        DB::table($table)
            ->where('status', 'published')
            ->whereNull('published_at')
            ->update(['published_at' => DB::raw('updated_at')]);
    }
};
