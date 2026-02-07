@props(['type' => 'domain'])

@php
    $serviceType = strtolower($type);
    
    // Map service type to badge classes
    $badgeClasses = [
        'vps' => 'badge-service-vps',
        'domain' => 'badge-service-domain',
        'hosting' => 'badge-service-hosting',
    ];
    
    // Map service type to display labels
    $labels = [
        'vps' => 'VPS',
        'domain' => 'Domain',
        'hosting' => 'Hosting',
    ];
    
    $badgeClass = $badgeClasses[$serviceType] ?? 'badge-service-domain';
    $label = $labels[$serviceType] ?? ucfirst($serviceType);
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium $badgeClass"]) }}>
    {{ $label }}
</span>
