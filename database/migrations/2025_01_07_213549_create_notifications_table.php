<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('description'); // وصف النشاط
            $table->unsignedBigInteger('user_id')->nullable(); // المستخدم المرتبط بالنشاط
            $table->string('type_name'); // نوع النشاط (مثل "عضوية جديدة")
            $table->string('type_color'); // لون النوع
            $table->string('status_name'); // حالة النشاط (مثل "مكتمل")
            $table->string('status_color'); // لون الحالة
            $table->timestamps(); // تاريخ الإنشاء والتحديث

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
