<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\KnowledgeArticle;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'manager', 'staff'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '(480) 555-0100',
            ]
        );
        $admin->syncRoles(['admin']);

        User::query()->updateOrCreate(
            ['email' => 'manager@ashlarridge.com'],
            [
                'name' => 'Sofia Mendoza',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '(480) 555-0101',
            ]
        )->syncRoles(['manager']);

        User::query()->updateOrCreate(
            ['email' => 'crew@ashlarridge.com'],
            [
                'name' => 'Tyler Quinn',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '(480) 555-0102',
            ]
        )->syncRoles(['staff']);

        $settings = [
            'company_name' => 'Ashlar Ridge Stamped Concrete',
            'company_phone' => '(480) 555-0194',
            'company_email' => 'hello@ashlarridge.com',
            'whatsapp' => '14805550194',
            'service_area' => 'Phoenix, Scottsdale, Mesa & East Valley',
            'tax_rate' => '8.10',
        ];
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $products = [
            ['name' => 'Ashlar Slate Stamp Mat Set', 'sku' => 'STM-ASH-6', 'category' => 'stamp', 'unit_cost' => 420, 'unit_price' => 680, 'stock_qty' => 4],
            ['name' => 'Cobblestone Stamp Mat Set', 'sku' => 'STM-COB-6', 'category' => 'stamp', 'unit_cost' => 390, 'unit_price' => 640, 'stock_qty' => 3],
            ['name' => 'Color Hardener — Desert Sand (50 lb)', 'sku' => 'CH-DES-50', 'category' => 'hardener', 'unit_cost' => 28, 'unit_price' => 48, 'stock_qty' => 86],
            ['name' => 'Color Hardener — Canyon Terra (50 lb)', 'sku' => 'CH-CAN-50', 'category' => 'hardener', 'unit_cost' => 28, 'unit_price' => 48, 'stock_qty' => 64],
            ['name' => 'Antique Release — Charcoal', 'sku' => 'REL-CHR-30', 'category' => 'release', 'unit_cost' => 22, 'unit_price' => 39, 'stock_qty' => 52],
            ['name' => 'UV Acrylic Sealer (5 gal)', 'sku' => 'SEAL-UV-5', 'category' => 'sealer', 'unit_cost' => 95, 'unit_price' => 165, 'stock_qty' => 24],
            ['name' => 'Stampable Overlay Kit (50 lb)', 'sku' => 'OVL-STP-50', 'category' => 'overlay', 'unit_cost' => 34, 'unit_price' => 58, 'stock_qty' => 40],
            ['name' => 'Control Joint Soft-Cut Blade Pack', 'sku' => 'TL-CJB-4', 'category' => 'tool', 'unit_cost' => 55, 'unit_price' => 95, 'stock_qty' => 15],
        ];
        foreach ($products as $product) {
            Product::query()->updateOrCreate(['sku' => $product['sku']], $product + ['unit' => 'kit', 'is_active' => true]);
        }

        $customers = collect([
            ['name' => 'Hannah Cole', 'email' => 'hannah@example.com', 'phone' => '(480) 555-2201', 'company' => null, 'city' => 'Scottsdale', 'state' => 'AZ', 'zip' => '85251', 'address' => '742 Palm Grove Ct'],
            ['name' => 'Raj Patel', 'email' => 'raj@sonorancourtyards.com', 'phone' => '(480) 555-2210', 'company' => 'Sonoran Courtyards', 'city' => 'Mesa', 'state' => 'AZ', 'zip' => '85201', 'address' => '1180 Main St'],
            ['name' => 'Lauren Briggs', 'email' => 'ops@ridgelinepools.com', 'phone' => '(602) 555-2299', 'company' => 'Ridgeline Pools', 'city' => 'Phoenix', 'state' => 'AZ', 'zip' => '85018', 'address' => '4100 Camelback Rd'],
            ['name' => 'Diego Alvarez', 'email' => 'diego.alvarez@example.com', 'phone' => '(480) 555-2188', 'company' => null, 'city' => 'Gilbert', 'state' => 'AZ', 'zip' => '85234', 'address' => '55 Ocotillo Ridge'],
            ['name' => 'Megan Shaw', 'email' => 'megan@eastvalleyhoa.org', 'phone' => '(480) 555-2177', 'company' => 'East Valley HOA Services', 'city' => 'Chandler', 'state' => 'AZ', 'zip' => '85224', 'address' => '2200 Alma School Rd'],
        ])->map(fn ($c) => Customer::query()->updateOrCreate(['email' => $c['email']], $c));

        $projects = [
            ['customer' => 0, 'title' => 'Ashlar slate backyard patio', 'service_type' => 'patio', 'status' => 'scheduled', 'sq_ft' => 680, 'quoted_amount' => 10880],
            ['customer' => 1, 'title' => 'Courtyard cobble walkways', 'service_type' => 'walkway', 'status' => 'in_progress', 'sq_ft' => 920, 'quoted_amount' => 14720],
            ['customer' => 2, 'title' => 'Pool deck stamp + cooler sealer', 'service_type' => 'pool_deck', 'status' => 'quoted', 'sq_ft' => 1400, 'quoted_amount' => 25200],
            ['customer' => 3, 'title' => 'Driveway brick basketweave', 'service_type' => 'driveway', 'status' => 'completed', 'sq_ft' => 1100, 'quoted_amount' => 17600, 'final_amount' => 18200],
            ['customer' => 4, 'title' => 'Clubhouse overlay resurfacing', 'service_type' => 'overlay', 'status' => 'invoiced', 'sq_ft' => 2400, 'quoted_amount' => 31200, 'final_amount' => 30800],
        ];

        $projectModels = [];
        foreach ($projects as $i => $p) {
            $customer = $customers[$p['customer']];
            $projectModels[] = Project::query()->updateOrCreate(
                ['job_number' => 'JOB-26'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $customer->id,
                    'title' => $p['title'],
                    'service_type' => $p['service_type'],
                    'status' => $p['status'],
                    'sq_ft' => $p['sq_ft'],
                    'quoted_amount' => $p['quoted_amount'],
                    'final_amount' => $p['final_amount'] ?? null,
                    'start_date' => now()->subDays(10 - $i)->toDateString(),
                    'site_address' => trim(($customer->address ?? '').', '.$customer->city.', '.$customer->state),
                    'notes' => 'Seeded demo job for Ashlar Ridge ERP.',
                ]
            );
        }

        foreach ([0, 1, 3, 4] as $idx) {
            $project = $projectModels[$idx];
            $invoice = Invoice::query()->updateOrCreate(
                ['invoice_number' => 'INV-26'.str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $project->customer_id,
                    'project_id' => $project->id,
                    'issue_date' => now()->subDays(8 - $idx)->toDateString(),
                    'due_date' => now()->addDays(14)->toDateString(),
                    'subtotal' => $project->final_amount ?? $project->quoted_amount,
                    'tax' => round(($project->final_amount ?? $project->quoted_amount) * 0.081, 2),
                    'total' => round(($project->final_amount ?? $project->quoted_amount) * 1.081, 2),
                    'amount_paid' => in_array($project->status, ['completed', 'invoiced'], true) ? round(($project->final_amount ?? $project->quoted_amount) * 1.081, 2) : 0,
                    'status' => in_array($project->status, ['completed', 'invoiced'], true) ? 'paid' : 'sent',
                ]
            );

            if ($invoice->status === 'paid') {
                Payment::query()->updateOrCreate(
                    ['reference' => 'PMT-'.$invoice->invoice_number],
                    [
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total,
                        'paid_at' => now()->subDays(3)->toDateString(),
                        'method' => 'card',
                        'notes' => 'Demo payment',
                    ]
                );
            }
        }

        Appointment::query()->updateOrCreate(
            ['title' => 'Patio measure — Cole'],
            [
                'customer_id' => $customers[0]->id,
                'project_id' => $projectModels[0]->id,
                'type' => 'measure',
                'starts_at' => now()->addDays(2)->setTime(9, 0),
                'ends_at' => now()->addDays(2)->setTime(10, 0),
                'status' => 'scheduled',
                'location' => $customers[0]->address,
            ]
        );
        Appointment::query()->updateOrCreate(
            ['title' => 'Driveway pour day'],
            [
                'customer_id' => $customers[3]->id,
                'project_id' => $projectModels[3]->id,
                'type' => 'install',
                'starts_at' => now()->addDays(9)->setTime(6, 30),
                'ends_at' => now()->addDays(9)->setTime(15, 0),
                'status' => 'confirmed',
                'location' => $customers[3]->address,
            ]
        );

        $posts = [
            [
                'title' => 'Why Arizona Stamped Concrete Needs UV-Stable Sealers',
                'excerpt' => 'Desert sun cooks cheap acrylics — here is how Ashlar Ridge keeps color alive through monsoon season.',
                'cover' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1400&q=80',
                'body' => "Phoenix UV intensity will chalk and peel low-grade sealers within a year. That “faded patio” look is usually sealer failure, not the stamp pattern dying.\n\nAshlar Ridge specifies UV-stable acrylic or solvent systems matched to traffic and exposure, then schedules reseals every 2–4 years. If your surface looks chalky or sticky after monsoon rains, stop pressure-washing at high PSI and book a restoration assessment before you strip the wrong film.",
            ],
            [
                'title' => 'Ashlar Slate vs Cobble: Choosing a Pattern Scale',
                'excerpt' => 'Large modern homes love ashlar. Courtyards love cobble. Driveways need patterns that hide tire paths.',
                'cover' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1400&q=80',
                'body' => "Ashlar slate reads clean and contemporary under desert landscaping. Cobble and brick patterns add old-world texture for courtyards and walkways. Wood plank stamps shine on covered patios where UV load is lower.\n\nMatch pattern scale to the longest sightline from the kitchen or living room — tiny stamps on a big patio look busy; oversized ashlar on a narrow walkway can feel unfinished.",
            ],
            [
                'title' => 'Base Prep Before Any Stamp Hits the Slab',
                'excerpt' => 'A compacted base and honest joint plan prevent the cracks everyone blames on “Arizona soil.”',
                'cover' => 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cd00?auto=format&fit=crop&w=1400&q=80',
                'body' => "Decorative concrete still needs structural basics: compacted aggregate, correct thickness, reinforcement, and control joints that land where you want cracks to hide.\n\nAshlar Ridge refuses to stamp soft concrete or skip release. Ask any contractor for their base and joint protocol before you sign — pattern beauty cannot fix a moving slab.",
            ],
        ];
        foreach ($posts as $post) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'cover_image' => $post['cover'],
                    'is_published' => true,
                    'published_at' => now()->subDays(random_int(2, 20)),
                ]
            );
        }

        $articles = [
            ['category' => 'issues', 'title' => 'White powdery bloom on sealed patio', 'body' => "1. Confirm it is sealer blush or efflorescence (wipe test with vinegar vs water).\n2. Avoid sealing in high humidity or late-day monsoon moisture.\n3. If acrylic blushed, strip affected areas and reseal in cooler, dry windows.\n4. Improve drainage so water does not sit against the slab edge."],
            ['category' => 'issues', 'title' => 'Faded color in full-sun driveway', 'body' => "UV ate the sealer first. Strip remaining film, deep-clean, repair chips with matching hardener paste if needed, then reseal with a UV-stable system. Schedule maintenance every 2–3 years for west-facing driveways."],
            ['category' => 'guides', 'title' => 'Patio pour-day timeline', 'body' => "Day −1: Forms set, base compacted, rebar placed.\nMorning: Place, screed, bull float.\nMidday: Color hardener broadcast & work-in.\nRelease + stamp while plastic.\nNext day: Wash release residue carefully.\nDay 3–7: Soft-cut joints if needed, then seal.\n+24–48h after seal: Furniture. +7 days: Vehicles on driveways."],
            ['category' => 'guides', 'title' => 'How to clean stamped concrete', 'body' => "Rinse desert dust with a garden hose. Use a mild concrete cleaner — avoid harsh acids on sealed surfaces unless directed. Soft wash only; high-PSI blasting can scar sealers and open aggregate. Reseal when water no longer beads."],
            ['category' => 'admin', 'title' => 'Admin ERP quick start', 'body' => "Login at /admin with admin@example.com / password.\n1. Add customers in CRM.\n2. Create a Job/Project with sq ft and quote.\n3. Schedule appointments for measure/pour/seal.\n4. Convert completed jobs to invoices and record payments.\n5. Use AI Studio for blog drafts and troubleshooting suggestions.\n6. Update company Settings for phone, WhatsApp, and tax rate."],
            ['category' => 'admin', 'title' => 'Roles: Admin, Manager, Staff', 'body' => "Admin: full ERP + settings + users.\nManager: jobs, inventory, invoices, scheduling.\nStaff: field-facing records and appointment updates.\nAssign roles on the Users resource."],
            ['category' => 'troubleshoot', 'title' => 'Job over material estimate (hardener/sealer)', 'body' => "Check actual sq ft vs quote. Confirm extra hardener coats on porous finish or wind loss on release. Log additional bags/kits on the job notes, create a change-order invoice line, and update inventory stock quantities."],
            ['category' => 'troubleshoot', 'title' => 'Chatbot giving demo answers only', 'body' => "Demo mode is expected without API keys. Add OPENAI_API_KEY or GEMINI_API_KEY to .env, then clear config cache. The public chatbot and AI Studio will prefer live models when keys are present."],
        ];
        foreach ($articles as $article) {
            KnowledgeArticle::query()->updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'category' => $article['category'],
                    'body' => $article['body'],
                    'is_published' => true,
                ]
            );
        }
    }
}
