<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_services', function (Blueprint $table) {
            $table->id();
            $table->string('po_no');
            $table->date('date');
            $table->string('vendor_code');
            $table->string('project_code');
            $table->boolean('is_vat')->default(true);
            $table->string('remarks')->nullable();
            $table->tinyInteger('print_count')->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('po_services');
    }
}
