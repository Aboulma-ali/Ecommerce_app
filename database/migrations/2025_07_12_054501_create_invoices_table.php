<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('pdf_path')->nullable(); // Facultatif tant que le PDF n'est pas généré
            $table->string('client_name');
            $table->string('client_address');
            $table->string('client_email');
            $table->text('products'); // JSON ou texte avec la liste des produits (nom, quantité, prix)
            $table->decimal('total', 10, 2);
            $table->decimal('total_ttc', 10, 2)->nullable(); // Si tu gères la TVA
            $table->timestamp('order_date');
            $table->enum('payment_method', ['en_ligne', 'à_la_livraison']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
