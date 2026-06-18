<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Post;
use App\Models\Skill;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    private array $skillNames = [
        'Web Development', 'Digital Marketing', 'Sales', 'Bookkeeping', 'Logistics',
        'Graphic Design', 'Photography', 'Copywriting', 'Customer Service',
        'Project Management', 'Data Analysis', 'Public Speaking', 'Negotiation',
        'SEO', 'Social Media Management',
    ];

    private array $locations = [
        'Austin, TX', 'Brooklyn, NY', 'Portland, OR', 'Denver, CO', 'Atlanta, GA',
        'Chicago, IL', 'Phoenix, AZ', 'Nashville, TN', 'Seattle, WA', 'Miami, FL',
        'Raleigh, NC', 'Minneapolis, MN',
    ];

    public function run(): void
    {
        $skills = $this->seedSkills();

        $sellers = $this->seedSellers($skills);
        $tutors = $this->seedTutors($skills);
        $applicants = $this->seedApplicants();
        $members = $this->seedMembers($skills);

        $everyone = $sellers->pluck('user')
            ->merge($tutors->pluck('user'))
            ->merge($applicants)
            ->merge($members);

        $this->seedConnections($everyone);
        $this->seedFeed($everyone, $sellers, $tutors);
        $this->seedOrders($sellers, $everyone);
        $this->seedEnrollments($tutors, $everyone);
    }

    private function seedSkills(): Collection
    {
        return collect($this->skillNames)->map(
            fn (string $name) => Skill::firstOrCreate(['name' => $name])
        );
    }

    private function storeDefinitions(): array
    {
        return [
            ['name' => 'Urban Leather Co.', 'industry' => 'Retail', 'description' => 'Handmade leather bags, wallets, and belts crafted in small batches.', 'products' => [
                ['Classic Tote Bag', 8900, 24], ['Slim Bifold Wallet', 3200, 60], ['Full-Grain Belt', 4500, 40], ['Weekend Duffel', 12000, 15],
            ]],
            ['name' => 'GreenLeaf Organics', 'industry' => 'Food & Beverage', 'description' => 'Locally sourced organic pantry staples and snacks.', 'products' => [
                ['Organic Honey 500g', 1200, 80], ['Cold-Pressed Olive Oil 1L', 1800, 50], ['Trail Mix 250g', 750, 100], ['Herbal Tea Sampler', 1500, 70],
            ]],
            ['name' => 'Bright Ideas Print Shop', 'industry' => 'Professional Services', 'description' => 'Custom printing, business cards, and branded merchandise for small businesses.', 'products' => [
                ['Business Card Pack (500)', 4000, 30], ['Custom Vinyl Banner', 6500, 20], ['Branded Tote Bags (50)', 22000, 10], ['Sticker Sheet Bundle', 1800, 90],
            ]],
            ['name' => 'Coastal Coffee Roasters', 'industry' => 'Food & Beverage', 'description' => 'Small-batch coffee roasted weekly and shipped fresh.', 'products' => [
                ['House Blend 1kg', 2200, 60], ['Single Origin Ethiopia 250g', 1600, 45], ['Cold Brew Concentrate', 1900, 35], ['Espresso Beans 1kg', 2400, 50],
            ]],
            ['name' => 'Nimbus Tech Repairs', 'industry' => 'Technology', 'description' => 'Phone and laptop repair kits and refurbished accessories.', 'products' => [
                ['Phone Screen Repair Kit', 2500, 40], ['USB-C Fast Charger', 1900, 100], ['Laptop Battery Replacement', 5200, 25], ['Refurbished Wireless Mouse', 1400, 60],
            ]],
            ['name' => 'Bloom & Co. Florists', 'industry' => 'Retail', 'description' => 'Fresh seasonal arrangements and gifting bouquets.', 'products' => [
                ['Seasonal Bouquet', 3500, 30], ['Succulent Gift Set', 2800, 45], ['Dried Flower Arrangement', 4200, 20], ['Single Stem Roses (12)', 3900, 25],
            ]],
            ['name' => 'Forge Metal Works', 'industry' => 'Manufacturing', 'description' => 'Hand-forged tools, hooks, and decorative ironwork.', 'products' => [
                ['Hand-Forged Hook Set', 2600, 35], ['Fire Pit Poker', 3100, 20], ['Decorative Wall Hooks', 1900, 40], ['Custom Bottle Opener', 1200, 80],
            ]],
            ['name' => 'Sunrise Bakery Supplies', 'industry' => 'Food & Beverage', 'description' => 'Ingredients and tools for home and small-batch bakers.', 'products' => [
                ['Pastry Flour 5kg', 1800, 50], ['Vanilla Bean Extract 250ml', 1600, 60], ['Silicone Baking Mat Set', 2200, 45], ['Stand Mixer Attachment', 4800, 18],
            ]],
            ['name' => 'Pixel & Thread Apparel', 'industry' => 'Arts & Crafts', 'description' => 'Screen-printed apparel and accessories for small brands.', 'products' => [
                ['Custom Logo T-Shirt', 2400, 70], ['Embroidered Cap', 2100, 55], ['Pullover Hoodie', 4600, 35], ['Tote Bag Bundle (3)', 3300, 40],
            ]],
            ['name' => 'Northwind Outdoor Gear', 'industry' => 'Retail', 'description' => 'Camping and hiking gear for weekend adventurers.', 'products' => [
                ['2-Person Tent', 12500, 15], ['Insulated Water Bottle', 1900, 80], ['Camp Cookware Set', 5400, 25], ['Trail Backpack 30L', 7600, 20],
            ]],
        ];
    }

    private function seedSellers(Collection $skills): Collection
    {
        return collect($this->storeDefinitions())->map(function (array $def, int $i) use ($skills) {
            $owner = User::factory()->create([
                'is_seller' => true,
                'seller_status' => 'approved',
            ]);

            $store = $owner->store()->create([
                'name' => $def['name'],
                'slug' => Str::slug($def['name']).'-'.Str::random(6),
                'description' => $def['description'],
            ]);
            $store->status = 'approved';
            $store->save();

            $products = collect($def['products'])->map(
                fn (array $p) => $store->products()->create([
                    'name' => $p[0],
                    'slug' => Str::slug($p[0]).'-'.Str::random(6),
                    'description' => $def['name'].' — '.$p[0].'.',
                    'price_cents' => $p[1],
                    'stock' => $p[2],
                    'status' => 'active',
                ])
            );

            $owner->profile->update([
                'headline' => 'Founder, '.$def['name'],
                'bio' => 'Running '.$def['name'].' on PAFFAR. '.$def['description'],
                'industry' => $def['industry'],
                'business_name' => $def['name'],
                'website' => 'https://'.Str::slug($def['name']).'.example.com',
                'location' => $this->locations[$i % count($this->locations)],
            ]);
            $owner->profile->skills()->sync($skills->random(rand(2, 4))->pluck('id'));

            return ['user' => $owner, 'store' => $store, 'products' => $products];
        });
    }

    private function courseDefinitions(): array
    {
        return [
            ["Selling on PAFFAR: A Seller's Playbook", 0, ['Setting Up Your Store', 'Writing Product Listings That Sell', 'Pricing & Inventory Basics', 'Handling Orders & Customer Service', 'Growing Repeat Customers'], 'approved'],
            ['Bookkeeping Basics for Small Business Owners', 4900, ['Why Bookkeeping Matters', 'Tracking Income & Expenses', 'Understanding Cash Flow', 'Preparing for Tax Season'], 'approved'],
            ['Digital Marketing for Local Entrepreneurs', 5900, ['Finding Your Target Customer', 'Social Media Basics', 'Email Marketing 101', 'Measuring What Works'], 'approved'],
            ['Photography for Product Listings', 3900, ['Lighting on a Budget', 'Composition Basics', 'Editing With Free Tools'], 'approved'],
            ['Negotiation Skills for Vendors', 0, ['Preparing to Negotiate', 'Reading the Other Side', 'Closing the Deal'], 'approved'],
            ['Intro to SEO for Small Business Websites', 4500, ['How Search Engines Work', 'Keyword Basics', 'On-Page SEO Checklist', 'Local SEO for Small Business'], 'approved'],
            ['Building Your Personal Brand Online', 0, ['Defining Your Brand Story', 'Showing Up Consistently'], 'pending'],
        ];
    }

    private function seedTutors(Collection $skills): Collection
    {
        $defs = $this->courseDefinitions();
        $grouped = [[0], [1], [2], [3], [4], [5, 6]];

        return collect($grouped)->map(function (array $courseIndexes, int $i) use ($defs, $skills) {
            $tutor = User::factory()->create([
                'is_tutor' => true,
                'tutor_status' => 'approved',
            ]);

            $courses = collect($courseIndexes)->map(function (int $idx) use ($tutor) {
                [$title, $price, $lessons, $status] = $this->courseDefinitions()[$idx];

                $course = $tutor->courses()->create([
                    'title' => $title,
                    'slug' => Str::slug($title).'-'.Str::random(6),
                    'description' => 'Learn '.$title.' with practical, real-world lessons.',
                    'price_cents' => $price,
                ]);
                $course->status = $status;
                $course->save();

                foreach ($lessons as $position => $lessonTitle) {
                    $course->lessons()->create([
                        'title' => $lessonTitle,
                        'content' => 'In this lesson you will learn about '.$lessonTitle.'.',
                        'position' => $position,
                    ]);
                }

                return $course;
            });

            $tutor->profile->update([
                'headline' => 'Instructor on PAFFAR',
                'bio' => 'Teaching '.$courses->pluck('title')->join(' and ').' to fellow entrepreneurs on PAFFAR.',
                'industry' => 'Education',
                'location' => $this->locations[$i % count($this->locations)],
            ]);
            $tutor->profile->skills()->sync($skills->random(rand(2, 4))->pluck('id'));

            return ['user' => $tutor, 'courses' => $courses];
        });
    }

    private function seedApplicants(): Collection
    {
        $pendingSeller = User::factory()->create(['is_seller' => true, 'seller_status' => 'pending']);
        $pendingSeller->profile->update([
            'headline' => 'Aspiring Seller',
            'bio' => 'Just applied to open a store on PAFFAR.',
            'industry' => 'Retail',
        ]);

        $rejectedSeller = User::factory()->create(['is_seller' => true, 'seller_status' => 'rejected']);
        $rejectedSeller->profile->update([
            'headline' => 'Member',
            'bio' => 'Exploring PAFFAR as a buyer for now.',
        ]);

        $pendingTutor = User::factory()->create(['is_tutor' => true, 'tutor_status' => 'pending']);
        $pendingTutor->profile->update([
            'headline' => 'Aspiring Instructor',
            'bio' => 'Applied to teach a course on PAFFAR.',
            'industry' => 'Education',
        ]);

        return collect([$pendingSeller, $rejectedSeller, $pendingTutor]);
    }

    private function seedMembers(Collection $skills): Collection
    {
        $headlines = [
            'Marketing Consultant', 'Freelance Graphic Designer', 'Operations Manager',
            'Customer Success Specialist', 'Independent Contractor', 'Small Business Advisor',
            'Virtual Assistant', 'Sales Representative', 'Supply Chain Coordinator', 'Community Manager',
        ];
        $industries = ['Retail', 'Technology', 'Professional Services', 'Health & Wellness', 'Education', 'Manufacturing'];

        return collect(range(0, 9))->map(function (int $i) use ($skills, $headlines, $industries) {
            $member = User::factory()->create();

            $member->profile->update([
                'headline' => $headlines[$i % count($headlines)],
                'bio' => 'Active member of the PAFFAR community, connecting with vendors and entrepreneurs.',
                'industry' => $industries[$i % count($industries)],
                'location' => $this->locations[$i % count($this->locations)],
            ]);
            $member->profile->skills()->sync($skills->random(rand(2, 4))->pluck('id'));

            return $member;
        });
    }

    private function seedConnections(Collection $everyone): void
    {
        $everyone->each(function (User $user) use ($everyone) {
            $pool = $everyone->reject(fn (User $u) => $u->id === $user->id);
            $targets = $pool->random(min(rand(3, 8), $pool->count()));
            $user->following()->syncWithoutDetaching($targets->pluck('id'));
        });
    }

    private function seedFeed(Collection $everyone, Collection $sellers, Collection $tutors): void
    {
        $genericPosts = [
            'Excited to be part of the PAFFAR community!',
            'Looking for recommendations on reliable local suppliers — any tips?',
            'Just connected with a few great vendors here. Loving this platform.',
            'Anyone else taking courses on PAFFAR? Just finished one this week.',
            'Great conversation with a fellow entrepreneur today about scaling a small business.',
        ];

        foreach ($sellers as $seller) {
            $store = $seller['store'];
            $firstProduct = $seller['products']->first();

            $seller['user']->posts()->create([
                'body' => 'Just restocked '.$firstProduct->name.' over at '.$store->name.' — check it out!',
            ]);
            $seller['user']->posts()->create([
                'body' => 'Thank you to everyone who has supported '.$store->name.' so far. More products coming soon!',
            ]);
        }

        foreach ($tutors as $tutor) {
            foreach ($tutor['courses'] as $course) {
                $tutor['user']->posts()->create([
                    'body' => 'My course "'.$course->title.'" is now live on PAFFAR. Hope it helps fellow entrepreneurs!',
                ]);
            }
        }

        $everyone->each(function (User $user) use ($genericPosts) {
            if ($user->posts()->count() === 0) {
                $user->posts()->create(['body' => $genericPosts[array_rand($genericPosts)]]);
            }
        });

        Post::all()->each(function (Post $post) use ($everyone) {
            $pool = $everyone->reject(fn (User $u) => $u->id === $post->user_id);

            $likers = $pool->random(min(rand(0, 10), $pool->count()));
            foreach ($likers as $liker) {
                $post->likes()->create(['user_id' => $liker->id]);
            }

            $comments = [
                'This is great, thanks for sharing!',
                'Love this — congrats!',
                'Following along, keep it up.',
                'Great tip, will try this out.',
            ];
            $commenters = $pool->random(min(rand(0, 3), $pool->count()));
            foreach ($commenters as $commenter) {
                $post->comments()->create([
                    'user_id' => $commenter->id,
                    'body' => $comments[array_rand($comments)],
                ]);
            }
        });
    }

    private function reviewComment(): string
    {
        return collect([
            'Great quality and fast shipping, will order again.',
            'Exactly as described. Very happy with this purchase.',
            'Good experience overall, would recommend this seller.',
            'Solid product for the price.',
            'Communication was great and the order arrived quickly.',
        ])->random();
    }

    private function seedOrders(Collection $sellers, Collection $everyone): void
    {
        $statuses = ['completed', 'completed', 'completed', 'processing', 'pending', 'cancelled'];

        foreach ($sellers as $seller) {
            $store = $seller['store'];
            $pool = $everyone->reject(fn (User $u) => $u->id === $seller['user']->id);
            $buyers = $pool->random(min(6, $pool->count()));

            foreach ($buyers as $buyer) {
                for ($n = 0, $orderCount = rand(1, 2); $n < $orderCount; $n++) {
                    $lines = $seller['products']->random(rand(1, 3))->map(fn ($product) => [
                        'product' => $product,
                        'quantity' => rand(1, 3),
                    ]);
                    $total = $lines->sum(fn (array $line) => $line['product']->price_cents * $line['quantity']);
                    $status = $statuses[array_rand($statuses)];

                    $order = Order::create([
                        'buyer_id' => $buyer->id,
                        'store_id' => $store->id,
                        'status' => $status,
                        'total_cents' => $total,
                    ]);

                    foreach ($lines as $line) {
                        $order->items()->create([
                            'product_id' => $line['product']->id,
                            'quantity' => $line['quantity'],
                            'price_cents' => $line['product']->price_cents,
                        ]);
                    }

                    if ($status === 'completed') {
                        $store->reviews()->firstOrCreate(
                            ['user_id' => $buyer->id],
                            ['rating' => rand(3, 5), 'comment' => $this->reviewComment()]
                        );
                    }
                }
            }
        }
    }

    private function seedEnrollments(Collection $tutors, Collection $everyone): void
    {
        foreach ($tutors as $tutor) {
            foreach ($tutor['courses'] as $course) {
                if ($course->status !== 'approved') {
                    continue;
                }

                $pool = $everyone->reject(fn (User $u) => $u->id === $tutor['user']->id);
                $students = $pool->random(min(8, $pool->count()));

                foreach ($students as $student) {
                    $enrollment = $student->enrollments()->create(['course_id' => $course->id]);

                    if (rand(0, 1)) {
                        $enrollment->completed_at = now()->subDays(rand(1, 60));
                        $enrollment->save();

                        $course->reviews()->firstOrCreate(
                            ['user_id' => $student->id],
                            ['rating' => rand(3, 5), 'comment' => $this->reviewComment()]
                        );
                    }
                }
            }
        }
    }
}
