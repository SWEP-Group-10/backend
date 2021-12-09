<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCourseDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_department', function (Blueprint $table) {
            $table->dropColumn('department_code');
            $table->integer('department_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_department', function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->string('department_code');
        });
    }
}
