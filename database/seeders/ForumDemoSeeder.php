<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumThread;
use App\Models\User;
use Illuminate\Database\Seeder;

class ForumDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->orderBy('id')->first();

        if (! $admin) {
            return;
        }

        $threads = [
            [
                'category' => 'general-discussion',
                'title' => "G'day from Brisbane — new here",
                'slug' => 'gday-from-brisbane-new-here',
                'posts' => [
                    '<p>Howdy all, just signed up. Running Brisbane to Sydney twice a week in a Kenworth. Looking forward to sharing road intel and learning a few shortcuts.</p>',
                    '<p>Welcome aboard! The Route Reports section is worth watching before every run — saved me from a low bridge near Eaglehawk last month.</p>',
                    '<p>Cheers! Already found two weight limits on my usual route I never knew about. Great resource.</p>',
                ],
            ],
            [
                'category' => 'route-reports-hazards',
                'title' => 'Montague St bridge — another strike this morning',
                'slug' => 'montague-st-bridge-another-strike',
                'posts' => [
                    '<p>Heads up, another truck hit the Montague St bridge in South Melbourne around 6am today. Expect delays and photographers. If you are over 4m, stay well clear.</p>',
                    '<p>That bridge has its own warning signs, flashing lights, and still collects trucks monthly. Unbelievable.</p>',
                    '<p>Reported it here with fresh photos. Verification count is climbing — thanks everyone.</p>',
                ],
            ],
            [
                'category' => 'vehicle-tips-tricks',
                'title' => 'Tyre pressures for B-doubles in summer?',
                'slug' => 'tyre-pressures-for-b-doubles-in-summer',
                'posts' => [
                    '<p>What pressures are people running on their trailers through summer? I have been on 100 psi cold but the wear on the shoulders looks off with heavy loads.</p>',
                    '<p>Depends on your rubber, but most of us run 95–100 cold on 11R22.5s and check them every pre-start. Heat buildup is the killer — an under-inflated tyre in 40° heat will delaminate fast.</p>',
                    '<p>+1 for daily checks. TPMS paid for itself within a year on my rig. Caught two slow leaks before they became blowouts.</p>',
                ],
            ],
            [
                'category' => 'off-topic',
                'title' => 'Best roadhouse pies — Nullarbor edition',
                'slug' => 'best-roadhouse-pies-nullarbor-edition',
                'posts' => [
                    '<p>Settle it once and for all: best pie on the Nullarbor crossing? My vote is the pepper steak at Border Village. Fight me.</p>',
                    '<p>Border Village is solid but the plain mince at Caiguna is the sleeper pick. Flaky pastry, proper gravy, no nonsense.</p>',
                ],
            ],
        ];

        foreach ($threads as $data) {
            $category = ForumCategory::where('slug', $data['category'])->first();

            if (! $category) {
                continue;
            }

            $thread = ForumThread::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $category->id,
                    'user_id' => $admin->id,
                    'title' => $data['title'],
                ]
            );

            foreach ($data['posts'] as $index => $content) {
                if ($thread->posts()->where('content', $content)->exists()) {
                    continue;
                }

                ForumPost::create([
                    'thread_id' => $thread->id,
                    'user_id' => $admin->id,
                    'content' => $content,
                    'is_edited' => false,
                ]);

                // Stagger timestamps so threads look naturally aged
                $thread->posts()->latest('id')->first()?->update([
                    'created_at' => now()->subDays(count($data['posts']) - $index)->subHours($index),
                    'updated_at' => now()->subDays(count($data['posts']) - $index)->subHours($index),
                ]);
            }

            $thread->update([
                'last_post_at' => $thread->posts()->max('created_at') ?? now(),
                'created_at' => now()->subDays(count($data['posts']) + 1),
            ]);
        }
    }
}
