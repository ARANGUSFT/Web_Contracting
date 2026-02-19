<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            // Relación con la empresa / ubicación
            $table->foreignId('company_location_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Empresa (user)
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // ✅ CREW (NUEVO)
            $table->foreignId('crew_id')
                  ->nullable()
                  ->constrained('crews')
                  ->nullOnDelete();


            // Información del cliente
            $table->string('customer_email')->nullable();
            $table->string('bill_to')->nullable();
            $table->string('address')->nullable(); 


            // Datos de la factura
            $table->string('invoice_number')->unique();
            $table->string('terms')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();

            // 🆕 NUEVOS CAMPOS
            $table->text('memo')->nullable();
            $table->text('notes')->nullable();

            // Montos
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // Estado
            $table->enum('status', ['draft', 'sent', 'paid'])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
