<?php

namespace Tighten\NovaStripe\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class TestUser extends BaseUser
{
    use Notifiable;

    protected $table = 'users';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        /**
         * Given that package tests are not in the context of a full Laravel application,
         * `users` table does not exist and is created on the fly. The in-memory database
         * will handle this quickly, ensuring we can interact with the User model.
         */
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamps();
            });
        }
    }
}
