<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\KnowledgeArticle;
use App\Models\Post;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->take(3)->get();

        return view('pages.home', compact('posts'));
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function serviceShow(string $slug): View
    {
        $services = $this->serviceCatalog();
        abort_unless(isset($services[$slug]), 404);

        return view('services.show', [
            'slug' => $slug,
            'service' => $services[$slug],
            'services' => $services,
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create($data);

        return back()->with('success', 'Thanks! Ashlar Ridge will follow up within one business day.');
    }

    public function blog(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function blogShow(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('blog.show', compact('post'));
    }

    public function knowledge(): View
    {
        $articles = KnowledgeArticle::query()->where('is_published', true)->orderBy('category')->orderBy('title')->get()->groupBy('category');

        return view('kb.index', compact('articles'));
    }

    public function knowledgeShow(string $slug): View
    {
        $article = KnowledgeArticle::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('kb.show', compact('article'));
    }

    public function chat(Request $request, AiService $ai)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session_id' => ['nullable', 'string', 'max:80'],
        ]);

        $result = $ai->chat($data['message'], $data['session_id'] ?? null);

        return response()->json($result);
    }

    /** @return array<string, array<string, string>> */
    protected function serviceCatalog(): array
    {
        return [
            'stamped-patios' => [
                'title' => 'Stamped Patios',
                'summary' => 'Ashlar slate, random stone, and cobble patterns that turn yards into outdoor rooms.',
                'body' => 'Ashlar Ridge designs patio layouts around furniture zones and shade, then places color-hardened concrete stamped with interlocking patterns. Integral color options and antique release create depth that looks like natural stone without the uneven maintenance.',
                'image' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80',
            ],
            'driveways-walkways' => [
                'title' => 'Driveways & Walkways',
                'summary' => 'Durable stamped approaches that hide tire marks and desert dust better than plain gray.',
                'body' => 'We engineer thickness, rebar, and joint spacing for Phoenix heat cycles, then stamp with brick, cobble, or ashlar patterns. UV-stable sealers protect color while remaining workable for vehicle traffic after proper cure.',
                'image' => 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cd00?auto=format&fit=crop&w=1600&q=80',
            ],
            'pool-decks' => [
                'title' => 'Pool Decks',
                'summary' => 'Slip-conscious textures and cooler-touch sealers around water features.',
                'body' => 'Pool decks need grip when wet and color that survives chlorine splash and UV. We stamp with finer textures, detail control joints away from water lines, and specify sealers suited for barefoot traffic in summer heat.',
                'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80',
            ],
            'overlays-resurfacing' => [
                'title' => 'Overlays & Resurfacing',
                'summary' => 'Stampable overlays that refresh tired slabs without a full tear-out.',
                'body' => 'When an existing patio is sound but dated, we mechanically prep, apply a stampable overlay, and recreate pattern and color. Ideal for East Valley homes where demolition access or HOA disruption is limited.',
                'image' => 'https://images.unsplash.com/photo-1600573472592-401b489a3cdc?auto=format&fit=crop&w=1600&q=80',
            ],
            'vertical-accents' => [
                'title' => 'Vertical Accents',
                'summary' => 'Stamped retaining faces, seat walls, and outdoor kitchen surrounds.',
                'body' => 'Vertical stamping and form liners extend the patio language onto walls and planters. We match horizontal patterns so outdoor kitchens and seat walls feel intentional, not bolted-on.',
                'image' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1600&q=80',
            ],
            'sealing-restoration' => [
                'title' => 'Sealing & Restoration',
                'summary' => 'Strip, clean, and reseal aging stamped concrete to revive color and sheen.',
                'body' => 'Arizona sun cooks sealer. Our restoration crews strip failing film, deep-clean joints, repair chips, and reapply breathable or solvent acrylic systems matched to the original look — usually every 2–4 years depending on exposure.',
                'image' => 'https://images.unsplash.com/photo-1558904541-efa843a96f01?auto=format&fit=crop&w=1600&q=80',
            ],
        ];
    }
}
