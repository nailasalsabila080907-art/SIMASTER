@props(['href', 'label' => null])

<a href="{{ $href }}" title="{{ $label ?? 'Kembali' }}" aria-label="{{ $label ?? 'Kembali' }}"
   {{ $attributes->merge(['class' => 'btn-icon-ghost flex-shrink-0 text-decoration-none' . ($label ? ' px-3 gap-2' : '')]) }}
   @if($label) style="width:auto" @endif>
    <i class="bi bi-arrow-left"></i>
    @if($label)<span style="font-size:.85rem">{{ $label }}</span>@endif
</a>