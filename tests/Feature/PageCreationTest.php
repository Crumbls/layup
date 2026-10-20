<?php

declare(strict_types=1);

namespace Crumbls\Layup\Tests\Feature;

use Crumbls\Layup\Models\Page;
use Crumbls\Layup\Resources\PageResource;
use Crumbls\Layup\Resources\PageResource\Pages\ListPages;
use Crumbls\Layup\Support\PageTemplate;
use Crumbls\Layup\Tests\TestCase;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;

class PageCreationTest extends TestCase
{
    private ?string $customTemplatePath = null;

    protected function getPackageProviders($app): array
    {
        return [
            \BladeUI\Icons\BladeIconsServiceProvider::class,
            \BladeUI\Heroicons\BladeHeroiconsServiceProvider::class,
            \Filament\Support\SupportServiceProvider::class,
            ...parent::getPackageProviders($app),
            \Filament\Actions\ActionsServiceProvider::class,
            \Filament\Forms\FormsServiceProvider::class,
            \Filament\Infolists\InfolistsServiceProvider::class,
            \Filament\Notifications\NotificationsServiceProvider::class,
            \Filament\Schemas\SchemasServiceProvider::class,
            \Filament\Tables\TablesServiceProvider::class,
            \Filament\Widgets\WidgetsServiceProvider::class,
            \Filament\FilamentServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $panel = Panel::make()
            ->id('test')
            ->default()
            ->resources([PageResource::class]);

        Filament::registerPanel($panel);
        Filament::setCurrentPanel($panel);
        Filament::bootCurrentPanel();

        Route::name('filament.test.')->group(fn () => PageResource::registerRoutes($panel));
        Route::getRoutes()->refreshNameLookups();

        $this->actingAs((new User)->forceFill([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]));
    }

    protected function tearDown(): void
    {
        if ($this->customTemplatePath !== null) {
            File::delete($this->customTemplatePath);
        }

        parent::tearDown();
    }

    public static function builtInTemplates(): array
    {
        return [
            'landing page' => ['landing-page'],
            'about' => ['about'],
            'contact' => ['contact'],
            'pricing' => ['pricing'],
        ];
    }

    #[DataProvider('builtInTemplates')]
    public function test_creates_a_page_with_the_selected_built_in_template_content(string $template): void
    {
        Livewire::test(ListPages::class)
            ->callAction('create', data: [
                'template' => $template,
                'title' => 'Template Page',
            ])
            ->assertHasNoActionErrors();

        $page = Page::query()->where('slug', 'template-page')->sole();

        $this->assertSame(PageTemplate::get($template)['content'], $page->content);
        $this->assertSame(Page::STATUS_DRAFT, $page->status);
    }

    public function test_creates_a_page_with_a_template_saved_from_an_existing_page(): void
    {
        $source = Page::create([
            'title' => 'Source Page',
            'slug' => 'source-page',
            'content' => PageTemplate::get('about')['content'],
            'status' => Page::STATUS_DRAFT,
        ]);
        $template = PageTemplate::saveFromPage('Saved Page ' . Str::uuid(), $source->content);
        $this->customTemplatePath = resource_path('layup/templates/' . $template . '.json');

        Livewire::test(ListPages::class)
            ->callAction('create', data: [
                'template' => $template,
                'title' => 'Copied Page',
            ])
            ->assertHasNoActionErrors();

        $page = Page::query()->where('slug', 'copied-page')->sole();

        $this->assertSame($source->content, $page->content);
    }

    public static function blankTemplates(): array
    {
        return [
            'no selection' => [null],
            'blank template' => ['blank'],
        ];
    }

    #[DataProvider('blankTemplates')]
    public function test_creates_a_blank_page(?string $template): void
    {
        Livewire::test(ListPages::class)
            ->callAction('create', data: [
                'template' => $template,
                'title' => 'Blank Page',
            ])
            ->assertHasNoActionErrors();

        $this->assertSame(['rows' => []], Page::query()->sole()->content);
    }

    public function test_clearing_a_template_selection_creates_a_blank_page(): void
    {
        Livewire::test(ListPages::class)
            ->mountAction('create')
            ->setActionData(['template' => 'landing-page', 'title' => 'Blank Page'])
            ->setActionData(['template' => null])
            ->callMountedAction()
            ->assertHasNoActionErrors();

        $this->assertSame(['rows' => []], Page::query()->sole()->content);
    }

    public function test_changing_templates_uses_the_final_selection(): void
    {
        Livewire::test(ListPages::class)
            ->mountAction('create')
            ->setActionData(['template' => 'landing-page', 'title' => 'About Page'])
            ->setActionData(['template' => 'about'])
            ->callMountedAction()
            ->assertHasNoActionErrors();

        $this->assertSame(PageTemplate::get('about')['content'], Page::query()->sole()->content);
    }
}
