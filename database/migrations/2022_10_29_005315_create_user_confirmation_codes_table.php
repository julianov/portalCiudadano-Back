<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('user_confirmation_codes', function (Blueprint $table) {

			$table->bigInteger('id');
			# $table->foreign('id')->references('cuil')->on('users')->onDelete('cascade');
			$table->foreignIdFor(User::class, 'user_id')->constrained()->onDelete('cascade');
			$table->integer('code');
			$table->timestamp('created_at')->nullable();
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('user_confirmation_codes');
	}
};
