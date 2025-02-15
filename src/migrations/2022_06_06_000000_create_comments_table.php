<?php

/**
 * Part of the Laravel-ThreadedComment package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the MIT License.
 *
 * This source file is subject to the MIT License that is
 * bundled with this package in the LICENSE file.
 * It is also available at the following URL: http://opensource.org/licenses/MIT
 *
 * @version    1.0.0
 * @author     BMason
 * @license    MIT
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('title')->nullable();
            $table->text('text');

            $table->integer('commentable_id')->unsigned()->nullable();
            $table->string('commentable_type')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('root_id');
            $table->text('root_type');

            $table->index('user_id');
            $table->index('root_id');
            $table->index('root_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments');
    }
}
