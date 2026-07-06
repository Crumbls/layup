<?php

declare(strict_types=1);

namespace Crumbls\Layup\Resources;

use BackedEnum;
use Crumbls\Layup\Forms\Components\LayupBuilder;
use Crumbls\Layup\Models\Page;
use Crumbls\Layup\Resources\PageResource\Pages;
use Crumbls\Layup\Support\PageLayout;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = null;

    public static function getModel(): string
    {
        return config('layup.pages.model', Page::class);
    }

    /**
     * Include trashed pages in the base query so the Trash tab can find them.
     * Each list tab applies its own visibility filter on top of this.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    public static function getNavigationGroup(): ?string
    {
        return __('layup::resource.navigation_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('layup::resource.navigation_label');
    }

    protected static ?string $slug = 'pages';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('permalink')
                    ->label(__('layup::resource.permalink'))
                    ->content(fn (?Model $record): HtmlString => self::renderPermalink($record))
                    ->columnSpanFull()
                    ->visible(fn (?Model $record): bool => (bool) $record),

                LayupBuilder::make('content')
                    ->hiddenLabel(true)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Render the status indicator shown above the builder. Draft and
     * scheduled pages show a muted "not published" pill instead of a
     * live link, since the URL only resolves once the page is published.
     */
    protected static function renderPermalink(?Model $record): HtmlString
    {
        if (! $record || ! $record->path) {
            return new HtmlString('');
        }

        if (! $record->isPublished()) {
            return self::renderUnpublishedPill($record);
        }

        $url = $record->getUrl();
        $segments = explode('/', (string) $record->path);
        $slug = array_pop($segments);
        $parentPath = implode('/', $segments);

        $prefix = trim((string) config('layup.frontend.prefix', 'pages'), '/');
        $base = url($prefix === '' ? '/' : "/{$prefix}");
        $baseDisplay = $parentPath !== ''
            ? rtrim($base, '/') . '/' . $parentPath . '/'
            : rtrim($base, '/') . '/';

        $href = e($url);
        $baseHtml = e($baseDisplay);
        $slugHtml = e($slug);

        return new HtmlString(
            '<a href="' . $href . '" target="_blank" rel="noopener" '
            . 'class="text-sm text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 inline-flex items-baseline gap-1">'
            . '<span>' . $baseHtml . '</span>'
            . '<span class="font-semibold text-gray-900 dark:text-gray-100">' . $slugHtml . '</span>'
            . '</a>'
        );
    }

    /**
     * Pill shown for draft / scheduled pages in place of the permalink.
     * Scheduled pages surface their go-live time so the editor knows the
     * page is queued rather than simply unpublished.
     */
    protected static function renderUnpublishedPill(Model $record): HtmlString
    {
        if ($record->status === Page::STATUS_SCHEDULED && $record->published_at) {
            $label = __('layup::resource.scheduled_for', [
                'time' => $record->published_at->isoFormat('lll'),
            ]);
            $classes = 'bg-info-50 text-info-700 dark:bg-info-400/10 dark:text-info-400';
        } else {
            $label = __('layup::resource.draft_not_published');
            $classes = 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400';
        }

        return new HtmlString(
            '<span class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-sm font-medium ' . $classes . '">'
            . '<span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>'
            . e($label)
            . '</span>'
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('path')
            ->columns([
                ImageColumn::make('featured_image')
                    ->label(__('layup::resource.featured_image'))
                    ->disk(config('layup.uploads.disk', 'public'))
                    ->square()
                    ->size(40)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->formatStateUsing(function (Model $record, ?string $state): string {
                        $depth = $record->path ? substr_count((string) $record->path, '/') : 0;

                        return str_repeat('— ', $depth) . (string) $state;
                    })
                    ->description(fn (Model $record): ?string => $record->slug)
                    ->searchable(['title', 'slug'])
                    ->sortable(),
                TextColumn::make('path')
                    ->label(__('layup::resource.path'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'warning',
                        'published' => 'success',
                        'scheduled' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('author')
                    ->label(__('layup::resource.author'))
                    ->placeholder('—')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('parent_id')
                    ->label(__('layup::resource.parent_page'))
                    ->options(function (): array {
                        $modelClass = config('layup.pages.model', Page::class);

                        return $modelClass::query()
                            ->whereIn('id', $modelClass::query()->whereNotNull('parent_id')->select('parent_id'))
                            ->orderBy('path')
                            ->get()
                            ->mapWithKeys(fn (Model $page): array => [
                                $page->getKey() => $page->path
                                    ? "{$page->title} ({$page->path})"
                                    : $page->title,
                            ])
                            ->all();
                    })
                    ->searchable(),
            ])
            ->recordActions([
                ActionGroup::make([

                    Action::make('view')
                        ->visible(fn (Model $record): bool => $record->status === 'published' && ! $record->trashed())
                        ->url(function (Model $record) {
                            return Route::has('layup.page.show') ? route('layup.page.show', $record->path) : null;
                        })
                        ->icon('heroicon-o-eye')
                        ->openUrlInNewTab(),
                    EditAction::make()
                        ->visible(fn (Model $record): bool => ! $record->trashed()),
                    Action::make('quickEdit')
                        ->label(__('layup::resource.quick_edit'))
                        ->icon('heroicon-o-pencil-square')
                        ->color('gray')
                        ->modalWidth('md')
                        ->visible(fn (Model $record): bool => ! $record->trashed())
                        ->fillForm(fn (Model $record): array => self::settingsFillData($record))
                        ->schema(fn (Model $record): array => self::settingsFormSchema($record))
                        ->action(fn (array $data, Model $record) => self::applySettings($record, $data)),
                    Action::make('duplicate')
                        ->label(__('layup::resource.duplicate'))
                        ->icon('heroicon-o-document-duplicate')
                        ->color('gray')
                        ->visible(fn (Model $record): bool => ! $record->trashed())
                        ->requiresConfirmation()
                        ->action(function (Page $record): void {
                            $modelClass = config('layup.pages.model', Page::class);
                            $modelClass::create([
                                'title' => $record->title . ' ' . __('layup::resource.copy_suffix'),
                                'slug' => $record->slug . '-copy-' . Str::random(4),
                                'parent_id' => $record->parent_id,
                                'content' => $record->content,
                                'meta' => $record->meta,
                                'status' => 'draft',
                            ]);
                        }),
                    Action::make('export')
                        ->label(__('layup::resource.export'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->visible(fn (Model $record): bool => ! $record->trashed())
                        ->action(function (Page $record) {
                            $json = json_encode([
                                'title' => $record->title,
                                'slug' => $record->slug,
                                'content' => $record->content,
                                'meta' => $record->meta,
                                'exported_at' => now()->toIso8601String(),
                                'layup_version' => '1.0',
                            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

                            return response()->streamDownload(
                                fn () => print ($json),
                                Str::slug($record->title) . '.json',
                                ['Content-Type' => 'application/json'],
                            );
                        }),
                    DeleteAction::make()
                        ->visible(fn (Model $record): bool => ! $record->trashed()),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ]),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->label(__('layup::resource.publish'))
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'published'])),
                    BulkAction::make('unpublish')
                        ->label(__('layup::resource.unpublish'))
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'draft'])),
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Shared form schema for Quick Edit and Page Settings — the title/slug/
     * parent/status quartet. Extracted so both contexts validate identically
     * (path-uniqueness scoped to the chosen parent).
     */
    public static function settingsFormSchema(?Model $record = null): array
    {
        return [
            TextInput::make('title')
                ->label(__('layup::resource.title'))
                ->required()
                ->maxLength(255),
            TextInput::make('slug')
                ->label(__('layup::resource.slug'))
                ->required()
                ->maxLength(255)
                ->rules([
                    fn (Get $get): \Closure => function (string $attribute, mixed $value, \Closure $fail) use ($get, $record): void {
                        $modelClass = config('layup.pages.model', Page::class);
                        $parentId = $get('parent_id');
                        $parentPath = $parentId
                            ? ($modelClass::find($parentId)?->path ?? '')
                            : '';

                        $proposedPath = $parentPath === ''
                            ? (string) $value
                            : $parentPath . '/' . $value;

                        $query = $modelClass::query()->where('path', $proposedPath);

                        if ($record?->getKey()) {
                            $query->where('id', '!=', $record->getKey());
                        }

                        if ($query->exists()) {
                            $fail(__('layup::resource.slug_taken_under_parent'));
                        }
                    },
                ]),
            Select::make('parent_id')
                ->label(__('layup::resource.parent_page'))
                ->options(fn (): array => self::parentOptions($record?->getKey()))
                ->searchable()
                ->placeholder(__('layup::resource.no_parent_top_level'))
                ->nullable(),
            Select::make('status')
                ->label(__('layup::resource.status'))
                ->options([
                    'draft' => __('layup::resource.draft'),
                    'published' => __('layup::resource.published'),
                    'scheduled' => __('layup::resource.scheduled'),
                ])
                ->required()
                ->helperText(__('layup::resource.status_help')),
            DateTimePicker::make('published_at')
                ->label(__('layup::resource.publish_at'))
                ->seconds(false)
                ->helperText(__('layup::resource.publish_at_help'))
                ->nullable(),
            FileUpload::make('featured_image')
                ->label(__('layup::resource.featured_image'))
                ->image()
                ->imageEditor()
                ->disk(config('layup.uploads.disk', 'public'))
                ->directory('layup/featured')
                ->helperText(__('layup::resource.featured_image_help'))
                ->nullable(),
            Select::make('container_preset')
                ->label(__('layup::resource.container_preset'))
                ->options(fn (): array => PageLayout::options())
                ->placeholder(__('layup::resource.container_preset_default'))
                ->helperText(__('layup::resource.container_preset_help'))
                ->nullable(),
            Select::make('template_preset')
                ->label(__('layup::resource.template_preset'))
                ->options(fn (): array => PageLayout::templateOptions())
                ->placeholder(__('layup::resource.template_preset_default'))
                ->helperText(__('layup::resource.template_preset_help'))
                ->visible(fn (): bool => PageLayout::templateOptions() !== [])
                ->nullable(),
            Textarea::make('meta_description')
                ->label(__('layup::resource.meta_description'))
                ->helperText(__('layup::resource.meta_description_help'))
                ->maxLength(160)
                ->rows(2)
                ->nullable(),
            Toggle::make('meta_noindex')
                ->label(__('layup::resource.noindex'))
                ->helperText(__('layup::resource.noindex_help')),
        ];
    }

    /**
     * Build the fillForm payload shared by Page Settings + Quick Edit so
     * both modals seed the same fields, including meta-derived ones.
     */
    public static function settingsFillData(Model $record): array
    {
        return [
            'title' => $record->title,
            'slug' => $record->slug,
            'parent_id' => $record->parent_id,
            'status' => $record->status,
            'published_at' => $record->published_at,
            'featured_image' => $record->featured_image,
            'container_preset' => $record->meta['layout']['container'] ?? null,
            'template_preset' => $record->meta['layout']['template'] ?? null,
            'meta_description' => $record->meta['description'] ?? null,
            'meta_noindex' => (bool) ($record->meta['noindex'] ?? false),
        ];
    }

    /**
     * Apply settings-modal data to a page, merging the virtual
     * `container_preset`field into the JSON `meta` column instead of
     * letting it land as a top-level attribute.
     */
    public static function applySettings(Model $record, array $data): void
    {
        $containerPreset = $data['container_preset'] ?? null;
        $templatePreset = $data['template_preset'] ?? null;
        $metaDescription = $data['meta_description'] ?? null;
        $metaNoindex = (bool) ($data['meta_noindex'] ?? false);
        unset(
            $data['container_preset'],
            $data['template_preset'],
            $data['meta_description'],
            $data['meta_noindex'],
        );

        $meta = $record->meta ?? [];
        $meta['layout'] = $meta['layout'] ?? [];

        self::setOrUnset($meta['layout'], 'container', $containerPreset);
        self::setOrUnset($meta['layout'], 'template', $templatePreset);

        if (empty($meta['layout'])) {
            unset($meta['layout']);
        }

        self::setOrUnset($meta, 'description', $metaDescription);

        if ($metaNoindex) {
            $meta['noindex'] = true;
        } else {
            unset($meta['noindex']);
        }

        $data['meta'] = $meta;

        $record->update($data);
    }

    /**
     * Tiny helper that sets a key when truthy and removes it when not,
     * keeping the meta array tidy when users clear a preset.
     */
    protected static function setOrUnset(array &$target, string $key, mixed $value): void
    {
        if ($value !== null && $value !== '') {
            $target[$key] = $value;

            return;
        }

        unset($target[$key]);
    }

    /**
     * Build a flat options list of pages eligible to be a parent.
     *
     * Excludes the editing record itself and all of its descendants — this
     * keeps obvious cycle-creating choices out of the dropdown. The trait's
     * saving guard remains the authoritative check.
     */
    public static function parentOptions(int|string|null $excludeId = null): array
    {
        $modelClass = config('layup.pages.model', Page::class);

        $query = $modelClass::query()->orderBy('path');

        if ($excludeId !== null) {
            $excludeIds = static::collectDescendantIds((int) $excludeId);
            $query->whereNotIn('id', $excludeIds);
        }

        return $query->get()->mapWithKeys(fn (Model $page): array => [
            $page->getKey() => $page->path
                ? "{$page->title} ({$page->path})"
                : $page->title,
        ])->all();
    }

    /**
     * @return array<int, int>
     */
    protected static function collectDescendantIds(int $rootId): array
    {
        $modelClass = config('layup.pages.model', Page::class);

        $ids = [$rootId];
        $frontier = [$rootId];

        while ($frontier !== []) {
            $children = $modelClass::query()
                ->whereIn('parent_id', $frontier)
                ->pluck('id')
                ->all();

            if ($children === []) {
                break;
            }

            $ids = array_merge($ids, $children);
            $frontier = $children;
        }

        return $ids;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
