<?php // todo fix tag

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by_user')->nullable();
            $table->unsignedBigInteger('updated_by_user')->nullable();
            $table->string

("title")->nullable();
            $table->string

("content")->nullable();
            $table->enum
("status", ["Active","In Active"],)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}

