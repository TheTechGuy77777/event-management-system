<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ===== CREATE EVENT MANAGERS =====
        $managers = [
            [
                'name' => 'Chibuzo Okonkwo',
                'email' => 'chibuzo@eventplug.com',
                'phone' => '+2348012345678',
                'organization_name' => 'Plug Entertainment',
            ],
            [
                'name' => 'Amara Nwosu',
                'email' => 'amara@eventplug.com',
                'phone' => '+2348098765432',
                'organization_name' => 'Amara Events Co.',
            ],
            [
                'name' => 'Tunde Bakare',
                'email' => 'tunde@eventplug.com',
                'phone' => '+2348055544433',
                'organization_name' => 'Lagos Vibes',
            ],
        ];

        $createdManagers = [];
        foreach ($managers as $manager) {
            $createdManagers[] = User::updateOrCreate(
                ['email' => $manager['email']],
                [
                    'name' => $manager['name'],
                    'password' => Hash::make('Password@123'),
                    'role' => 'event_manager',
                    'phone' => $manager['phone'],
                    'organization_name' => $manager['organization_name'],
                    'is_active' => true,
                    'is_banned' => false,
                    'email_verified_at' => now(),
                ]
            );
        }

        // ===== CREATE EVENTS =====
        $categories = Category::all();

        $eventsData = [
            [
                'name' => 'Lagos Tech Fest 2026',
                'description' => 'A gathering of developers, startups, and tech enthusiasts across Africa.',
                'event_type' => 'Conference',
                'category' => 'Tech',
                'location' => 'Landmark Centre, Lagos',
                'country' => 'Nigeria',
                'image' => 'https://source.unsplash.com/featured/?technology,conference',
                'start_date' => now()->addDays(20),
                'end_date' => now()->addDays(20)->addHours(6),
                'status' => 'published',
                'payment_model' => 'attendee_pays',
                'tickets' => [
                    ['name' => 'Standard', 'price' => 20000, 'quantity' => 300],
                    ['name' => 'VIP', 'price' => 50000, 'quantity' => 100],
                ],
                'manager_index' => 0,
            ],
            [
                'name' => 'Afrobeats Live Concert',
                'description' => 'Experience top Afrobeats artists live on stage.',
                'event_type' => 'Concert',
                'category' => 'Music',
                'location' => 'Eko Hotel, Lagos',
                'country' => 'Nigeria',
                'image' => 'https://source.unsplash.com/featured/?concert,afrobeats',
                'start_date' => now()->addDays(25),
                'end_date' => now()->addDays(25)->addHours(5),
                'status' => 'published',
                'payment_model' => 'attendee_pays',
                'tickets' => [
                    ['name' => 'Regular', 'price' => 15000, 'quantity' => 500],
                    ['name' => 'VIP', 'price' => 40000, 'quantity' => 150],
                ],
                'manager_index' => 1,
            ],
            [
                'name' => 'Startup Pitch Lagos',
                'description' => 'Pitch your startup idea to investors and win funding.',
                'event_type' => 'Networking',
                'category' => 'Business',
                'location' => 'Yaba Tech Hub',
                'country' => 'Nigeria',
                'image' => 'https://source.unsplash.com/featured/?startup,pitch',
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(15)->addHours(4),
                'status' => 'published',
                'payment_model' => 'attendee_pays',
                'tickets' => [
                    ['name' => 'Free Pass', 'price' => 0, 'quantity' => 300, 'ticket_type' => 'free'],
                ],
                'manager_index' => 2,
            ],
            [
                'name' => 'Fashion Week Nigeria',
                'description' => 'Top designers showcase their latest collections.',
                'event_type' => 'Exhibition',
                'category' => 'Fashion',
                'location' => 'Federal Palace Hotel',
                'country' => 'Nigeria',
                'image' => 'https://source.unsplash.com/featured/?fashion,runway',
                'start_date' => now()->addDays(40),
                'end_date' => now()->addDays(40)->addHours(6),
                'status' => 'published',
                'payment_model' => 'manager_pays',
                'tickets' => [
                    ['name' => 'General', 'price' => 20000, 'quantity' => 400],
                ],
                'manager_index' => 0,
            ],
            [
                'name' => 'Comedy Night Lagos',
                'description' => 'An unforgettable night of laughter.',
                'event_type' => 'Performance',
                'category' => 'Comedy',
                'location' => 'Terra Kulture',
                'country' => 'Nigeria',
                'image' => 'https://source.unsplash.com/featured/?comedy,show',
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(10)->addHours(3),
                'status' => 'published',
                'payment_model' => 'attendee_pays',
                'tickets' => [
                    ['name' => 'Regular', 'price' => 10000, 'quantity' => 200],
                ],
                'manager_index' => 1,
            ],
            [
                'name' => 'Beach Party Lagos',
                'description' => 'Music, vibes, and fun at the beach.',
                'event_type' => 'Party',
                'category' => 'Music',
                'location' => 'Elegushi Beach',
                'country' => 'Nigeria',
                'image' => 'https://source.unsplash.com/featured/?beach,party',
                'start_date' => now()->addDays(60),
                'end_date' => now()->addDays(60)->addHours(8),
                'status' => 'draft',
                'payment_model' => 'attendee_pays',
                'tickets' => [
                    ['name' => 'Early Bird', 'price' => 8000, 'quantity' => 500],
                ],
                'manager_index' => 2,
            ],
            [
                'name' => 'Digital Marketing Masterclass',
                'description' => 'Learn how to grow businesses online.',
                'event_type' => 'Workshop',
                'category' => 'Business',
                'location' => 'Ikeja',
                'country' => 'Nigeria',
                'image' => 'https://source.unsplash.com/featured/?marketing,workshop',
                'start_date' => now()->addDays(18),
                'end_date' => now()->addDays(18)->addHours(5),
                'status' => 'published',
                'payment_model' => 'attendee_pays',
                'tickets' => [
                    ['name' => 'Access', 'price' => 12000, 'quantity' => 150],
                ],
                'manager_index' => 0,
            ],
            [
                'name' => 'Fitness Bootcamp Lagos',
                'description' => 'Stay fit and healthy with top trainers.',
                'event_type' => 'Health',
                'category' => 'Health',
                'location' => 'Lekki Phase 1',
                'country' => 'Nigeria',
                'image' => 'https://source.unsplash.com/featured/?fitness,training',
                'start_date' => now()->addDays(12),
                'end_date' => now()->addDays(12)->addHours(2),
                'status' => 'published',
                'payment_model' => 'attendee_pays',
                'tickets' => [
                    ['name' => 'Entry', 'price' => 5000, 'quantity' => 100],
                ],
                'manager_index' => 1,
            ],
            [
                'name' => 'Art Exhibition Lagos',
                'description' => 'Explore modern African art.',
                'event_type' => 'Exhibition',
                'category' => 'Art',
                'location' => 'Nike Art Gallery',
                'country' => 'Nigeria',
                'image' => 'https://source.unsplash.com/featured/?art,exhibition',
                'start_date' => now()->addDays(22),
                'end_date' => now()->addDays(22)->addHours(5),
                'status' => 'published',
                'payment_model' => 'manager_pays',
                'tickets' => [
                    ['name' => 'Entry', 'price' => 7000, 'quantity' => 200],
                ],
                'manager_index' => 2,
            ],
            [
                'name' => 'Crypto & Web3 Meetup',
                'description' => 'Discuss blockchain and crypto opportunities.',
                'event_type' => 'Meetup',
                'category' => 'Tech',
                'location' => 'Victoria Island',
                'country' => 'Nigeria',
                'image' => 'https://source.unsplash.com/featured/?crypto,blockchain',
                'start_date' => now()->addDays(8),
                'end_date' => now()->addDays(8)->addHours(3),
                'status' => 'published',
                'payment_model' => 'attendee_pays',
                'tickets' => [
                    ['name' => 'Free', 'price' => 0, 'quantity' => 300, 'ticket_type' => 'free'],
                ],
                'manager_index' => 0,
            ],
        ];

        $createdEvents = [];
        foreach ($eventsData as $eventData) {
            $manager = $createdManagers[$eventData['manager_index']];
            $category = $categories->where('name', $eventData['category'])->first()
                ?? $categories->first();

            $slug = Str::slug($eventData['name']);
            $originalSlug = $slug;
            $count = 1;
            while (Event::where('slug', $slug)->exists()) {
                $slug = $originalSlug.'-'.$count++;
            }

            $event = Event::updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id' => $manager->id,
                    'category_id' => $category->id,
                    'name' => $eventData['name'],
                    'image' => $eventData['image'],
                    'slug' => $slug,
                    'description' => $eventData['description'],
                    'event_type' => $eventData['event_type'],
                    'location' => $eventData['location'],
                    'country' => $eventData['country'],
                    'start_date' => $eventData['start_date'],
                    'end_date' => $eventData['end_date'],
                    'timezone' => 'Africa/Lagos',
                    'status' => $eventData['status'],
                    'published_at' => $eventData['status'] === 'published' ? now() : null,
                    'payment_model' => $eventData['payment_model'],
                    'commission_rate' => 5,
                    'is_virtual' => false,
                    'is_recurring' => false,
                ]
            );

            // Create tickets
            foreach ($eventData['tickets'] as $ticketData) {
                Ticket::updateOrCreate(
                    ['event_id' => $event->id, 'name' => $ticketData['name']],
                    [
                        'ticket_type' => $ticketData['ticket_type'] ?? 'paid',
                        'admission_type' => $ticketData['admission_type'] ?? 'single',
                        'group_size' => $ticketData['group_size'] ?? null,
                        'price' => $ticketData['price'],
                        'quantity' => $ticketData['quantity'],
                        'quantity_sold' => 0,
                        'purchase_limit' => 5,
                        'is_active' => true,
                    ]
                );
            }

            $createdEvents[] = $event;
        }

        // ===== CREATE ORDERS & ATTENDEES =====
        $buyers = [
            ['name' => 'Emeka Johnson', 'email' => 'emeka@gmail.com', 'phone' => '+2348011111111'],
            ['name' => 'Fatima Aliyu', 'email' => 'fatima@gmail.com', 'phone' => '+2348022222222'],
            ['name' => 'Chidi Okeke', 'email' => 'chidi@gmail.com', 'phone' => '+2348033333333'],
            ['name' => 'Ngozi Adeyemi', 'email' => 'ngozi@gmail.com', 'phone' => '+2348044444444'],
            ['name' => 'Babatunde Osei', 'email' => 'babatunde@gmail.com', 'phone' => '+2348055555555'],
            ['name' => 'Aisha Musa', 'email' => 'aisha@gmail.com', 'phone' => '+2348066666666'],
            ['name' => 'Kelechi Eze', 'email' => 'kelechi@gmail.com', 'phone' => '+2348077777777'],
            ['name' => 'Yetunde Olatunji', 'email' => 'yetunde@gmail.com', 'phone' => '+2348088888888'],
        ];

        // Create orders for published events
        foreach ($createdEvents as $event) {
            if ($event->status !== 'published') {
                continue;
            }

            $tickets = $event->tickets()->where('ticket_type', '!=', 'free')->get();
            if ($tickets->isEmpty()) {
                continue;
            }

            // Create 5-10 orders per event
            $orderCount = rand(5, 10);
            for ($i = 0; $i < $orderCount; $i++) {
                $buyer = $buyers[array_rand($buyers)];
                $ticket = $tickets->random();
                $qty = rand(1, 2);

                $unitPrice = $ticket->price;
                $subtotal = $unitPrice * $qty;
                $commission = $subtotal * 0.05;
                $totalAmount = $event->payment_model === 'attendee_pays'
                    ? $subtotal + $commission
                    : $subtotal;
                $managerEarnings = $subtotal - ($event->payment_model === 'manager_pays' ? $commission : 0);

                $order = Order::create([
                    'event_id' => $event->id,
                    'buyer_name' => $buyer['name'],
                    'buyer_email' => $buyer['email'],
                    'buyer_phone' => $buyer['phone'],
                    'total_amount' => $totalAmount,
                    'platform_commission' => $commission,
                    'manager_earnings' => $managerEarnings,
                    'payment_reference' => 'EVT-'.strtoupper(Str::random(12)),
                    'payment_gateway' => rand(0, 1) ? 'paystack' : 'monnify',
                    'payment_status' => 'completed',
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(1, 30)),
                ]);

                // Create order items
                for ($j = 0; $j < $qty; $j++) {
                    $ticketCode = 'EVT-'.strtoupper(Str::random(6));

                    OrderItem::create([
                        'order_id' => $order->id,
                        'ticket_id' => $ticket->id,
                        'attendee_name' => $buyer['name'],
                        'attendee_email' => $buyer['email'],
                        'ticket_code' => $ticketCode,
                        'unit_price' => $unitPrice,
                        'is_checked_in' => rand(0, 1),
                        'checked_in_at' => rand(0, 1) ? now()->subHours(rand(1, 5)) : null,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at,
                    ]);
                }

                // Update ticket quantity sold
                $ticket->increment('quantity_sold', $qty);
            }

            // Create notifications for event manager
            Notification::create([
                'user_id' => $event->user_id,
                'title' => 'Tickets selling fast! 🔥',
                'message' => $event->name.' has sold '.$event->tickets->sum('quantity_sold').' tickets so far.',
                'type' => 'success',
                'created_at' => now()->subDays(rand(1, 5)),
                'updated_at' => now()->subDays(rand(1, 5)),
            ]);
        }

        $this->command->info('✅ Demo data seeded successfully!');
        $this->command->info('Event Managers created:');
        foreach ($createdManagers as $manager) {
            $this->command->info('  - '.$manager->email.' / Password@123');
        }
    }
}
