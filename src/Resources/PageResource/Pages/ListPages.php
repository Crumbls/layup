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
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
                ])
                ->action(function (array $data) {
                    $modelClass = config('layup.pages.model', Page::class);
                    $slug = Str::slug($data['title']);

                    if ($modelClass::where('slug', $slug)->exists()) {
                        $slug .= '-' . Str::random(4);
                    }

                    $record = $modelClass::create([
                        'title' => $data['title'],
                        'slug' => $slug,
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
                    $path = storage_path('app/' . $data['file']);

                    if (! file_exists($path)) {
                        Notification::make()->danger()->title(__('layup::notifications.file_not_found'))->send();

                        return;
                    }

                    $json = json_decode(file_get_contents($path), true);
                    @unlink($path);

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

                    // Ensure unique slug
                    if ($modelClass::where('slug', $slug)->exists()) {
                        $slug .= '-' . Str::random(4);
                    }

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
}
