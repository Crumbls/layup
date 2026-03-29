@php
    $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []);
    $cols = $data['columns'] ?? 3;
    $modelClass = $data['model'] ?? null;
    $posts = collect();
    if ($modelClass && class_exists($modelClass)) {
        $query = $modelClass::query();
        $query = match($data['order'] ?? 'latest') {
            'oldest' => $query->oldest(),
            'title' => $query->orderBy('title'),
            default => $query->latest(),
        };
        $posts = $query->limit($data['limit'] ?? 6)->get();
    }
@endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif
     class="{{ $vis }} {{ $data['class'] ?? '' }}"
     style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}"
     {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}
>
    @if($posts->isEmpty())
        <p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ $data['empty_message'] ?? __('layup::frontend.post_list.no_posts') }}</p>
    @else
        @php $plId = 'layup-pl-' . md5(uniqid('pl', true)); @endphp
        <style>
            #{{ $plId }} { display:grid; grid-template-columns:1fr; gap:1.5rem; }
            @media(min-width:640px) { #{{ $plId }} { grid-template-columns:repeat(2,1fr); } }
            @media(min-width:1024px) { #{{ $plId }} { grid-template-columns:repeat({{ $cols }},1fr); } }
        </style>
        <div id="{{ $plId }}">
            @foreach($posts as $post)
                <article class="border dark:border-gray-700 rounded-lg overflow-hidden">
                    @if(method_exists($post, 'getFeaturedImageUrl') && $post->getFeaturedImageUrl())
                        <img src="{{ $post->getFeaturedImageUrl() }}" alt="{{ $post->title }}" class="w-full h-48 object-cover" />
                    @endif
                    <div class="p-4">
                        @if(!empty($data['show_date']) && $post->published_at)
                            <time class="text-xs text-gray-400 dark:text-gray-500">{{ $post->published_at->format('M j, Y') }}</time>
                        @endif
                        <h3 class="font-semibold mt-1">
                            @if($post->slug)<a href="{{ url($post->slug) }}" class="layup-hover-text-primary">{{ $post->title }}</a>@else{{ $post->title }}@endif
                        </h3>
                        @if(!empty($data['show_excerpt']) && ($post->excerpt ?? null))
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">{{ \Illuminate\Support\Str::limit($post->excerpt, 120) }}</p>
                        @endif
                        @if(!empty($data['read_more_text']) && $post->slug)
                            <a href="{{ url($post->slug) }}" class="text-sm layup-text-primary hover:underline mt-2 inline-block">{{ $data['read_more_text'] }}</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
