@props([
    'name',
    'id' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select an option...',
    'required' => false,
    'class' => '',
    'onchange' => null
])

@php
    $selectId = $id ?? $name . '_' . Str::random(6);
@endphp

<select 
    name="{{ $name }}" 
    id="{{ $selectId }}" 
    @if($required) required @endif 
    @if($onchange) onchange="{{ $onchange }}" @endif
    class="custom-select-element {{ $class }}"
    {{ $attributes }}
>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach($options as $val => $label)
        <option value="{{ $val }}" {{ (string)$selected === (string)$val ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>
