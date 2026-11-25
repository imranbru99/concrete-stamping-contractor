@extends('layouts.app')

@section('title', 'Ashlar Ridge Stamped Concrete')
@section('meta_description', 'Premium stamped concrete patios, driveways, pool decks, and overlays across Phoenix and the East Valley by Ashlar Ridge.')

@section('content')
<section class="hero">
    <div class="wrap hero-inner">
        <h1>Ashlar Ridge<br>Stamped Concrete</h1>
        <p>Desert-ready decorative concrete — ashlar slate, cobble, and wood-plank patterns with color hardeners and UV-stable sealers built for Arizona sun.</p>
        <div class="hero-ctas">
            <a class="btn btn-primary" href="{{ route('contact') }}">Book a site visit</a>
            <a class="btn btn-ghost" href="{{ route('services') }}">Browse patterns & services</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2>Decorative concrete systems</h2>
        <p class="lede">Purpose-built stamping for patios, driveways, pool decks, and vertical accents — not a flat gray pour with a sticker stamp.</p>
        <div class="grid-3">
            @foreach([
                ['stamped-patios','Stamped Patios','Ashlar, slate & cobble living spaces for outdoor rooms.','https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1200&q=80'],
                ['driveways-walkways','Driveways & Walkways','Texture that hides tire marks and desert dust.','https://images.unsplash.com/photo-1600047509807-ba8f99d2cd00?auto=format&fit=crop&w=1200&q=80'],
                ['pool-decks','Pool Decks','Cooler-touch sealers and slip-conscious textures.','https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$blurb,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad">
                        <h3>{{ $title }}</h3>
                        <p>{{ $blurb }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Why Ashlar Ridge</h2>
        <p class="lede">Phoenix crews that treat subgrade, control joints, and sealer chemistry as non-negotiable — because stamped concrete fails from the base up.</p>
        <div class="grid-3">
            <div class="panel">
                <h3>Subgrade-first installs</h3>
                <p>Compacted base, proper thickness, and joint layout before any stamp hits the slab.</p>
            </div>
            <div class="panel">
                <h3>Pattern-matched systems</h3>
                <p>Ashlar slate for modern homes, cobble for courtyards, wood plank for covered patios — color hardener + release + sealer as a set.</p>
            </div>
            <div class="panel">
                <h3>ERP-run jobs</h3>
                <p>Quotes, pour days, seal schedules, and invoices live in one operations stack — fewer dropped balls in monsoon season.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Ops dashboard preview</h2>
        <p class="lede">A glimpse of the Ashlar Ridge ERP used to schedule pour crews, track stamp mats & hardeners, and close invoices.</p>
        <div class="dash-preview">
            <div>
                <div class="grid-3" style="grid-template-columns:repeat(3,1fr)">
                    <div class="metric"><strong>19</strong> Active jobs</div>
                    <div class="metric"><strong>$142k</strong> Pipeline</div>
                    <div class="metric"><strong>14</strong> Site visits</div>
                </div>
                <div class="bars">
                    <span style="height:40%"></span><span style="height:65%"></span><span style="height:50%"></span>
                    <span style="height:80%"></span><span style="height:55%"></span><span style="height:90%"></span>
                    <span style="height:70%"></span>
                </div>
            </div>
            <div>
                <h3 style="font-family:var(--font-display);margin-top:0">Live modules</h3>
                <ul style="line-height:1.9;padding-left:1.1rem;color:rgba(255,255,255,.86)">
                    <li>Jobs & pour scheduling</li>
                    <li>Stamp / hardener inventory</li>
                    <li>Invoices & payments</li>
                    <li>AI content + troubleshooting</li>
                </ul>
                <a class="btn btn-primary" style="margin-top:1rem" href="/admin">Open admin ERP</a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>From the yard</h2>
        <p class="lede">Guides and field notes for homeowners planning decorative concrete.</p>
        <div class="grid-3">
            @forelse($posts as $post)
                <a class="panel" href="{{ route('blog.show', $post->slug) }}">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->excerpt }}</p>
                </a>
            @empty
                <div class="panel"><p>Blog posts will appear after seeding.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
