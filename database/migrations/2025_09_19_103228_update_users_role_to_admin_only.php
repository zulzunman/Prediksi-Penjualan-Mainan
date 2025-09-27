<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateUsersRoleToAdminOnly extends Migration
{
    public function up()
    {
        // Update semua user yang memiliki role 'pemilik' menjadi 'admin'
        DB::table('users')
            ->where('role', 'pemilik')
            ->update(['role' => 'admin']);

        // Ubah enum untuk hanya menerima 'admin'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin') DEFAULT 'admin'");
    }

    public function down()
    {
        // Kembalikan ke kondisi semula (admin, pemilik)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pemilik') DEFAULT 'admin'");
    }
}
