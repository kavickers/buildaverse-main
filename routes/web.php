<?php

use App\Http\Controllers\ForumController;
use App\Http\Controllers\GuildsController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\API\AvatarsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Main website */

Route::domain('dev.brixoro.com')->group(function () {
    /* Authentication Scaffolding */
    Auth::routes(['verify' => true]);

    /* Discord OAuth */
    Route::controller(DiscordController::class)->group(function () {
        Route::middleware(['auth'])->group(function () {
            Route::group(['prefix' => 'discord'], function() {
                Route::redirect('/connect', 'https://discord.com/oauth2/authorize?client_id=' . config('discord.client_id')
                    . '&redirect_uri=' . config('discord.redirect_uri')
                    . '&response_type=code&scope=' . implode('%20', explode('&', config('discord.scopes')))
                    . '&prompt=' . config('discord.prompt', 'consent'))
                    ->name('discord.connect');

                Route::get('/callback', 'handle')
                    ->name('discord.login');

                Route::redirect('/refresh-token', '/discord/connect')
                    ->name('discord.refresh_token');

                Route::post('/unlink', 'unlink')
                    ->name('discord.unlink');
            });
        });
    });

    /* User-related routing handled by UserController */
    Route::controller(UserController::class)->group(function () {
        /* Guest routes */
        Route::get('/', 'index')->name('index');
        Route::get('/site/offline', 'maintenance')->name('maintenance.index');
        Route::get('/user/{user}', 'profile')->name('user.profile');
        Route::get('/achievements', 'achievements')->name('achievements');

        /* Authenticated routes */
        Route::middleware(['auth'])->group(function () {
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/friends', 'friends')->name('user.friends');
            Route::get('/account/settings', 'settings')->name('user.settings');

            Route::post('/account/settings/general', 'update_settings_general')->name('user.settings.update.general');
            Route::post('/account/settings/privacy', 'update_settings_privacy')->name('user.settings.update.privacy');
            Route::post('/account/settings/logout_all', 'logout_other_sessions')->name('user.settings.logoutall');
            Route::post('/friends/{user}/accept', 'accept_friend')->name('user.friends.accept');
            Route::post('/friends/accept', 'accept_all_friends')->name('user.friends.accept.all');
            Route::post('/friends/{user}/decline', 'decline_friend')->name('user.friends.decline');
            Route::post('/friends/decline', 'decline_all_friends')->name('user.friends.decline.all');
            Route::post('/friends/{user}/remove', 'remove_friend')->name('user.friends.remove');
            Route::post('/friends/{user}/add', 'add_friend')->name('user.friends.add');
        });
    });

    /* Forum-related routing handed by ForumController */
    Route::controller(ForumController::class)->group(function () {
        /* Guest routes */
        Route::get('/forum', 'index')->name('forum.index');
        Route::get('/forum/thread/{thread}', 'show_thread')->name('forum.thread');

        /* Authenticated routes */
        Route::middleware(['auth'])->group(function () {
            Route::get('/forum/create', 'create_thread')->name('forum.thread.create');
            Route::get('/forum/thread/{thread}/reply', 'create_reply')->name('forum.thread.reply');
            Route::get('/forum/thread/{thread}/quote/{quote_id}/{quote_type}', 'show_quote')->name('forum.thread.quote');

            Route::post('/forum/create', 'store_thread')->name('forum.thread.create.post');
            Route::post('/forum/thread/{thread}/reply', 'store_reply')->name('forum.thread.reply.post');
            Route::post('/forum/thread/{thread}/quote/{quote_id}/{quote_type}', 'store_quote')->name('forum.thread.quote.post');

            /* Likes */
            Route::post('/forum/thread/{thread}/like', 'thread_like');
            Route::post('/forum/reply/{reply}/like', 'reply_like');

            /* Forum Moderation */
            Route::get('/forum/thread/{thread}/move', 'show_move')->name('forum.thread.move');
            Route::post('/forum/thread/{thread}/lock', 'lock_thread')->name('forum.thread.lock');
            Route::post('/forum/thread/{thread}/pin', 'pin_thread')->name('forum.thread.pin');
            Route::post('/forum/thread/{thread}/move', 'move_thread')->name('forum.thread.move.post');
        });
    });

    /* Reports-related routing handled by ReportController */
    Route::controller(ReportController::class)->group(function () {
        /* Guest routes */
        /* Authenticated routes */
        Route::middleware(['auth'])->group(function () {
            /* Frontend Views */
            Route::get('/thread/{thread}/report', 'report_thread')->name('report.threads');
            Route::get('/reply/{reply}/report', 'report_reply')->name('report.reply');
            Route::get('/user/{user}/report', 'report_user')->name('report.user');
            Route::get('/blurb/{blurb}/report', 'report_blurb')->name('report.blurb');
            Route::get('/item/{item}/report', 'report_item')->name('report.item');
            Route::get('/comment/{comment}/report', 'report_comment')->name('report.comment');

            /* Handle Report Submit */
            Route::post('/thread/{thread}/report', 'submit')->name('report.threads.submit');
            Route::post('/reply/{reply}/report', 'submit')->name('report.reply.submit');
            Route::post('/user/{user}/report', 'submit')->name('report.user.submit');
            Route::post('/blurb/{blurb}/report', 'submit')->name('report.blurb.submit');
            Route::post('/item/{item}/report', 'submit')->name('report.item.submit');
            Route::post('/comment/{comment}/report', 'submit')->name('report.comment.submit');
        });
    });

    Route::controller(MarketController::class)->group(function () {
        /* Guest routes */
        Route::get('/market', 'index')->name('market.index');
        Route::get('/market/item/{item}', 'show_item')->name('market.item');
        ;
        /* Authenticated routes */
        Route::middleware(['auth'])->group(function () {
            Route::get('/market/create', 'create_item')->name('market.create.index');
            Route::get('/market/create/shirt', 'create_shirt')->name('market.create.shirt');
            Route::get('/market/create/pants', 'create_pants')->name('market.create.pants');
            Route::get('/market/{item}/edit', 'edit_item')->name('market.item.edit');


            Route::post('/market/item/{item}/listing/{listing}/buy', 'buy_listing')->name('market.listing.buy');
            Route::post('/market/item/{item}/buy/{type}', 'buy_item')->name('market.item.buy');
            Route::post('/market/item/{item}/comment', 'comment')->name('market.item.comment');
            Route::post('/market/create/shirt/process', 'upload_shirt')->name('market.create.shirt.process');
            Route::post('/market/create/pants/process', 'upload_pants')->name('market.create.pants.process');
            Route::post('/market/{item}/edit', 'edit')->name('market.item.edit.post');
        });
    });

    Route::controller(GuildsController::class)->group(function ()
    {
        /* Guest routes */
        Route::get('/guilds/{guild}/view', 'view')->name('guilds.view');
        Route::get('/guilds/search', 'search')->name('guilds.search');
        Route::get('/guilds/explore', 'explore')->name('guilds.explore');

        /* Authenticated routes */
        Route::middleware(['auth'])->group(function ()
        {
            Route::get('/guilds/{guild}/edit', 'edit')->name('guilds.edit');
            Route::get('/guilds/create', 'create')->name('guilds.create');
            Route::get('/guilds', 'index')->name('guilds.index');
        });
    });
});

/* Administrative */
Route::domain('antelope.is')->group(function() {
    Route::get('/', function() {
        return response()->json([
            'success' => 'true',
            'message' => [
                'version' => config('app.framework'),
                'framework' => app()->version(),
            ],
        ], 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    });
});

/* APIs */
Route::domain('avatars.buildaverse.net')->group(function() {
    Route::controller(AvatarsController::class)->group(function ()
    {
        Route::get('/', 'index');
        /*
        * Buildaverse Avatar APIs v1.0.0; originally created April 18th, 2021 at 4:30AM for BLOXCity.com
        */
        Route::group(['prefix' => 'v1'], function() {
            Route::get('/', 'v1');
            Route::get('/render/{user}', 'render');
            Route::get('/market/{item}', 'market');
        });
        
    });
    
});