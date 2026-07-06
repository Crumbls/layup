<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected function tableName(): string
    {
        return config('layup.revisions.table', 'layup_page_revisions');
    }

    protected function pagesTableName(): string
    {
        return config('layup.pages.table', 'layup_pages');
    }

    public function up(): void
    {
        Schema::create($this->tableName(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained($this->pagesTableName())->cascadeOnDelete();
            $table->json('content');
            $table->string('note')->nullable();
            $table->string('author')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName());
    }
};
