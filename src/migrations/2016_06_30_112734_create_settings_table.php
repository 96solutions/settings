<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(Config::get('settings.connection', null))->create(Config::get('settings.table', 'settings'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('value')->nullable();
            $table->string('module')->nullable()->default('global');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['module', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(Config::get('settings.connection', null))->dropIfExists(Config::get('settings.table', 'settings'));
    }
}
