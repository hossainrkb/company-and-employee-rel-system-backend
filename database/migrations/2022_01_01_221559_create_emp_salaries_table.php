<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_salaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emp_id');
            $table->bigInteger('com_id');
            $table->string('trx_key',255);
            $table->integer('month');
            $table->integer('year');
            $table->integer('salary_amount');
            $table->string('salary_type')->comment('BASIC, MISC etc');
            $table->string('salary_status')->comment('PENDING,PROCESSING etc');
            $table->string('salary_currency');
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
        Schema::dropIfExists('emp_salaries');
    }
}
