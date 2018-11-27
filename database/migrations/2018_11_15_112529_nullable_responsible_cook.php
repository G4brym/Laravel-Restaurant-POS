<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// To execute this migration, first install doctrine/dbal with the following comand:
// composer require doctrine/dbal

class NullableResponsibleCook extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('responsible_cook_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $firstCook = DB::table('users')->where('type', 'cook')->first();
        if ($firstCook != null) {
            DB::table('orders')->where('responsible_cook_id', null)
                              ->update(['responsible_cook_id' => $firstCook->id]);
        }
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('responsible_cook_id')->unsigned()->nullable(false)->change();
        });
    }
}
