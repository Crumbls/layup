<?php

declare(strict_types=1);

namespace Crumbls\Layup\Console\Commands;

use Crumbls\Layup\Models\Page;
use Illuminate\Console\Command;

/**
 * Promotes scheduled pages to "published" once their published_at has
 * passed. Designed to run frequently (every minute) via the scheduler;
 * the service provider registers this automatically unless disabled.
 */
class PublishScheduledCommand extends Command
{
    protected $signature = 'layup:publish-scheduled
                            {--dry-run : Show what would be published without making changes}';

    protected $description = 'Promote scheduled pages whose publish time has arrived.';

    public function handle(): int
    {
        $modelClass = config('layup.pages.model', Page::class);

        $query = $modelClass::query()
            ->where('status', 'scheduled')
            ->where('published_at', '<=', now());

        $count = $query->count();

        if ($count === 0) {
            $this->info('No scheduled pages are due.');

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $query->get(['id', 'title', 'path', 'published_at'])
                ->each(fn ($page) => $this->line(
                    "  - [#{$page->id}] {$page->title} ({$page->path}) — due {$page->published_at}"
                ));

            $this->comment("Would publish {$count} page(s).");

            return self::SUCCESS;
        }

        // Iterate so model events fire (revisions, safelist, observers).
        $query->get()->each(function ($page): void {
            $page->status = 'published';
            $page->save();
            $this->info("Published: {$page->title} ({$page->path})");
        });

        $this->comment("Published {$count} page(s).");

        return self::SUCCESS;
    }
}
