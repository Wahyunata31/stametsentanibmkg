<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        // 1. Definisikan daftar wilayah (ADM4)
        $wilayahList = [
            // --- KABUPATEN JAYAPURA ---
            // Distrik Airu
            '91.03.16.2006' => 'Naira, Airu (Kab. Jayapura)',
            '91.03.16.2005' => 'Kamikaru, Airu (Kab. Jayapura)',
            '91.03.16.2004' => 'Muara Nawa, Airu (Kab. Jayapura)',
            '91.03.16.2003' => 'Aurina, Airu (Kab. Jayapura)',
            '91.03.16.2002' => 'Pagai, Airu (Kab. Jayapura)',
            '91.03.16.2001' => 'Hulu Atas, Airu (Kab. Jayapura)',

            // Distrik Demta
            '91.03.10.2001' => 'Demta, Demta (Kab. Jayapura)',
            '91.03.10.2003' => 'Yougapsa, Demta (Kab. Jayapura)',
            '91.03.10.2002' => 'Ambora, Demta (Kab. Jayapura)',
            '91.03.10.2006' => 'Kamdera, Demta (Kab. Jayapura)',
            '91.03.10.2007' => 'Muaif, Demta (Kab. Jayapura)',
            '91.03.10.2004' => 'Muris Kecil, Demta (Kab. Jayapura)',
            '91.03.10.2005' => 'Yakore, Demta (Kab. Jayapura)',

            // Sentani
            '91.03.01.2006' => 'Sentani Kota, Sentani (Kab. Jayapura)',
            '91.03.01.2002' => 'Hinekombe, Sentani (Kab. Jayapura)',
            '91.03.01.1002' => 'Dobonsolo, Sentani (Kab. Jayapura)',
            '91.03.01.2011' => 'Hobong, Sentani (Kab. Jayapura)',
            '91.03.01.3010' => 'Ifar Besar, Sentani (Kab. Jayapura)',
            '91.03.01.3007' => 'Keheran, Sentani (Kab. Jayapura)',
            '91.03.01.2004' => 'Sereh, Sentani (Kab. Jayapura)',
            '91.03.01.2017' => 'Yahim, Sentani (Kab. Jayapura)',
            '91.03.01.2005' => 'Yobeh, Sentani (Kab. Jayapura)',

            //Sentani Timur
            '91.03.02.2004' => 'Asei Besar, Sentani Timur (Kab. Jayapura)',
            '91.03.02.3005' => 'Asei Kecil, Sentani Timur (Kab. Jayapura)',
            '91.03.02.3003' => 'Ayapo, Sentani Timur (Kab. Jayapura)',
            '91.03.02.2006' => 'Nendali, Sentani Timur (Kab. Jayapura)',
            '91.03.02.2001' => 'Nolokla, Sentani Timur (Kab. Jayapura)',
            '91.03.02.2002' => 'Asei Besar, Sentani Timur (Kab. Jayapura)',
            '91.03.02.3007' => 'Yokiwa, Sentani Timur (Kab. Jayapura)',

            // Sentani Barat
            '91.03.04.2001' => 'Dosay, Sentani Barat (Kab. Jayapura)',
            '91.03.04.2005' => 'Maribu, Sentani Barat (Kab. Jayapura)',
            '91.03.04.2011' => 'Sabron Yaru, Sentani Barat (Kab. Jayapura)',
            '91.03.04.2008' => 'Sabron Sari, Sentani Barat (Kab. Jayapura)',
            '91.03.04.3006' => 'Waibon, Sentani Barat (Kab. Jayapura)',
            
            //Kemtuk
            '91.03.05.2006' => 'Kwansu, Kemtuk (Kab. Jayapura)',
            '91.03.05.2003' => 'Mamda, Kemtuk (Kab. Jayapura)',
            '91.03.05.2004' => 'Mamei, Kemtuk (Kab. Jayapura)',
            '91.03.05.2002' => 'Mamda Yawan, Kemtuk (Kab. Jayapura)',
            '91.03.05.2005' => 'Nanbom, Kemtuk (Kab. Jayapura)',
            '91.03.05.2008' => 'Sabeab Kecil, Kemtuk (Kab. Jayapura)',
            '91.03.05.2001' => 'Sama, Kemtuk (Kab. Jayapura)',
            '91.03.05.2009' => 'Sekori, Kemtuk (Kab. Jayapura)',
            '91.03.05.2010' => 'Skoaim, Kemtuk (Kab. Jayapura)',
            '91.03.05.2007' => 'Soaib, Kemtuk (Kab. Jayapura)',

            //Kemtuk Gresi
            '91.03.06.1015' => 'Hatib, Kemtuk Gresi (Kab. Jayapura)',
            '91.03.06.2004' => 'Braso, Kemtuk Gresi (Kab. Jayapura)',
            '91.03.06.2006' => 'Bring, Kemtuk Gresi (Kab. Jayapura)',
            '91.03.06.2002' => 'Dementin, Kemtuk Gresi (Kab. Jayapura)',
            '91.03.06.2001' => 'Domoikati, Kemtuk Gresi (Kab. Jayapura)',
            '91.03.06.2009' => 'Ibub, Kemtuk Gresi (Kab. Jayapura)',
            '91.03.06.2014' => 'Jagrang, Kemtuk Gresi (Kab. Jayapura)',
            '91.03.06.2007' => 'Nembugresi, Kemtuk Gresi (Kab. Jayapura)',
            '91.03.06.2005' => 'Pupehabu, Kemtuk Gresi (Kab. Jayapura)',
            '91.03.06.2013' => 'Swentab, Kemtuk Gresi (Kab. Jayapura)',
            '91.03.06.2003' => 'Yanbra, Kemtuk Gresi (Kab. Jayapura)',

            //Nimboran
            '91.03.07.1021' => 'Tabri, Nimboran (Kab. Jayapura)',
            '91.03.07.2004' => 'Benyom, Nimboran (Kab. Jayapura)',
            '91.03.07.2001' => 'Gemebs, Nimboran (Kab. Jayapura)',
            '91.03.07.2009' => 'Imsar, Nimboran (Kab. Jayapura)',
            '91.03.07.3018' => 'Kaitemung, Nimboran (Kab. Jayapura)',
            '91.03.07.2010' => 'Kuipons, Nimboran (Kab. Jayapura)',
            '91.03.07.2017' => 'Kuwase, Nimboran (Kab. Jayapura)',
            '91.03.07.2003' => 'Meyu, Nimboran (Kab. Jayapura)',
            '91.03.07.2005' => 'Oyengsi, Nimboran (Kab. Jayapura)',
            '91.03.07.2019' => 'Pobaim, Nimboran (Kab. Jayapura)',
            '91.03.07.2002' => 'Singgri, Nimboran (Kab. Jayapura)',
            '91.03.07.2006' => 'Singgriway, Nimboran (Kab. Jayapura)',
            '91.03.07.2014' => 'Yenggu Baru, Nimboran (Kab. Jayapura)',
            '91.03.07.2015' => 'Yenggu Lama, Nimboran (Kab. Jayapura)',

            //Kaureh
            '91.03.11.2001' => 'Lapua, Kaureh (Kab. Jayapura)',
            '91.03.11.2002' => 'Sebum, Kaureh (Kab. Jayapura)',
            '91.03.11.2003' => 'Soskotek, Kaureh (Kab. Jayapura)',
            '91.03.11.2006' => 'Umbron, Kaureh (Kab. Jayapura)',
            '91.03.11.2004' => 'Yadauw, Kaureh (Kab. Jayapura)',

            // Distrik Depapre
            '91.03.03.2002' => 'Entiyebo, Depapre (Kab. Jayapura)',
            '91.03.03.2003' => 'Kendate, Depapre (Kab. Jayapura)',
            '91.03.03.2004' => 'Tablasupa, Depapre (Kab. Jayapura)',
            '91.03.03.2001' => 'Waiya, Depapre (Kab. Jayapura)',
            '91.03.03.2006' => 'Wambena, Depapre (Kab. Jayapura)',
            '91.03.03.2005' => 'Yepase, Depapre (Kab. Jayapura)',
            '91.03.03.2007' => 'Yewena, Depapre (Kab. Jayapura)',

            //Distrik Nimbokrang
            '91.03.08.2002' => 'Benyom Jaya I, Nimbokrang (Kab. Jayapura)',
            '91.03.08.2003' => 'Benyom Jaya II, Nimbokrang (Kab. Jayapura)',
            '91.03.08.2004' => 'Berab, Nimbokrang (Kab. Jayapura)',
            '91.03.08.2009' => 'Bunyom, Nimbokrang (Kab. Jayapura)',
            '91.03.08.2005' => 'Hamograng, Nimbokrang (Kab. Jayapura)',
            '91.03.08.2001' => 'Nimbokrang, Nimbokrang (Kab. Jayapura)',
            '91.03.08.2007' => 'Nombukrang Sari, Nimbokrang (Kab. Jayapura)',
            '91.03.08.2008' => 'Rhepang Muaif, Nimbokrang (Kab. Jayapura)',
            '91.03.08.2006' => 'Wahab, Nimbokrang (Kab. Jayapura)',

            //Unurum Guay
            '91.03.09.2001' => 'Beneik, Unurum Guay (Kab. Jayapura)',
            '91.03.09.2002' => 'Ganusa, Unurum Guay (Kab. Jayapura)',
            '91.03.09.2003' => 'Guriyad, Unurum Guay (Kab. Jayapura)',
            '91.03.09.2004' => 'Santosa, Unurum Guay (Kab. Jayapura)',
            '91.03.09.2005' => 'Sawa Suma, Unurum Guay (Kab. Jayapura)',

            //Ebungfao
            '91.03.12.2002' => 'Atabar, Ebungfao (Kab. Jayapura)',
            '91.03.12.3003' => 'Babrangko, Ebungfao (Kab. Jayapura)',
            '91.03.12.2001' => 'Ebungfa, Ebungfao (Kab. Jayapura)',
            '91.03.12.2004' => 'Khameyaka, Ebungfao (Kab. Jayapura)',
            '91.03.12.3005' => 'Simporo, Ebungfao (Kab. Jayapura)',

            //Distrik Waibu
            '91.03.13.3001' => 'Donday, Waibu (Kab. Jayapura)',
            '91.03.13.2006' => 'Doyo Baru, Waibu (Kab. Jayapura)',
            '91.03.13.2002' => 'Doyo Lama, Waibu (Kab. Jayapura)',
            '91.03.13.2003' => 'Kwadeware, Waibu (Kab. Jayapura)',
            '91.03.13.2005' => 'Sosiri, Waibu (Kab. Jayapura)',
            '91.03.13.2004' => 'Yakonde, Waibu (Kab. Jayapura)',

            //Distrik Nambluong
            '91.03.14.2005' => 'Besum, Nambluong (Kab. Jayapura)',
            '91.03.14.2007' => 'Imestum, Nambluong (Kab. Jayapura)',
            '91.03.14.2008' => 'Karya Bumi, Nambluong (Kab. Jayapura)',
            '91.03.14.2003' => 'Sanggai, Nambluong (Kab. Jayapura)',
            '91.03.14.2002' => 'Sermai Bawah, Nambluong (Kab. Jayapura)',
            '91.03.14.2004' => 'Yakasib, Nambluong (Kab. Jayapura)',

            //Yapsi
            '91.03.15.2004' => 'Bumi Sahaja, Yapsi (Kab. Jayapura)',
            '91.03.15.2002' => 'Kwarja, Yapsi (Kab. Jayapura)',
            '91.03.15.2006' => 'Nawa Mukti, Yapsi (Kab. Jayapura)',
            '91.03.15.2005' => 'Nawa Mulya, Yapsi (Kab. Jayapura)',
            '91.03.15.2003' => 'Ongan Jaya, Yapsi (Kab. Jayapura)',
            '91.03.15.2008' => 'Purnama Jati, Yapsi (Kab. Jayapura)',
            '91.03.15.2001' => 'Tabeyan, Yapsi (Kab. Jayapura)',
            '91.03.15.2007' => 'Taqwa Bangun, Yapsi (Kab. Jayapura)',

            //Ravenirara
            '91.03.17.3004' => 'Nehibe, Raveni Rara (Kab. Jayapura)',
            '91.03.17.2003' => 'Newa, Raveni Rara (Kab. Jayapura)',
            '91.03.17.2002' => 'Yongsu Dosoyo, Raveni Rara (Kab. Jayapura)',
            '91.03.17.2001' => 'Yongsu Sapari, Raveni Rara (Kab. Jayapura)',

            //Gresi Selatan
            '91.03.18.2004' => 'Bangai, Gresi Selatan (Kab. Jayapura)',
            '91.03.18.3002' => 'Iwon, Gresi Selatan (Kab. Jayapura)',
            '91.03.18.2003' => 'Klaisu, Gresi Selatan (Kab. Jayapura)',
            '91.03.18.2001' => 'Omon, Gresi Selatan (Kab. Jayapura)',

            //Distrik Yokari
            '91.03.19.2005' => 'Buseryo,  Yokari (Kab. Jayapura)',
            '91.03.19.2003' => 'Endokisi,  Yokari (Kab. Jayapura)',
            '91.03.19.2001' => 'Maruwai,  Yokari (Kab. Jayapura)',
            '91.03.19.2002' => 'Meukisi,  Yokari (Kab. Jayapura)',
            '91.03.19.2006' => 'Senamay,  Yokari (Kab. Jayapura)',

            // --- KOTA JAYAPURA ---
            //Jayapura Utara
            '91.71.01.2008' => 'Kayo Batu, Jayapura Utara (Kota Jayapura)',
            '91.71.01.1007' => 'Angkasapura, Jayapura Utara (Kota Jayapura)',
            '91.71.01.1002' => 'Bayangkara, Jayapura Utara (Kota Jayapura)',
            '91.71.01.1001' => 'Gurabesi, Jayapura Utara (Kota Jayapura)',
            '91.71.01.1004' => 'Imbi, Jayapura Utara (Kota Jayapura)',
            '91.71.01.1006' => 'Mandala, Jayapura Utara (Kota Jayapura)',
            '91.71.01.1005' => 'Tanjung Ria, Jayapura Utara (Kota Jayapura)',
            '91.71.01.1003' => 'Trikora, Jayapura Utara (Kota Jayapura)',

            //Jayapura Selatan
            '91.71.02.1003' => 'Numbay, Jayapura Selatan (Kota Jayapura)',
            '91.71.02.1006' => 'Hamadi, Jayapura Selatan (Kota Jayapura)',
            '91.71.02.1005' => 'Entrop, Jayapura Selatan (Kota Jayapura)',
            '91.71.02.1001' => 'Argapura, Jayapura Selatan (Kota Jayapura)',
            '91.71.02.1002' => 'Ardipura, Jayapura Selatan (Kota Jayapura)',
            '91.71.02.2007' => 'Tahima Soroma, Jayapura Selatan (Kota Jayapura)',
            '91.71.02.2008' => 'Tobati, Jayapura Selatan (Kota Jayapura)',

            //Abepura
            '91.71.03.1011' => 'Abepantai, Abepura (Kota Jayapura)',
            '91.71.03.1002' => 'Asano, Abepura (Kota Jayapura)',
            '91.71.03.1008' => 'Abepantai, Abepura (Kota Jayapura)',
            '91.71.03.1012' => 'Kotabaru, Abepura (Kota Jayapura)',
            '91.71.03.1014' => 'Vim, Abepura (Kota Jayapura)',
            '91.71.03.1015' => 'Wahno, Abepura (Kota Jayapura)',
            '91.71.03.1016' => 'Way Mhorock, Abepura (Kota Jayapura)',
            '91.71.03.2004' => 'Enggros, Abepura (Kota Jayapura)',
            '91.71.03.2007' => 'Koya Koso, Abepura (Kota Jayapura)',
            '91.71.03.2001' => 'Nafri, Abepura (Kota Jayapura)',

            //Muara Tami
            '91.71.04.1004' => 'Koya Barat, Muara Tami (Kota Jayapura)',
            '91.71.04.1005' => 'Koya Timur, Muara Tami (Kota Jayapura)',
            '91.71.04.2006' => 'Holtekamp, Muara Tami (Kota Jayapura)',
            '91.71.04.2007' => 'Koya Tengah, Muara Tami (Kota Jayapura)',
            '91.71.04.2008' => 'Mosso, Muara Tami (Kota Jayapura)',
            '91.71.04.2001' => 'Skouw Mabo, Muara Tami (Kota Jayapura)',
            '91.71.04.2003' => 'Skouw Sae, Muara Tami (Kota Jayapura)',
            '91.71.04.2002' => 'Skouw Yambe, Muara Tami (Kota Jayapura)',

            //Heram
            '91.71.05.1001' => 'Hedam, Heram (Kota Jayapura)',
            '91.71.05.1002' => 'Waena, Heram (Kota Jayapura)',
            '91.71.05.1004' => 'Yabansai, Heram (Kota Jayapura)',
            '91.71.05.2005' => 'Kampung Waena, Heram (Kota Jayapura)',
            '91.71.05.2003' => 'Yoka, Heram (Kota Jayapura)',





        ];

        // 2. Ambil kode wilayah dari URL (?kode_wilayah=...), default ke Sentani
        $selectedAdm = $request->query('kode_wilayah', '91.03.01.2006');

        $apiUrl = "https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=" . $selectedAdm;
        
        try {
            // bypass SSL agar tidak error di localhost
            $response = Http::withoutVerifying()->timeout(15)->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                
                // 3. KIRIM SEMUA VARIABEL KE VIEW
                return view('cuacaprakiraan', [
                    'data' => $data,
                    'wilayahList' => $wilayahList,
                    'selectedAdm' => $selectedAdm
                ]);
            }
            
            return view('cuacaprakiraan')->with('error', 'Gagal mengambil data dari BMKG.');

        } catch (\Exception $e) {
            return view('cuacaprakiraan')->with('error', 'Koneksi bermasalah: ' . $e->getMessage());
        }
    }
}