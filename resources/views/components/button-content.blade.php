@if($loading)
    <span class="flex items-center">
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 button-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ $loadingText }}
    </span>
@else
    <span class="flex items-center">
        @if($icon)
            <i class="fas fa-{{ $icon }} @if($text) mr-2 @endif"></i>
        @endif
        
        {{ $text }}
        
        @if($rightIcon)
            <i class="fas fa-{{ $rightIcon }} @if($text) ml-2 @endif"></i>
        @endif
    </span>
@endif