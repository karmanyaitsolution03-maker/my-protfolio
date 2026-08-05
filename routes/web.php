<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

/* ---------- public ---------- */
Route::get('/', [PortfolioController::class, 'home'])->name('home');
Route::get('/resume', [PortfolioController::class, 'resume'])->name('resume.download');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
Route::post('/interactions', [InteractionController::class, 'track'])->middleware('throttle:20,1')->name('interactions.track');
Route::post('/assistant/chat', [AssistantController::class, 'chat'])->middleware('throttle:10,1')->name('assistant.chat');
Route::post('/assistant/speak', [AssistantController::class, 'speak'])->middleware('throttle:40,1')->name('assistant.speak');
Route::post('/assistant/match', [AssistantController::class, 'match'])->middleware('throttle:6,1')->name('assistant.match');

/* ---------- sitemap ---------- */
Route::get('/sitemap.xml', function (\Illuminate\Http\Request $request) {
    // Build absolute URLs from the request host so the sitemap always
    // matches the live domain (production) regardless of APP_URL.
    $base = rtrim($request->getSchemeAndHttpHost(), '/');

    $urls = [
        ['loc' => $base . '/', 'changefreq' => 'weekly', 'priority' => '1.0'],
    ];

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $u) {
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . "</loc>\n";
        $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
        $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
        $xml .= "  </url>\n";
    }
    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

/* ---------- admin (simple session auth via ADMIN_PASSWORD in .env) ---------- */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'settingsSave'])->name('settings.save');
    Route::post('/migrate', [AdminController::class, 'migrate'])->name('migrate');
    Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
    Route::get('/messages/export', [AdminController::class, 'messagesExport'])->name('messages.export');
    Route::post('/messages/{message}/reply', [AdminController::class, 'messageReply'])->name('messages.reply');
    Route::delete('/messages/{message}', [AdminController::class, 'messageDelete'])->name('messages.delete');
    Route::get('/visitors', [AdminController::class, 'visitors'])->name('visitors');

    Route::get('/{resource}', [AdminController::class, 'index'])->name('res.index');
    Route::get('/{resource}/create', [AdminController::class, 'create'])->name('res.create');
    Route::post('/{resource}', [AdminController::class, 'store'])->name('res.store');
    Route::get('/{resource}/{id}/edit', [AdminController::class, 'edit'])->name('res.edit');
    Route::put('/{resource}/{id}', [AdminController::class, 'update'])->name('res.update');
    Route::delete('/{resource}/{id}', [AdminController::class, 'destroy'])->name('res.destroy');
});
