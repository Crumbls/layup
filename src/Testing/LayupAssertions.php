<?php

declare(strict_types=1);

namespace Crumbls\Layup\Testing;

use Crumbls\Layup\Contracts\Widget;
use Crumbls\Layup\Support\ContentWalker;
use Crumbls\Layup\Support\WidgetRegistry;
use Crumbls\Layup\View\BaseView;
use Crumbls\Layup\View\BaseWidget;
use Illuminate\Database\Eloquent\Model;

trait LayupAssertions
{
    /**
     * Assert that a page (or any model with a content column) contains a specific widget type.
     */
    public function assertPageContainsWidget(Model $page, string $type, ?int $expectedCount = null): void
    {
        $content = $page->content ?? [];
        $types = ContentWalker::collectWidgetTypes($content);

        $this->assertArrayHasKey(
            $type,
            $types,
            "Failed asserting that the page contains a '{$type}' widget."
        );

        if ($expectedCount !== null) {
            $this->assertSame(
                $expectedCount,
                $types[$type],
                "Failed asserting that the page contains exactly {$expectedCount} '{$type}' widget(s). Found {$types[$type]}."
            );
        }
    }

    /**
     * Assert that a page does not contain a specific widget type.
     */
    public function assertPageDoesNotContainWidget(Model $page, string $type): void
    {
        $content = $page->content ?? [];
        $types = ContentWalker::collectWidgetTypes($content);

        $this->assertArrayNotHasKey(
            $type,
            $types,
            "Failed asserting that the page does not contain a '{$type}' widget."
        );
    }

    /**
     * Assert that a widget type renders without errors.
     */
    public function assertWidgetRenders(string $type, array $data = []): void
    {
        $registry = app(WidgetRegistry::class);
        $class = $registry->get($type);

        $this->assertNotNull(
            $class,
            "Failed asserting that widget type '{$type}' is registered."
        );

        $widget = $class::make($data ?: $class::getDefaultData());
        $html = $widget->render()->render();

        $this->assertIsString($html, "Failed asserting that widget '{$type}' renders a string.");
        $this->assertNotEmpty($html, "Failed asserting that widget '{$type}' renders non-empty HTML.");
    }

    /**
     * Assert that a page renders without errors.
     */
    public function assertPageRenders(Model $page): void
    {
        $html = $page->toHtml();

        $this->assertIsString($html, 'Failed asserting that the page renders a string.');
        $this->assertNotEmpty($html, 'Failed asserting that the page renders non-empty HTML.');
    }

    /**
     * Assert that a widget class satisfies the full Widget contract.
     *
     * @param  class-string<Widget>  $class
     */
    public function assertWidgetContractValid(string $class): void
    {
        $this->assertTrue(
            is_subclass_of($class, Widget::class),
            "Failed asserting that {$class} implements the Widget contract."
        );

        $type = $class::getType();
        $this->assertNotEmpty($type, "Failed asserting that {$class}::getType() is non-empty.");

        $label = $class::getLabel();
        $this->assertNotEmpty($label, "Failed asserting that {$class}::getLabel() is non-empty.");

        $icon = $class::getIcon();
        $this->assertStringStartsWith(
            'heroicon-',
            $icon,
            "Failed asserting that {$class}::getIcon() starts with 'heroicon-'."
        );

        $category = $class::getCategory();
        $this->assertNotEmpty($category, "Failed asserting that {$class}::getCategory() is non-empty.");

        $formSchema = $class::getFormSchema();
        $this->assertIsArray($formSchema, "Failed asserting that {$class}::getFormSchema() returns an array.");

        $defaults = $class::getDefaultData();
        $this->assertIsArray($defaults, "Failed asserting that {$class}::getDefaultData() returns an array.");

        $preview = $class::getPreview($defaults);
        $this->assertIsString($preview, "Failed asserting that {$class}::getPreview() returns a string.");

        $arr = $class::toArray();
        $this->assertArrayHasKey('type', $arr);
        $this->assertArrayHasKey('label', $arr);
        $this->assertArrayHasKey('icon', $arr);
        $this->assertArrayHasKey('category', $arr);
        $this->assertArrayHasKey('defaults', $arr);
    }

    /**
     * Assert that a widget's getDefaultData() covers all fields in getContentFormSchema().
     *
     * @param  class-string<BaseWidget>  $class
     */
    public function assertDefaultsCoverFormFields(string $class): void
    {
        $fieldNames = $this->extractWidgetFieldNames($class);
        $defaults = $class::getDefaultData();

        $missing = array_diff($fieldNames, array_keys($defaults));

        $this->assertEmpty(
            $missing,
            "Failed asserting that {$class}::getDefaultData() covers all form fields. Missing: " . implode(', ', $missing)
        );
    }

    /**
     * Assert that a widget renders successfully with its default data.
     *
     * @param  class-string<BaseWidget>  $class
     */
    public function assertWidgetRendersWithDefaults(string $class): void
    {
        $defaults = $class::getDefaultData();
        $prepared = $class::prepareForRender($defaults);
        $widget = $class::make($prepared);
        $html = $widget->render()->render();

        $this->assertIsString($html, "Failed asserting that {$class} renders a string with default data.");
        $this->assertNotEmpty($html, "Failed asserting that {$class} renders non-empty HTML with default data.");
    }

    /**
     * Extract field names from a widget's content form schema.
     *
     * @param  class-string<BaseWidget>  $class
     * @return array<string>
     */
    protected function extractWidgetFieldNames(string $class): array
    {
        $components = $class::getContentFormSchema();
        $names = [];

        BaseView::walkComponents($components, function ($component) use (&$names): void {
            if (method_exists($component, 'getName')) {
                $name = $component->getName();
                if ($name !== null && $name !== '') {
                    $names[] = $name;
                }
            }
        });

        return $names;
    }
}
