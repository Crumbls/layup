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
            $blueprint->string('featured_image')->nullable()->after('meta');
        });

        // Backfill from any pre-existing meta.image set by templates or
        // imports — gives upgraders an instant featured-image value with
        // zero command runs. Stored as a path/URL string for now; a media
        // library swap later can wrap this into a relation without
        // changing the column type.
        DB::table($table)
            ->whereNull('featured_image')
            ->whereNotNull('meta')
            ->orderBy('id')
            ->each(function ($row) use ($table): void {
                $meta = json_decode($row->meta ?? 'null', true);

                if (! is_array($meta) || empty($meta['image'])) {
                    return;
                }

                DB::table($table)
                    ->where('id', $row->id)
                    ->update(['featured_image' => $meta['image']]);
            });
    }
};
