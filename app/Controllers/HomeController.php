<?php
namespace App\Controllers;
use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        $divisiModel = new \App\Models\Divisi();
        $divisiAktif = $divisiModel->getAktif();

        ob_start();
        $this->view('public/landing', ['divisiAktif' => $divisiAktif]);
        $content = ob_get_clean();
        
        $this->view('layouts/public', [
            'content' => $content, 
            'title' => 'Portal SIMAGANG Bappeda Provinsi Lampung',
            'currentPage' => 'beranda'
        ]);
    }

    public function alur() {
        ob_start();
        $this->view('public/alur');
        $content = ob_get_clean();
        
        $this->view('layouts/public', [
            'content' => $content, 
            'title' => 'Alur Pengajuan - SIMAGANG Bappeda Provinsi Lampung',
            'currentPage' => 'alur'
        ]);
    }

    public function persyaratan() {
        // Fetch active divisions from database
        $divisiModel = new \App\Models\Divisi();
        $divisiData = $divisiModel->getAktif();

        ob_start();
        $this->view('public/persyaratan', ['divisiData' => $divisiData]);
        $content = ob_get_clean();
        
        $this->view('layouts/public', [
            'content' => $content, 
            'title' => 'Persyaratan Magang - SIMAGANG Bappeda Provinsi Lampung',
            'currentPage' => 'persyaratan'
        ]);
    }

    public function faq() {
        ob_start();
        $this->view('public/faq');
        $content = ob_get_clean();
        
        $this->view('layouts/public', [
            'content' => $content, 
            'title' => 'FAQ - SIMAGANG Bappeda Provinsi Lampung',
            'currentPage' => 'faq'
        ]);
    }
}
