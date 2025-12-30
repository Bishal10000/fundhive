
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('story');
            $table->decimal('goal_amount', 15, 2);
            $table->decimal('current_amount', 15, 2)->default(0);
            $table->date('deadline');
            $table->string('featured_image');
            $table->json('gallery_images')->nullable();
            $table->string('video_url')->nullable();
            $table->enum('status', ['draft', 'pending', 'active', 'completed', 'suspended'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->integer('views')->default(0);

            $table->decimal('fraud_score', 5, 2)->default(0);  // default 0
            $table->boolean('is_flagged')->default(false);     // default false

            $table->timestamps();
            $table->softDeletes();
        });
    }
};
