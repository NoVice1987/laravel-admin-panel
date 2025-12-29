<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add soft deletes to admin_users table if it doesn't exist
        if (!Schema::hasColumn('admin_users', 'deleted_at')) {
            Schema::table('admin_users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add soft deletes to news table if it doesn't exist
        if (!Schema::hasColumn('news', 'deleted_at')) {
            Schema::table('news', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add soft deletes to menus table if it doesn't exist
        if (!Schema::hasColumn('menus', 'deleted_at')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add soft deletes to menu_items table if it doesn't exist
        if (!Schema::hasColumn('menu_items', 'deleted_at')) {
            Schema::table('menu_items', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
