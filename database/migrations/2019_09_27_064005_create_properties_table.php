<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title");
            $table->text("description");
            $table->integer("bedroom");
            $table->integer("bathroom");
            $table->bigInteger("property_type")->unsigned();
            $table->foreign("property_type")->references("id")->on("property_types");
            $table->bigInteger("status_id")->unsigned();
            $table->foreign("status_id")->references("id")->on("statuses");
            $table->boolean("for_sale");
            $table->boolean("for_rent");
            $table->bigInteger("project_id")->unsigned();
            $table->foreign("project_id")->references("id")->on("projects");
            $table->bigInteger("region_id")->unsigned();
            $table->foreign("region_id")->references("id")->on("regions");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('building');
    }
}
