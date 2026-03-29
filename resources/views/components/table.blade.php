@php $vis = \Crumbls\Layup\View\BaseView::visibilityClasses($data['hide_on'] ?? []); @endphp
<div @if(!empty($data['id']))id="{{ $data['id'] }}"@endif class="overflow-x-auto {{ $vis }} {{ $data['class'] ?? '' }}" style="{{ \Crumbls\Layup\View\BaseView::buildInlineStyles($data) }}" {!! \Crumbls\Layup\View\BaseView::animationAttributes($data) !!}>
    <table class="w-full text-sm text-left">
        @if(!empty($data['caption']))
            <caption class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $data['caption'] }}</caption>
        @endif
        @if(!empty($data['headers']))
            <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                <tr>
                    @foreach($data['headers'] as $header)
                        <th class="px-2 py-2 md:px-4 md:py-3 font-semibold">{{ is_array($header) ? ($header['text'] ?? '') : $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            @foreach(($data['rows'] ?? []) as $index => $row)
                <tr class="{{ !empty($data['striped']) && $index % 2 === 1 ? 'bg-gray-50 dark:bg-gray-800' : '' }} border-b border-gray-200 dark:border-gray-700">
                    @foreach(($row['cells'] ?? []) as $cell)
                        <td class="px-2 py-2 md:px-4 md:py-3">{{ is_array($cell) ? ($cell['text'] ?? '') : $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
