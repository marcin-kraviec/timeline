<?php

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CategoryController;

const COLOR_MAP = [
    "Czarny" => "bg-primary",
    "Biały" => "bg-light",
    "Zielony" => "bg-success",
    "Żółty" => "bg-warning",
    "Czerwony" => "bg-danger",
    "Niebieski" => "bg-info"
];

Route::get('/', function () {
    $selectedYear = request('year');
    $currentYear = Carbon::now()->year;
    $years = Event::selectRaw('YEAR(start_date) as year')
        ->union(
            Event::selectRaw('YEAR(end_date) as year')
        )
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year')
        ->toArray();
    if (!in_array($currentYear, $years)) {
        $years[] = $currentYear;
    }
    rsort($years);
    if ($selectedYear!=null) {
        $eventsQuery = Event::query();
        if ($selectedYear) {
            $eventsQuery->whereYear('start_date', $selectedYear)
                        ->orWhereYear('end_date', $selectedYear);
        }
        $events = $eventsQuery->get();
    } else {
        $events = Event::all();
    }
    $categories = Category::withCount('events')->get();
    $totalEventsCount = Event::count();
    $usedColors = $categories->pluck('color')->toArray();
    $allColors = array_keys(COLOR_MAP);
    $availableColors = array_diff($allColors, $usedColors);
    $user = "";
    if (Auth::check()) {
        $user = Auth::user()->name;
    }

    return view('home', [
        'events' => $events,
        'categories' => $categories,
        'color_map' => COLOR_MAP,
        'totalEventsCount' => $totalEventsCount,
        'usedColors' => $usedColors,
        'availableColors' => $availableColors,
        'years' => $years,
        'currentYear' => $currentYear,
        'user' => $user
    ]);
});

Route::post('/register', [UserController::class, 'register']);
Route::get('/register', function () {
    return view('register');
});

Route::post('/login', [UserController::class, 'login']);
Route::get('/login', function () {
    return view('login');

})->name('login');

Route::post('/profile', [UserController::class, 'updatePassword']);
Route::get('/profile', function () {
    return view('profile');
})->middleware('auth');

Route::get('/logout', [UserController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::post('/event', [EventController::class, 'createEvent']);
    Route::delete('/event/{event}', [EventController::class, 'deleteEvent']);
    Route::patch('/event/{event}', [EventController::class, 'modifyEvent']);
    Route::post('/category', [CategoryController::class, 'createCategory']);
    Route::delete('/category/{category}', [CategoryController::class, 'deleteCategory']);
    Route::patch('/category/{category}', [CategoryController::class, 'modifyCategory']);
});
