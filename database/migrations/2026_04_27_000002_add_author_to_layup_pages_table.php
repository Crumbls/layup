<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected function tableName(): string
    {
        return config('layup.pages.table', 'layup_pages');
    }

    public function up(): void
    {
        Schema::table($this->tableName(), function (Blueprint $blueprint): void {
            // Stored as a string so the package stays agnostic about the
            // host app's User model. Mirrors how PageRevision records authors.
            $blueprint->string('author')->nullable()->after('meta');
        });
    }
};
