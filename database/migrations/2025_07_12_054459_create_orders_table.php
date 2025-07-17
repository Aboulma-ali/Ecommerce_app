<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->onDelete('set null');
            $table->decimal('total', 10, 2)->nullable();;
            $table->enum('status', ['en_attente', 'expédiée', 'livrée', 'annulée'])->default('en_attente');
            $table->enum('payment_status', ['non_payé', 'payé'])->default('non_payé');
            $table->enum('payment_method', ['en_ligne', 'à_la_livraison']);
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
