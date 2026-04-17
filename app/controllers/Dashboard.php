<?php

class Dashboard extends Controller {

    private function guard() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $this->guard();

        $asetModel = $this->model('Aset_model');

        $data['role'] = $_SESSION['role'] ?? 'User';
        $data['nama'] = $_SESSION['nama_lengkap'] ?? 'User';

        $data['total_aset'] = $asetModel->countAllAset();

        $data['total_medis'] = $asetModel->countByKategori('Medis');
        $data['total_sarpras'] = $asetModel->countByKategori('Sarpras');
        $data['total_it'] = $asetModel->countByKategori('IT');

        $data['baik'] = $asetModel->countByKondisi('Baik');
        $data['rusak_ringan'] = $asetModel->countByKondisi('Rusak Ringan');
        $data['rusak_berat'] = $asetModel->countByKondisi('Rusak Berat');
        $data['maintenance'] = $asetModel->countByKondisi('Maintenance');
        $data['gudang'] = $asetModel->countByKondisi('Gudang');

        $ruanganStats = $asetModel->getAsetPerRuangan(8);

        $data['ruangan_labels'] = array_map(function($item) {
            return $item['nama_ruang'];
        }, $ruanganStats);

        $data['ruangan_totals'] = array_map(function($item) {
            return (int)$item['total_aset'];
        }, $ruanganStats);

        $data['judul'] = 'Dashboard - MedTrack IPSRS';
        $data['page_heading'] = 'Dashboard';
        $data['page_subheading'] = 'Ringkasan data aset dan kondisi alat.';
        $data['content_view'] = 'dashboard/index';

        $this->view('templates/dashboard_layout', $data);
    }
}