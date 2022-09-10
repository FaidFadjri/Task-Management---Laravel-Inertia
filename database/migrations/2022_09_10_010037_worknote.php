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
        #---- Main tables
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->enum('progress', ['To Do', 'On Progress', 'Pending', 'Stuck', 'Complete']);
            $table->date('due_date');
            $table->string('estimation_cpus');
            $table->string('estimation_cost');
            $table->string('estimation_revenue');
            $table->string('actual_cpus');
            $table->string('actual_cost');
            $table->string('actual_revenue');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('project_type', ['project', 'activity']);
            $table->enum('priority', ['HIGH', 'MEDIUM', 'LOW']);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
