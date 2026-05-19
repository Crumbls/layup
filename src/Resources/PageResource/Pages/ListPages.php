<?php

declare(strict_types=1);

namespace Crumbls\Layup\Resources\PageResource\Pages;

use Crumbls\Layup\Models\Page;
use Crumbls\Layup\Resources\PageResource;
use Crumbls\Layup\Support\ContentValidator;
use Crumbls\Layup\Support\PageTemplate;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    public function getTabs(): array
    {
        $modelClass = config('layup.pages.model', Page::class);

        return [
            'all' => Tab::make(__('layup::resource.tab_all'))
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('deleted_at'))
                ->badge(fn (): int => $modelClass::query()->count()),

            'published' => Tab::make(__('layup::resource.published'))
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('deleted_at')->where('status', 'published'))
                ->badge(fn (): int => $modelClass::query()->where('status', 'published')->count()),

            'scheduled' => Tab::make(__('layup::resource.scheduled'))
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('deleted_at')->where('status', 'scheduled'))
                ->badge(fn (): int => $modelClass::query()->where('status', 'scheduled')->count())
                ->badgeColor('info'),

            'draft' => Tab::make(__('layup::resource.draft'))
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('deleted_at')->where('status', 'draft'))
                ->badge(fn (): int => $modelClass::query()->where('status', 'draft')->count()),

            'trash' => Tab::make(__('layup::resource.tab_trash'))
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('deleted_at'))
                ->badge(fn (): int => $modelClass::query()->onlyTrashed()->count())
                ->badgeColor('danger'),
        ];
    }

    protected function getHeaderActions(): array
    {
        $modelClass = config('layup.pages.model', Page::class);

        return [
            Actions\Action::make('emptyTrash')
                ->label(__('layup::resource.empty_trash'))
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->visible(fn (): bool => $this->activeTab === 'trash'
                    && $modelClass::onlyTrashed()->exists())
                ->requiresConfirmation()
                ->modalHeading(__('layup::resource.empty_trash_confirm_heading'))
                ->modalDescription(__('layup::resource.empty_trash_confirm_description'))
                ->action(function () use ($modelClass): void {
                    $count = $modelClass::onlyTrashed()->count();

                    // Iterate so cascade ordering and any model-event side
                    // effects (revisions, safelist sync) fire correctly.
                    $modelClass::onlyTrashed()->get()->each->forceDelete();

                    Notification::make()
                        ->success()
                        ->title(__('layup::resource.empty_trash_done', ['count' => $count]))
                        ->send();
                }),
            Actions\Action::make('create')
                ->label(__('layup::resource.new_page'))
                ->icon('heroicon-o-plus')
                ->modalWidth('md')
                ->schema([
                    Select::make('template')
                        ->label(__('layup::resource.start_from_template'))
                        ->options(PageTemplate::options())
                        ->placeholder(__('layup::resource.blank_page'))
                        ->nullable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, Set $set): void {
                            if ($state) {
                                $template = PageTemplate::get($state);
                                if ($template) {
                                    $set('content', $template['content']);
                                }
                            }
                        }),
                    TextInput::make('title')
                        ->label(__('layup::resource.title'))
                        ->required()
                        ->maxLength(255),
                    Select::make('parent_id')
                        ->label(__('layup::resource.parent_page'))
                        ->options(fn (): array => PageResource::parentOptions())
                        ->searchable()
                        ->placeholder(__('layup::resource.no_parent_top_level'))
                        ->nullable(),
                ])
                ->action(function (array $data) {
                    $modelClass = config('layup.pages.model', Page::class);
                    $parentId = $data['parent_id'] ?? null;
                    $slug = $this->uniqueSlugUnder(Str::slug($data['title']), $parentId);

                    $record = $modelClass::create([
                        'title' => $data['title'],
                        'slug' => $slug,
                        'parent_id' => $parentId,
                        'content' => $data['content'] ?? ['rows' => []],
                        'status' => 'draft',
                    ]);

                    return redirect(PageResource::getUrl('edit', ['record' => $record]));
                }),
            Actions\Action::make('import')
                ->label(__('layup::resource.import'))
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->form([
                    FileUpload::make('file')
                        ->label(__('layup::resource.json_file'))
                        ->acceptedFileTypes(['application/json'])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $disk = config('filament.default_filesystem_disk', 'public');
                    $diskInstance = Storage::disk($disk);
                    $file = $data['file'];

                    if (! $diskInstance->exists($file)) {
                        Notification::make()->danger()->title(__('layup::notifications.file_not_found'))->send();

                        return;
                    }

                    $contents = null;

                    try {
                        $contents = $diskInstance->get($file);

                        if ($contents === null) {
                            Notification::make()->danger()->title(__('layup::notifications.file_not_found'))->send();

                            return;
                        }

                        if (! $diskInstance->delete($file)) {
                            Notification::make()->warning()->title(__('layup::notifications.file_delete_failed'))->send();
                        }
                    } catch (\Throwable $e) {
                        Notification::make()->danger()->title(__('layup::notifications.file_not_found'))->send();

                        return;
                    }

                    $json = json_decode($contents, true);

                    if (! $json || ! isset($json['content'])) {
                        Notification::make()->danger()->title(__('layup::notifications.invalid_json'))->send();

                        return;
                    }

                    $validator = new ContentValidator;
                    if (! $validator->validate($json['content'])) {
                        Notification::make()->danger()->title(__('layup::notifications.invalid_content_structure'))->send();

                        return;
                    }

                    $modelClass = config('layup.pages.model', Page::class);
                    $slug = $json['slug'] ?? Str::slug($json['title'] ?? 'imported');
                    $slug = $this->uniqueSlugUnder($slug, null);

                    $modelClass::create([
                        'title' => $json['title'] ?? 'Imported Page',
                        'slug' => $slug,
                        'content' => $json['content'],
                        'meta' => $json['meta'] ?? [],
                        'status' => 'draft',
                    ]);

                    Notification::make()->success()->title(__('layup::notifications.page_imported'))->send();
                }),
        ];
    }

    /**
     * Build a slug guaranteed unique within the given parent scope by
     * checking the resulting `path` rather than a global slug uniqueness.
     */
    protected function uniqueSlugUnder(string $slug, int|string|null $parentId): string
    {
        $modelClass = config('layup.pages.model', Page::class);
        $parentPath = $parentId
            ? ($modelClass::find($parentId)?->path ?? '')
            : '';

        $candidate = $slug;
        $proposedPath = $parentPath === '' ? $candidate : "{$parentPath}/{$candidate}";

        if ($modelClass::query()->where('path', $proposedPath)->exists()) {
            $candidate = $slug . '-' . Str::random(4);
        }

        return $candidate;
    }
}
