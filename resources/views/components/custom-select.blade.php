@props([
    'name',
    'id' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select an option...',
    'icon' => null,
    'required' => false,
    'submitOnChange' => true,
    'class' => '',
])

@php
    $selectId = $id ?? 'custom_select_' . $name . '_' . Str::random(5);

    // Normalize options into associative array [value => label]
    $normalizedOptions = [];
    foreach ($options as $key => $val) {
        if (is_int($key)) {
            $normalizedOptions[$val] = $val;
        } else {
            $normalizedOptions[$key] = $val;
        }
    }

    $hasSelection = ($selected !== null && $selected !== '' && array_key_exists((string)$selected, $normalizedOptions));
    $currentLabel = $hasSelection ? $normalizedOptions[(string)$selected] : $placeholder;
@endphp

<div class="custom-select-wrapper relative inline-block text-left {{ $class }}" data-submit="{{ $submitOnChange ? 'true' : 'false' }}">
    <!-- Hidden input that holds the actual form value -->
    <input 
        type="hidden" 
        name="{{ $name }}" 
        id="{{ $selectId }}" 
        value="{{ $selected ?? '' }}"
        class="custom-select-hidden"
        @if($required) required @endif
    >

    <!-- Custom Select Trigger Button -->
    <button 
        type="button" 
        class="custom-select-trigger w-full flex items-center justify-between gap-2.5 px-3.5 py-2 text-xs font-semibold rounded-xl border transition-all duration-150 outline-none select-none {{ $hasSelection ? 'bg-indigo-50/70 border-indigo-300 text-indigo-700 shadow-xs' : 'bg-slate-50/90 hover:bg-slate-100/80 border-slate-200 hover:border-slate-300 text-slate-700 shadow-xs' }} focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
        aria-haspopup="listbox"
        aria-expanded="false"
    >
        <span class="flex items-center gap-2 truncate">
            @if($icon)
                <i class="{{ $icon }} text-xs {{ $hasSelection ? 'text-indigo-600' : 'text-slate-400' }}"></i>
            @endif
            <span class="custom-select-label truncate">{{ $currentLabel }}</span>
        </span>
        <i class="fa-solid fa-chevron-down custom-select-chevron text-[10px] text-slate-400 transition-transform duration-200 ml-1"></i>
    </button>

    <!-- Dropdown Menu Popover -->
    <div 
        class="custom-select-menu hidden absolute left-0 sm:right-auto sm:left-0 z-50 mt-1.5 min-w-[200px] w-full max-h-60 overflow-y-auto rounded-2xl bg-white/95 backdrop-blur-md border border-slate-200 shadow-xl shadow-slate-900/10 p-1.5 transition-all text-xs outline-none"
        role="listbox"
        style="scrollbar-width: thin;"
    >
        @if($placeholder)
            <div 
                class="custom-select-option px-3 py-2 rounded-xl flex items-center justify-between cursor-pointer transition select-none {{ !$hasSelection ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}"
                data-value=""
                data-label="{{ $placeholder }}"
                role="option"
                aria-selected="{{ !$hasSelection ? 'true' : 'false' }}"
            >
                <span class="flex items-center gap-2 truncate">
                    <span class="w-2 h-2 rounded-full {{ !$hasSelection ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                    <span class="truncate">{{ $placeholder }}</span>
                </span>
                <i class="fa-solid fa-check text-indigo-600 text-xs custom-select-check {{ !$hasSelection ? '' : 'hidden' }}"></i>
            </div>
        @endif

        @foreach($normalizedOptions as $val => $label)
            @php
                $isSelected = ($hasSelection && (string)$selected === (string)$val);
            @endphp
            <div 
                class="custom-select-option px-3 py-2 rounded-xl flex items-center justify-between cursor-pointer transition select-none {{ $isSelected ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}"
                data-value="{{ $val }}"
                data-label="{{ $label }}"
                role="option"
                aria-selected="{{ $isSelected ? 'true' : 'false' }}"
            >
                <span class="flex items-center gap-2 truncate">
                    <span class="w-2 h-2 rounded-full {{ $isSelected ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                    <span class="truncate">{{ $label }}</span>
                </span>
                <i class="fa-solid fa-check text-indigo-600 text-xs custom-select-check {{ $isSelected ? '' : 'hidden' }}"></i>
            </div>
        @endforeach
    </div>
</div>
