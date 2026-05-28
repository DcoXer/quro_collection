<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Umum
            'site_name'             => 'Quro Collection',
            'site_tagline'          => 'Premium Muslim Fashion',
            'contact_email'         => '',
            'whatsapp_number'       => '',

            // Hero
            'hero_line1'            => 'Tampil',
            'hero_line2_italic'     => 'Elegan',
            'hero_line3'            => 'Setiap Hari',
            'hero_subtext_mobile'   => 'Baju koko premium, desain modern, nyaman dipakai kapan saja.',
            'hero_subtext_desktop'  => 'Koleksi baju koko premium dengan desain modern dan bahan berkualitas tinggi.',

            // Story
            'story_headline'        => 'Menghadirkan Elegansi dalam Setiap Jahitan',
            'story_body_desktop_1'  => 'Quro Collection lahir dari kecintaan terhadap fashion muslim yang modern. Kami percaya bahwa baju koko bukan sekadar pakaian ibadah, tapi juga pernyataan gaya yang bisa dikenakan kapan saja.',
            'story_body_desktop_2'  => 'Setiap produk kami dirancang dengan teliti, menggunakan bahan premium pilihan yang nyaman dipakai sepanjang hari.',
            'story_bullet_1'        => 'Bahan premium, nyaman seharian',
            'story_bullet_2'        => 'Proses & kirim dalam 1×24 jam',
            'story_bullet_3'        => 'Garansi kepuasan setiap pembelian',

            // Values
            'value_1_title'         => 'Kualitas Premium',
            'value_1_desc'          => 'Bahan dipilih dengan ketat untuk kenyamanan dan ketahanan jangka panjang.',
            'value_2_title'         => 'Pengiriman Cepat',
            'value_2_desc'          => 'Pesanan diproses dalam 1×24 jam dan dikirim ke seluruh Indonesia.',
            'value_3_title'         => 'Garansi Kepuasan',
            'value_3_desc'          => 'Kepuasan pelanggan adalah prioritas utama. Garansi untuk setiap pembelian.',

            // CTA
            'cta_headline'          => 'Temukan Koleksi Terbaik untuk Kamu',
            'cta_subtext'           => 'Lebih dari {jumlah} produk siap menemani penampilan elegan kamu sehari-hari.',
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
