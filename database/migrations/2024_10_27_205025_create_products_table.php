<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->index();
            $table->string('name');
            $table->foreignId('brand_id')->nullable()->constrained('brands', 'id');
            $table->foreignId('category_id')->constrained('categories', 'id');
            $table->foreignId('unit_id')->nullable()->constrained('units', 'id');
            $table->decimal('vat')->default(0);
            $table->integer('reorder')->default(0);
            $table->decimal('purchase_rate', 18, 2)->default(0);
            $table->decimal('sale_rate', 18, 2)->default(0);
            $table->decimal('wholesale_rate', 18, 2)->default(0);
            $table->enum('is_service', [1, 0])->default(0)->comment('1=service,0=product');
            $table->string('image')->nullable();
            $table->char('status', 1)->default('a');
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->dateTime('created_at')->useCurrent();
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
            $table->dateTime('updated_at')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('users', 'id');
            $table->softDeletes();
            $table->ipAddress('ipAddress');
            $table->foreignId('branch_id')->constrained('branches', 'id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
