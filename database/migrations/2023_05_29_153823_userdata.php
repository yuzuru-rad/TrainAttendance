<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('UserData', function (Blueprint $table) {
            $table->id();
            $table->integer('UID')->nullable();
            $table->string('IDMNo', 30)->nullable();
            $table->string('FullName', 50)->nullable();
            $table->string('CodeNo', 5)->nullable();
            $table->string('Section', 15)->nullable();
            $table->string('SectionId', 30)->nullable();
            $table->string('License', 10)->nullable();
            $table->integer('OfficialPosition')->nullable();
            $table->integer('WOFlag')->nullable();
            $table->integer('DAFlag')->nullable();
            $table->integer('NUFlag')->nullable();
            $table->integer('DRFlag')->nullable();
            $table->integer('COFlag')->nullable();
            $table->integer('CBFlag')->nullable();
            $table->integer('BOFlag')->nullable();
            $table->integer('SOFlag')->nullable();
            $table->integer('RNFlag')->nullable();
            $table->integer('ADFlag')->nullable();
            $table->integer('SCHFlag')->nullable();
            $table->string('PlanSectionId', 15)->nullable();
            $table->datetime('PlanDate')->nullable();
            $table->datetime('RetireDate')->nullable();
            $table->integer('KaiFlag')->nullable();
            $table->integer('GaiFlag')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('UserData');
    }
};
