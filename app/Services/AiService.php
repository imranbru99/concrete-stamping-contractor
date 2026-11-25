<?php

namespace App\Services;

use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiService
{
    /** @var array<int, array{q: string, a: string}> */
    protected array $faq = [
        [
            'q' => 'how long cure dry time sealer walk',
            'a' => 'Ashlar Ridge stamped slabs typically accept light foot traffic in 24 hours. We usually seal after 3–7 days depending on weather, then allow another 24–48 hours before furniture. Vehicle traffic on driveways is typically 7 days after the pour (longer in cool weather).',
        ],
        [
            'q' => 'patio driveway cost price estimate square',
            'a' => 'Most residential stamped concrete in the Phoenix East Valley runs about $12–$22 per sq ft installed, depending on pattern complexity, color system, thickness, and access. Request a free on-site measure for an exact quote.',
        ],
        [
            'q' => 'pattern ashlar slate cobble wood brick stamp',
            'a' => 'Popular Ashlar Ridge patterns include ashlar slate, random stone, cobblestone, brick basketweave, and wood plank. We help match pattern scale to your home’s architecture and outdoor furniture layout.',
        ],
        [
            'q' => 'color hardener release integral sealer',
            'a' => 'We typically use color hardener for rich surface color, antique release for secondary highlights, and a UV-stable acrylic or solvent sealer. Integral color can be added for thicker slabs and chip repair matching.',
        ],
        [
            'q' => 'pool deck slip hot barefoot',
            'a' => 'Pool decks get finer stamp textures for wet traction and sealers chosen for barefoot comfort. We detail joints away from water lines and can add cooler-tone colors that absorb less solar heat.',
        ],
        [
            'q' => 'overlay resurface existing concrete',
            'a' => 'If your existing slab is structurally sound, a stampable overlay can refresh pattern and color without full demolition. We mechanically prep, bond, stamp, and seal. Cracked or heaved slabs may need partial tear-out first.',
        ],
        [
            'q' => 'schedule appointment quote contact phoenix scottsdale mesa',
            'a' => 'Call or text (480) 555-0194, use the contact form, or WhatsApp us. Site visits for Phoenix, Scottsdale, Mesa, and East Valley cities are usually available within 2–3 business days.',
        ],
        [
            'q' => 'crack peel fading sealer monsoon maintenance',
            'a' => 'Most “failures” are sealer wear, not stamp failure. Plan resealing every 2–4 years in Arizona sun. Control joints and proper base reduce structural cracks. After monsoon storms, rinse dust and check for standing water against the slab edge.',
        ],
    ];

    public function chat(string $message, ?string $sessionId = null): array
    {
        $reply = null;
        $source = 'demo';

        if ($key = config('services.openai.key')) {
            try {
                $reply = $this->callOpenAi($message, $key);
                $source = 'openai';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply && ($gemini = config('services.gemini.key'))) {
            try {
                $reply = $this->callGemini($message, $gemini);
                $source = 'gemini';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply) {
            $reply = $this->demoReply($message);
            $source = 'demo';
        }

        ChatLog::query()->create([
            'session_id' => $sessionId,
            'message' => $message,
            'reply' => $reply,
            'source' => $source,
        ]);

        return ['reply' => $reply, 'source' => $source];
    }

    public function generate(string $type, string $prompt): string
    {
        $message = match ($type) {
            'blog' => "Write a concise SEO blog post outline and intro for a stamped concrete contractor about: {$prompt}",
            'description' => "Write a compelling product/service description for stamped decorative concrete: {$prompt}",
            'automation' => "Suggest 5 practical AI automations for a stamped concrete SME ERP related to: {$prompt}",
            'troubleshoot' => "Troubleshoot this stamped concrete operations issue with clear steps: {$prompt}",
            default => $prompt,
        };

        $result = $this->chat($message);

        return $result['reply'];
    }

    protected function demoReply(string $message): string
    {
        $hay = Str::lower($message);
        $best = null;
        $bestScore = 0;

        foreach ($this->faq as $item) {
            $score = 0;
            foreach (explode(' ', $item['q']) as $word) {
                if ($word !== '' && str_contains($hay, $word)) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $item['a'];
            }
        }

        if ($best && $bestScore > 0) {
            return $best;
        }

        return 'Thanks for reaching Ashlar Ridge Stamped Concrete. We install stamped patios, driveways, pool decks, and overlays across Phoenix and the East Valley. Ask about patterns, color systems, sealing, pricing, or scheduling—or call (480) 555-0194 for a free site visit.';
    }

    protected function callOpenAi(string $message, string $key): string
    {
        $response = Http::withToken($key)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are the Ashlar Ridge Stamped Concrete assistant for a Phoenix, AZ decorative concrete contractor. Be concise, practical, and focused on stamped patios, driveways, pool decks, overlays, and sealing. Never invent illegal claims.',
                    ],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.4,
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'choices.0.message.content', 'Unable to generate a response.');
    }

    protected function callGemini(string $message, string $key): string
    {
        $model = config('services.gemini.model', 'gemini-1.5-flash');
        $response = Http::timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "You are Ashlar Ridge Stamped Concrete assistant (Phoenix decorative / stamped concrete). Answer briefly:\n{$message}"],
                        ],
                    ],
                ],
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'candidates.0.content.parts.0.text', 'Unable to generate a response.');
    }
}
