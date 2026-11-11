<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['href', 'label' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['href', 'label' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="<?php echo e($href); ?>" title="<?php echo e($label ?? 'Kembali'); ?>" aria-label="<?php echo e($label ?? 'Kembali'); ?>"
   <?php echo e($attributes->merge(['class' => 'btn-icon-ghost flex-shrink-0 text-decoration-none' . ($label ? ' px-3 gap-2' : '')])); ?>

   <?php if($label): ?> style="width:auto" <?php endif; ?>>
    <i class="bi bi-arrow-left"></i>
    <?php if($label): ?><span style="font-size:.85rem"><?php echo e($label); ?></span><?php endif; ?>
</a><?php /**PATH C:\xampp\htdocs\simaster\resources\views/components/tombol-kembali.blade.php ENDPATH**/ ?>