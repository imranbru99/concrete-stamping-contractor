@extends('layouts.app')
@section('title', 'Stamped Concrete Services')
@section('content')
<section class="section">
    <div class="wrap">
        <h2>Services</h2>
        <p class="lede">Ashlar Ridge installs stamped patios, driveways, pool decks, overlays, and vertical accents with desert-ready color and sealer systems.</p>
        <div class="grid-3">
            @foreach([
                ['stamped-patios','Stamped Patios','https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1200&q=80'],
                ['driveways-walkways','Driveways & Walkways','https://images.unsplash.com/photo-1600047509807-ba8f99d2cd00?auto=format&fit=crop&w=1200&q=80'],
                ['pool-decks','Pool Decks','https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80'],
                ['overlays-resurfacing','Overlays & Resurfacing','https://images.unsplash.com/photo-1600573472592-401b489a3cdc?auto=format&fit=crop&w=1200&q=80'],
                ['vertical-accents','Vertical Accents','https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1200&q=80'],
                ['sealing-restoration','Sealing & Restoration','https://images.unsplash.com/photo-1558904541-efa843a96f01?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad"><h3>{{ $title }}</h3></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
