<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        // Get statistics based on user role
        if ($user->role == 'admin' || $user->role == 'pemilik') {
            $data['totalBarang'] = Barang::count();
        }

        if ($user->role == 'pemilik') {
            $data['totalPenjualan'] = Penjualan::count();
            $data['totalPendapatan'] = Penjualan::sum('total_harga');
        }

        if ($user->role == 'admin') {
            $data['totalUsers'] = User::count();
        }

        // Get recent activities (you can customize this based on your needs)
        $data['recentActivities'] = $this->getRecentActivities();

        return view('dashboard', $data);
    }

    private function getRecentActivities()
    {
        // Example recent activities - customize based on your application
        return [
            [
                'message' => 'Login berhasil',
                'time' => 'Baru saja',
                'icon' => 'person-check',
                'color' => 'success'
            ],
            [
                'message' => 'Data diperbarui',
                'time' => '2 jam yang lalu',
                'icon' => 'arrow-clockwise',
                'color' => 'info'
            ],
            [
                'message' => 'Backup sistem berhasil',
                'time' => '1 hari yang lalu',
                'icon' => 'shield-check',
                'color' => 'primary'
            ]
        ];
    }
}
