<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        $query = Event::with(['category', 'tickets'])
            ->where('status', 'published');

        // Search
        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        // Filter by country
        if (request('country')) {
            $query->where('country', request('country'));
        }

        // Filter by category
        if (request('category')) {
            $query->where('category_id', request('category'));
        }

        $events = $query->latest('published_at')->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('welcome', compact('events', 'categories'));
    }
}
