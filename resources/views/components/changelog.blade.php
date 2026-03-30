@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); @endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="space-y-6 {{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @foreach(($data['releases'] ?? []) as $release)
        @php
            $releaseType = $release['type'] ?? '';
            $isMajor = $releaseType === 'major';
            $isPatch = $releaseType === 'patch';
            $isMinor = !$isMajor && !$isPatch;
            $borderStyle = $isMajor
                ? 'border-color: var(--layup-primary);'
                : ($isMinor ? 'border-color: var(--layup-success);' : '');
            $badgeStyle = $isMajor
                ? 'background-color: color-mix(in oklab, var(--layup-primary) 15%, transparent); color: var(--layup-primary);'
                : ($isMinor ? 'background-color: color-mix(in oklab, var(--layup-success) 15%, transparent); color: var(--layup-on-surface);' : '');
        @endphp
        <div class="border-l-4 pl-4 @if($isPatch) border-gray-300 dark:border-gray-600 @endif" @if($isMajor || $isMinor) style="{{ $borderStyle }}" @endif>
            <div class="flex items-center gap-3 mb-2">
                <span class="font-mono font-bold text-lg">v{{ $release['version'] ?? '' }}</span>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $release['date'] ?? '' }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full @if($isPatch) bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 @endif" @if($isMajor || $isMinor) style="{{ $badgeStyle }}" @endif>{{ ucfirst($release['type'] ?? 'minor') }}</span>
            </div>
            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                @foreach(array_filter(explode("\n", $release['changes'] ?? '')) as $change)
                    <li>• {{ trim($change) }}</li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
