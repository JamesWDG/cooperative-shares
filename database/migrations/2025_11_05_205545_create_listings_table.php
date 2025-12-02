<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('listing');
            $table->string('property_title');
            $table->text('description');
            $table->string('category');
            $table->string('listed_in');
            $table->decimal('price', 10, 2);
            $table->decimal('tax_rate', 10, 2);
            $table->string('size_in_ft');
            $table->string('bedrooms');
            $table->string('bathrooms');
            $table->string('kitchens');
            $table->string('garages');
            $table->string('garage_size');
            $table->date('year_built');
            $table->string('floors');
            $table->text('listing_description');
            $table->longtext('files');
            $table->string('has_ac')->nullable();
            $table->string('has_garages')->nullable();
            $table->string('has_pool')->nullable();
            $table->string('has_parking')->nullable();
            $table->string('has_lakeview')->nullable();
            $table->string('has_garden')->nullable();
            $table->string('has_fireplace')->nullable();
            $table->string('has_pet')->nullable();
            $table->string('has_ceiling')->nullable();
            $table->string('has_shower')->nullable();
            $table->string('has_refrigerator')->nullable();
            $table->string('has_dryer')->nullable();
            $table->string('has_wifi')->nullable();
            $table->string('has_tv')->nullable();
            $table->string('has_bbq')->nullable();
            $table->string('has_laundry')->nullable();
            $table->string('has_accessible')->nullable();
            $table->string('has_lawn')->nullable();
            $table->string('has_elevator')->nullable();
            $table->text('address');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->integer('zip_code');
            $table->string('map_location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
