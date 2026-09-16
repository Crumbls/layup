<?php

declare(strict_types=1);

use Crumbls\Layup\View\AccordionWidget;
use Crumbls\Layup\View\AlertWidget;
use Crumbls\Layup\View\AnchorWidget;
use Crumbls\Layup\View\BackToTopWidget;
use Crumbls\Layup\View\BarCounterWidget;
use Crumbls\Layup\View\BeforeAfterWidget;
use Crumbls\Layup\View\ButtonWidget;
use Crumbls\Layup\View\ContactFormWidget;
use Crumbls\Layup\View\ContentToggleWidget;
use Crumbls\Layup\View\CookieConsentWidget;
use Crumbls\Layup\View\CountdownWidget;
use Crumbls\Layup\View\GalleryWidget;
use Crumbls\Layup\View\MapWidget;
use Crumbls\Layup\View\ModalWidget;
use Crumbls\Layup\View\NewsletterWidget;
use Crumbls\Layup\View\NotificationBarWidget;
use Crumbls\Layup\View\PricingToggleWidget;
use Crumbls\Layup\View\ProgressCircleWidget;
use Crumbls\Layup\View\QuoteCarouselWidget;
use Crumbls\Layup\View\ShareButtonsWidget;
use Crumbls\Layup\View\SliderWidget;
use Crumbls\Layup\View\TableOfContentsWidget;
use Crumbls\Layup\View\TabsWidget;
use Crumbls\Layup\View\TestimonialCarouselWidget;
use Crumbls\Layup\View\TestimonialSliderWidget;
use Crumbls\Layup\View\ToggleWidget;
use Crumbls\Layup\View\TypewriterWidget;
use Crumbls\Layup\View\VideoPlaylistWidget;

function layupOpeningTag(string $html): string
{
    $html = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $html) ?? $html;
    preg_match('/<[a-z][a-z0-9-]*(?:\s+(?:[^"\'<>]|"[^"]*"|\'[^\']*\')*)?\s*>/i', $html, $matches);

    return $matches[0] ?? '';
}

it('renders shared settings on widgets with custom wrappers', function (string $widgetClass, array $extraData): void {
    $widget = $widgetClass::make([
        ...$widgetClass::getDefaultData(),
        ...$extraData,
        'id' => 'shared-widget-id',
        'class' => 'shared-widget-class',
        'hide_on' => ['sm'],
        'padding' => ['unit' => 'rem', 'top' => 1, 'right' => 2, 'bottom' => 3, 'left' => 4],
        'animation' => 'fade-in',
    ]);

    $tag = layupOpeningTag($widget->render()->toHtml());

    expect($tag)
        ->toContain('id="shared-widget-id"')
        ->toContain('shared-widget-class')
        ->toContain('hidden md:block')
        ->toContain('padding-top: 1rem;')
        ->toContain('x-intersect.once');
})->with([
    'anchor' => [AnchorWidget::class, ['anchor_id' => 'content-anchor']],
    'back to top' => [BackToTopWidget::class, []],
    'cookie consent' => [CookieConsentWidget::class, []],
    'map' => [MapWidget::class, []],
]);

it('renders one Alpine scope with entrance animation', function (string $widgetClass, array $extraData): void {
    $widget = $widgetClass::make([
        ...$widgetClass::getDefaultData(),
        ...$extraData,
        'animation' => 'fade-in',
    ]);

    $tag = layupOpeningTag($widget->render()->toHtml());

    expect($tag)
        ->toContain('x-intersect.once')
        ->and(substr_count($tag, 'x-data'))
        ->toBe(1);
})->with([
    'accordion' => [AccordionWidget::class, []],
    'alert' => [AlertWidget::class, []],
    'anchor' => [AnchorWidget::class, ['anchor_id' => 'test-anchor']],
    'back to top' => [BackToTopWidget::class, []],
    'bar counter' => [BarCounterWidget::class, []],
    'before and after' => [BeforeAfterWidget::class, [
        'before_image' => 'before.jpg',
        'after_image' => 'after.jpg',
    ]],
    'button' => [ButtonWidget::class, []],
    'contact form' => [ContactFormWidget::class, []],
    'content toggle' => [ContentToggleWidget::class, []],
    'cookie consent' => [CookieConsentWidget::class, []],
    'countdown' => [CountdownWidget::class, []],
    'gallery' => [GalleryWidget::class, []],
    'modal' => [ModalWidget::class, []],
    'newsletter' => [NewsletterWidget::class, []],
    'notification bar' => [NotificationBarWidget::class, []],
    'pricing toggle' => [PricingToggleWidget::class, []],
    'progress circle' => [ProgressCircleWidget::class, []],
    'quote carousel' => [QuoteCarouselWidget::class, []],
    'share buttons' => [ShareButtonsWidget::class, []],
    'slider' => [SliderWidget::class, []],
    'table of contents' => [TableOfContentsWidget::class, []],
    'tabs' => [TabsWidget::class, []],
    'testimonial carousel' => [TestimonialCarouselWidget::class, []],
    'testimonial slider' => [TestimonialSliderWidget::class, [
        'testimonials' => [['quote' => 'Test', 'name' => 'Person']],
    ]],
    'toggle' => [ToggleWidget::class, []],
    'typewriter' => [TypewriterWidget::class, []],
    'video playlist' => [VideoPlaylistWidget::class, []],
]);
