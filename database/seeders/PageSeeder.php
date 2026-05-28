<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug'             => 'about',
                'title'            => 'Dibangun dengan passion, dipakai dengan bangga.',
                'meta_title'       => 'Tentang Kami — Quro Collection',
                'meta_description' => 'Quro Collection adalah brand fashion muslim premium yang menghadirkan koleksi baju koko modern dengan bahan berkualitas tinggi.',
                'content'          => '<p>Quro Collection lahir dari kecintaan terhadap fashion muslim yang modern namun tetap menjaga nilai-nilai kesederhanaan dan keanggunan. Kami percaya bahwa tampil rapi dan elegan adalah bagian dari cara menghargai diri sendiri.</p><p>Setiap produk kami dirancang dengan teliti, menggunakan bahan pilihan yang nyaman dipakai sepanjang hari — dari aktivitas sehari-hari hingga momen spesial bersama keluarga.</p>',
            ],
            [
                'slug'             => 'terms-of-service',
                'title'            => 'Syarat & Ketentuan',
                'meta_title'       => 'Syarat & Ketentuan — Quro Collection',
                'meta_description' => 'Syarat dan ketentuan penggunaan layanan Quro Collection — hak, kewajiban, dan aturan yang berlaku bagi seluruh pengguna.',
                'content'          => '<h2>Penerimaan Syarat</h2><p>Dengan mengakses dan menggunakan situs Quro Collection, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan ini. Jika Anda tidak menyetujui, harap hentikan penggunaan layanan kami. Kami berhak memperbarui syarat ini kapan saja tanpa pemberitahuan sebelumnya.</p><h2>Akun Pengguna</h2><p>Untuk melakukan pembelian, Anda wajib membuat akun dengan informasi yang benar dan terkini. Anda bertanggung jawab atas:</p><ul><li>Kerahasiaan kata sandi dan keamanan akun Anda.</li><li>Seluruh aktivitas yang terjadi di bawah akun Anda.</li><li>Segera memberitahu kami jika terdapat akses tidak sah ke akun Anda.</li></ul><h2>Pemesanan &amp; Pembayaran</h2><p>Setiap pesanan yang Anda lakukan tunduk pada ketersediaan stok dan konfirmasi pembayaran. Hal-hal yang perlu diperhatikan:</p><ul><li>Harga yang tercantum sudah termasuk pajak dan dapat berubah sewaktu-waktu.</li><li>Pembayaran diproses melalui Midtrans dan tunduk pada kebijakan mereka.</li><li>Pesanan yang telah dibayar akan diproses dalam 1x24 jam hari kerja.</li><li>Kami berhak membatalkan pesanan jika terdapat indikasi penipuan atau stok habis.</li></ul><h2>Pengiriman</h2><p>Pengiriman dilakukan melalui jasa kurir pilihan. Estimasi waktu pengiriman bersifat perkiraan dan dapat berbeda tergantung lokasi tujuan dan kondisi di lapangan. Kami tidak bertanggung jawab atas keterlambatan yang disebabkan oleh:</p><ul><li>Bencana alam, cuaca ekstrem, atau kejadian di luar kendali kami.</li><li>Kesalahan alamat yang diberikan oleh pembeli.</li><li>Keterlambatan dari pihak jasa pengiriman.</li></ul><h2>Pengembalian &amp; Refund</h2><p>Pengembalian barang dapat dilakukan dalam kondisi berikut:</p><ul><li>Produk diterima dalam kondisi rusak atau cacat produksi.</li><li>Produk yang diterima tidak sesuai dengan pesanan.</li><li>Permintaan pengembalian diajukan maksimal 2x24 jam setelah produk diterima.</li></ul><p>Refund akan diproses dalam 3-7 hari kerja setelah produk retur diterima dan diverifikasi.</p><h2>Larangan Penggunaan</h2><p>Anda dilarang menggunakan layanan kami untuk:</p><ul><li>Melakukan pemesanan fiktif atau penipuan dengan identitas palsu.</li><li>Mencoba mengakses sistem kami secara tidak sah.</li><li>Aktivitas yang melanggar hukum yang berlaku di Indonesia.</li></ul><p>Pelanggaran terhadap ketentuan ini dapat mengakibatkan penangguhan atau penghapusan akun Anda.</p><h2>Hukum yang Berlaku</h2><p>Syarat dan ketentuan ini diatur berdasarkan hukum Republik Indonesia. Setiap sengketa yang timbul akan diselesaikan secara musyawarah. Apabila tidak tercapai kesepakatan, penyelesaian akan dilakukan melalui jalur hukum yang berlaku di Indonesia.</p>',
            ],
            [
                'slug'             => 'privacy-policy',
                'title'            => 'Kebijakan Privasi',
                'meta_title'       => 'Kebijakan Privasi — Quro Collection',
                'meta_description' => 'Kebijakan privasi Quro Collection — bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.',
                'content'          => '<h2>Informasi yang Kami Kumpulkan</h2><p>Saat Anda menggunakan layanan Quro Collection, kami dapat mengumpulkan informasi berikut:</p><ul><li>Informasi akun: nama, alamat email, nomor telepon.</li><li>Informasi pengiriman: alamat lengkap, kota, provinsi, kode pos.</li><li>Informasi transaksi: riwayat pembelian dan metode pembayaran.</li><li>Data teknis: alamat IP, jenis browser, dan halaman yang dikunjungi.</li></ul><h2>Cara Kami Menggunakan Informasi</h2><p>Informasi yang kami kumpulkan digunakan untuk:</p><ul><li>Memproses dan mengantarkan pesanan Anda.</li><li>Mengirimkan konfirmasi pesanan dan pembaruan pengiriman.</li><li>Meningkatkan pengalaman belanja dan layanan kami.</li><li>Mencegah penipuan dan menjaga keamanan platform.</li><li>Mengirimkan promosi dan penawaran (hanya jika Anda menyetujui).</li></ul><h2>Berbagi Informasi</h2><p>Kami tidak menjual atau menyewakan informasi pribadi Anda kepada pihak ketiga. Informasi Anda hanya dibagikan kepada:</p><ul><li>Jasa pengiriman untuk keperluan pengantaran paket.</li><li>Penyedia layanan pembayaran (Midtrans) untuk memproses transaksi.</li><li>Pihak berwenang jika diwajibkan oleh hukum.</li></ul><h2>Keamanan Data</h2><p>Kami menerapkan langkah-langkah keamanan teknis dan organisasi yang tepat untuk melindungi informasi pribadi Anda dari akses, pengungkapan, perubahan, atau penghancuran yang tidak sah. Seluruh transaksi dienkripsi menggunakan teknologi SSL.</p><h2>Cookies</h2><p>Situs kami menggunakan cookies untuk meningkatkan pengalaman pengguna. Cookies digunakan untuk:</p><ul><li>Menyimpan preferensi dan sesi login Anda.</li><li>Menganalisis trafik dan penggunaan situs (Google Analytics).</li><li>Menampilkan konten yang relevan.</li></ul><p>Anda dapat menonaktifkan cookies melalui pengaturan browser, namun beberapa fitur situs mungkin tidak berfungsi optimal.</p><h2>Hak Anda</h2><p>Anda memiliki hak untuk:</p><ul><li>Mengakses informasi pribadi yang kami simpan tentang Anda.</li><li>Meminta koreksi informasi yang tidak akurat.</li><li>Meminta penghapusan akun dan data pribadi Anda.</li><li>Berhenti menerima komunikasi pemasaran kapan saja.</li></ul><p>Untuk mengajukan permintaan tersebut, hubungi kami melalui email yang tertera di bawah.</p><h2>Perubahan Kebijakan</h2><p>Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu. Perubahan signifikan akan diberitahukan melalui email atau notifikasi di situs kami. Penggunaan layanan kami setelah pembaruan berarti Anda menyetujui kebijakan yang telah diperbarui.</p>',
            ],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
