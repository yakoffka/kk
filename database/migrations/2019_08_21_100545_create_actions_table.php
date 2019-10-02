<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->set('type', [ // name model. set equivalent column.
                'Category', 
                'Comment',
                'Image',
                'Manufacturer',
                'Order',
                'Product',
                'Role',
                'User',
                'Setting',
            ]);
            $table->unsignedBigInteger('type_id');
            $table->set('action', [ // SET equivalent column.
                'create', 
                'update', 
                'delete',
                'verify', 
            ]);
            $table->text('description');
            $table->text('details')->nullable(); // serialized array. or longText??? or mediumText???
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
        Schema::dropIfExists('actions');
    }
}
