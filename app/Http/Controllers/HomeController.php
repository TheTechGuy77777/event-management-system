<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $query = Event::with(['category', 'tickets'])
            ->where('status', 'published');

        if (request('search')) {
            $query->where('name', 'like', '%'.request('search').'%');
        }

        if (request('country')) {
            $query->where('country', request('country'));
        }

        if (request('category')) {
            $query->where('category_id', request('category'));
        }

        $events = $query->latest('published_at')->paginate(12);

        // Cache categories for 1 hour
        $categories = Cache::remember('active_categories', 3600, function () {
            return Category::where('is_active', true)->get();
        });

        return view('welcome', compact('events', 'categories'));
    }
}
