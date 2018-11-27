<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitialMigration extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('type', ['manager', 'cook', 'cashier', 'waiter']);
            $table->boolean('blocked')->default(false);
            $table->string('photo_url')->nullable();
            $table->dateTime('last_shift_start')->nullable();
            $table->dateTime('last_shift_end')->nullable();
            $table->boolean('shift_active')->default(false);
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->enum('type', ['dish', 'drink']);
            $table->string('description');
            $table->string('photo_url');
            $table->decimal('price', 8, 2);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->integer('table_number');
            $table->primary('table_number');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('meals', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('state', ['active', 'terminated', 'paid', 'not paid']);
            $table->integer('table_number');
            $table->foreign('table_number')->references('table_number')->on('restaurant_tables');
            $table->dateTime('start');
            $table->dateTime('end')->nullable();  //state = "paid" or state = "not paid"
            $table->integer('responsible_waiter_id')->unsigned();
            $table->foreign('responsible_waiter_id')->references('id')->on('users');
            $table->decimal('total_price_preview', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('state', ['pending', 'confirmed', 'in preparation', 'prepared', 'delivered', 'not delivered']);
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items');
            $table->integer('meal_id')->unsigned();
            $table->foreign('meal_id')->references('id')->on('meals');
            $table->integer('responsible_cook_id')->unsigned();
            $table->foreign('responsible_cook_id')->references('id')->on('users');
            $table->dateTime('start');
            $table->dateTime('end')->nullable();  //state = "delivered" or state = "not delivered"
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('state', ['pending', 'paid', 'not paid']);
            $table->integer('meal_id')->unsigned();
            $table->foreign('meal_id')->references('id')->on('meals');
            $table->string('nif')->nullable();
            $table->string('name')->nullable();
            $table->date('date');
            $table->decimal('total_price', 8, 2);
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->integer('invoice_id')->unsigned();
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items');
            $table->primary(['invoice_id', 'item_id']);
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('sub_total_price', 8, 2);
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('meals');
        Schema::dropIfExists('restaurant_tables');
        Schema::dropIfExists('items');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
    }
}
