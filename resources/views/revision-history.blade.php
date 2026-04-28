<div class="lyp-rev-list">
    @if($revisions->isEmpty())
        <div class="lyp-rev-empty">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lyp-rev-empty-icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <p>{{ __('layup::builder.no_revisions') }}</p>
        </div>
    @else
        <ul class="lyp-rev-items">
            @foreach($revisions as $revision)
                <li class="lyp-rev-item">
                    <div class="lyp-rev-meta">
                        <div class="lyp-rev-title">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lyp-rev-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span class="lyp-rev-date">{{ $revision->created_at->format('M j, Y g:i A') }}</span>
                            <span class="lyp-rev-ago">({{ $revision->created_at->diffForHumans() }})</span>
                        </div>

                        @if($revision->note)
                            <p class="lyp-rev-note">{{ $revision->note }}</p>
                        @endif

                        @if($revision->author)
                            <p class="lyp-rev-author">{{ __('layup::builder.by', ['name' => $revision->author]) }}</p>
                        @endif

                        <div class="lyp-rev-rows">
                            {{ __('layup::builder.rows_count', ['count' => count($revision->content['rows'] ?? [])]) }}
                        </div>
                    </div>

                    <button
                        type="button"
                        wire:click="restoreRevision({{ $revision->id }})"
                        wire:loading.attr="disabled"
                        wire:target="restoreRevision({{ $revision->id }})"
                        class="fi-btn fi-btn-color-primary fi-color-primary fi-size-sm fi-btn-size-sm lyp-rev-restore"
                        onclick="if(!confirm('{{ __('layup::builder.restore_confirm') }}')) { event.stopPropagation(); return false; }"
                    >
                        <span wire:loading.remove wire:target="restoreRevision({{ $revision->id }})">
                            {{ __('layup::builder.restore') }}
                        </span>
                        <span wire:loading wire:target="restoreRevision({{ $revision->id }})">
                            {{ __('layup::builder.restoring') }}
                        </span>
                    </button>
                </li>
            @endforeach
        </ul>

        @if($revisions->count() >= 50)
            <p class="lyp-rev-footnote">{{ __('layup::builder.showing_last_revisions') }}</p>
        @endif
    @endif
</div>
