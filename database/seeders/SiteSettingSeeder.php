<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Hero Section
            'hero_title' => 'Wujudkan Ruang Impian Anda',
            'hero_subtitle' => 'Solusi desain interior dan arsitektur yang memadukan estetika, fungsionalitas, dan kenyamanan.',
            'hero_cta_text' => 'Konsultasi Sekarang',

            // Why Choose Us
            'why_title' => 'Kenapa Memilih Kami?',
            'why_subtitle' => 'Kami berkomitmen untuk memberikan hasil terbaik melalui layanan yang profesional, kreatif, dan terpercaya.',
            'why_1_icon' => 'bi-award',
            'why_1_title' => 'Profesional & Berpengalaman',
            'why_1_desc' => 'Tim kami terdiri dari para ahli di bidangnya yang siap mewujudkan setiap detail desain Anda dengan presisi.',
            'why_2_icon' => 'bi-lightbulb',
            'why_2_title' => 'Desain Kreatif & Inovatif',
            'why_2_desc' => 'Kami selalu mengikuti tren desain terkini sambil tetap mengedepankan solusi yang unik dan personal untuk Anda.',
            'why_3_icon' => 'bi-shield-check',
            'why_3_title' => 'Andal & Tepat Waktu',
            'why_3_desc' => 'Kepuasan Anda adalah prioritas kami. Kami menjamin transparansi proses dan penyelesaian proyek sesuai jadwal.',

            // Services
            'services_title' => 'Layanan Kami',
            'services_subtitle' => 'Kami menyediakan solusi desain komprehensif untuk memenuhi setiap kebutuhan ruang Anda.',
            'service_1_icon' => 'bi-house-door-fill',
            'service_1_title' => 'Desain Interior Residensial',
            'service_1_desc' => 'Menciptakan hunian yang nyaman, fungsional, dan mencerminkan kepribadian Anda, mulai dari apartemen, rumah tinggal, hingga villa.',
            'service_2_icon' => 'bi-building-fill',
            'service_2_title' => 'Desain Interior Komersial',
            'service_2_desc' => 'Solusi desain untuk ruang komersial seperti kantor, toko ritel, restoran, dan kafe yang dapat meningkatkan citra brand dan kenyamanan pelanggan.',
            'service_3_icon' => 'bi-rulers',
            'service_3_title' => 'Konsultasi & Perencanaan Ruang',
            'service_3_desc' => 'Membantu Anda dalam tahap awal perencanaan, mulai dari layout, pemilihan material, hingga skema warna yang paling sesuai.',
            'service_4_icon' => 'bi-palette-fill',
            'service_4_title' => 'Desain Furnitur Kustom',
            'service_4_desc' => 'Merancang dan membuat furnitur yang dibuat khusus untuk memaksimalkan fungsi dan estetika ruang Anda secara sempurna.',
            'service_5_icon' => 'bi-cone-striped',
            'service_5_title' => 'Manajemen & Pengawasan Proyek',
            'service_5_desc' => 'Mengawasi setiap tahap implementasi desain di lapangan untuk memastikan hasil akhir sesuai dengan rencana dan standar kualitas tertinggi.',

            // FAQ
            'faq_1_question' => 'Layanan apa saja yang Anda sediakan?',
            'faq_1_answer' => 'Kami menyediakan layanan desain interior komprehensif mulai dari konsultasi awal, perencanaan ruang (layout), pemilihan material, desain 3D, pembuatan furnitur kustom, hingga pengawasan implementasi di lapangan.',
            'faq_2_question' => 'Bagaimana proses pemesanannya?',
            'faq_2_answer' => 'Proses dimulai dengan mengisi formulir pemesanan di website kami. Tim kami akan segera menghubungi Anda melalui WhatsApp untuk diskusi awal. Setelah itu, kami akan menjadwalkan survei lokasi dan mempresentasikan proposal desain beserta penawaran biaya.',
            'faq_3_question' => 'Apakah Anda melayani proyek di luar kota?',
            'faq_3_answer' => 'Ya, kami melayani proyek di berbagai kota di Indonesia. Untuk proyek di luar area layanan utama kami (Yogyakarta & Jabodetabek), akan ada penyesuaian biaya untuk akomodasi dan transportasi tim yang akan kami diskusikan secara transparan di awal.',
            'faq_4_question' => 'Berapa lama pengerjaan sebuah proyek?',
            'faq_4_answer' => 'Jangka waktu pengerjaan sangat bervariasi tergantung pada skala dan kompleksitas proyek. Rata-rata, untuk satu ruangan apartemen bisa memakan waktu 1 bulan, sementara untuk satu rumah bisa memakan waktu 5-6 bulan. Estimasi waktu yang lebih akurat akan kami berikan dalam proposal proyek.',

            // Footer
            'company_name' => 'PT. ASTHA TUNGGAL MAKMUR',
            'company_description' => 'Mewujudkan ruang impian Anda dengan sentuhan profesional dan personal. Kami menyediakan solusi desain interior dan arsitektur yang inovatif dan berkualitas.',
            'address_main' => 'Jl. Dusun Pojok No.Rt 01/15 Dero Wetan, Harjobinangun, Kec. Pakem Kabupaten Sleman, Daerah Istimewa Yogyakarta 55582',
            'address_branch' => 'Jalan Bungur, 1 No. 15 Kelurahan Kukusan Depok, Jawa Barat 16425',
            'phone_main' => '+62 812 2993 883',
            'phone_branch' => '+62 813 1198 8070',
            'email' => 'asthattunggalmakmur@gmail.com',
            'social_instagram' => '',
            'social_facebook' => '',
            'social_tiktok' => '',
            'social_youtube' => '',
            'social_whatsapp' => '',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
