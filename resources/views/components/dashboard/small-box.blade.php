@props([
    'title',
    'value',
    'icon',
    'color' => 'info',
])

<div class="col-lg-3 col-6">
    <div class="small-box bg-{{ $color }}">
        <div class="inner">
            <h3>{{ $value }}</h3>
            <p>{{ $title }}</p>
        </div>

        <div class="icon">
            <i class="{{ $icon }}"></i>
        </div>
    </div>
</div>