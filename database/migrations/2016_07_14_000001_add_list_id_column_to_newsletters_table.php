<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddListIdColumnToNewslettersTable extends Migration {

    public function up()
    {
        Schema::table('newsletters', function (Blueprint $table) {
            $table->string('list_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('newsletters', function (Blueprint $table) {
            $table->dropColumn('list_id');
        });
    }

}