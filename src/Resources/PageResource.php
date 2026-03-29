<?php

declare(strict_types=1);

namespace Crumbls\Layup\Resources;

use BackedEnum;
use Crumbls\Layup\Forms\Components\LayupBuilder;
use Crumbls\Layup\Models\Page;
use Crumbls\Layup\Resources\PageResource\Pages;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = null;

    public static function getModel(): string
    {
        return config('layup.pages.model', Page::class);
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
                Section::make(__('layup::resource.seo'))
                    ->schema([
                        TextInput::make('meta.description')
                            ->label(__('layup::resource.meta_description'))
                            ->maxLength(160),
                        TextInput::make('meta.keywords')
                            ->label(__('layup::resource.meta_keywords')),
                    ])
                    ->hidden()
                    ->collapsed(),

                LayupBuilder::make('content')
                    ->hiddenLabel(true)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'warning',
                        'published' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => __('layup::resource.draft'),
                        'published' => __('layup::resource.published'),
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([

                    Action::make('view')
                        ->visible(function (Model $record) {
                            return $record->status == 'published';
                        })
                        ->url(function (Model $record) {
                            return Route::has('layup.page.show') ? route('layup.page.show', $record->slug) : null;
                        })
                        ->icon('heroicon-o-eye')
                        ->openUrlInNewTab(),
                    EditAction::make(),
                    Action::make('duplicate')
                        ->label(__('layup::resource.duplicate'))
                        ->icon('heroicon-o-document-duplicate')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(function (Page $record): void {
                            $modelClass = config('layup.pages.model', Page::class);
                            $modelClass::create([
                                'title' => $record->title . ' ' . __('layup::resource.copy_suffix'),
                                'slug' => $record->slug . '-copy-' . Str::random(4),
                                'content' => $record->content,
                                'meta' => $record->meta,
                                'status' => 'draft',
                            ]);
                        }),
                    Action::make('export')
                        ->label(__('layup::resource.export'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
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
                    DeleteAction::make(),
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
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
