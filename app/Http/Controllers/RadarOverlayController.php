<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class RadarOverlayController extends Controller
{
    public function getLatestRadar()
    {
        // 1. Ambil berkas dari folder storage/app/public/radar
        $files = Storage::disk('public')->files('radar');

        $radarFiles = array_filter($files, function($file) {
            $fileName = pathinfo($file, PATHINFO_BASENAME);
            return pathinfo($file, PATHINFO_EXTENSION) === 'png' && str_starts_with($fileName, 'JAY-');
        });

        if (empty($radarFiles)) {
            $transparentPixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
            return response($transparentPixel, 200)->header('Content-Type', 'image/png');
        }

        usort($radarFiles, function($a, $b) {
            return Storage::disk('public')->lastModified($b) <=> Storage::disk('public')->lastModified($a);
        });

        // Ambil file urutan teratas (terbaru)
        $latestFile = storage_path('app/public/' . $radarFiles[0]);

        $sourceImage = imagecreatefrompng($latestFile);
        if (!$sourceImage) {
            return response()->file($latestFile);
        }

        $width = imagesx($sourceImage);
        $height = imagesy($sourceImage);

        // 2. Siapkan kanvas baru dengan transparansi penuh
        $cleanImage = imagecreatetruecolor($width, $height);
        imagealphablending($cleanImage, false);
        imagesavealpha($cleanImage, true);
        
        $transparentColor = imagecolorallocatealpha($cleanImage, 0, 0, 0, 127);
        imagefill($cleanImage, 0, 0, $transparentColor);

        // 3. PROSES FILTER ISOLASI WARNA AWAN
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                
                $rgb = imagecolorat($sourceImage, $x, $y);
                $colors = imagecolorsforindex($sourceImage, $rgb);
                
                $r = $colors['red'];
                $g = $colors['green'];
                $b = $colors['blue'];
                $alpha = $colors['alpha'];

                // Jika piksel bawaan sudah transparan, lewatkan
                if ($alpha >= 120) continue;

                // Cek apakah warnanya masuk kategori bintik pasir (putih, abu-abu, atau warna pudar)
                // Piksel semut pasir biasanya memiliki nilai R, G, B yang sangat berdekatan (monoton)
                $isNoiseColor = (abs($r - $g) < 25 && abs($g - $b) < 25 && abs($r - $b) < 25);
                
                // Tambahan: Hapus jika piksel terlalu terang mendekati putih/abu-abu langit laut
                if ($r > 150 && $g > 150 && $b > 150) {
                    $isNoiseColor = true;
                }

                // KUNCI: Hanya loloskan warna awan radar asli (kontras warna tinggi seperti Hijau/Kuning/Merah)
                if (!$isNoiseColor) {
                    imagesetpixel($cleanImage, $x, $y, $rgb);
                }
            }
        }

        // 4. Output gambar bersih ke memory buffer
        ob_start();
        imagepng($cleanImage);
        $imageData = ob_get_clean();

        imagedestroy($sourceImage);
        imagedestroy($cleanImage);

        return response($imageData, 200)->header('Content-Type', 'image/png');
    }

    // === 2. FUNGSI BARU UNTUK RADAR BMKG SIDARMA ===
    // === FUNGSI REVISI: MEMBERSIHKAN TOTAL BASEMAP & RETRIEVER BMKG SIDARMA ===
    // === FUNGSI REVISI: MEMBERSIHKAN TOTAL BASEMAP & RETRIEVER BMKG SIDARMA ===
    // === FUNGSI REVISI FINAL: ISOLASI SPEKTRUM WARNA RADAR UTK CITRA CMAX MURNI ===
    // === FUNGSI REVISI FINAL: MEMBERSIHKAN TOTAL BASEMAP, LOGO & LEGENDA BMKG ===
    // === FUNGSI REVISI FINAL: ELIMINASI MASIF AREA LEGENDA & LOGO BMKG ===
    // === FUNGSI REVISI FINAL: PENYAPUAN BERSIH SISA PITA LEGENDA dBZ ===
    public function getSidarmaRadar()
    {
        try {
            // Ambil gambar langsung dari server BMKG Sidarma
            $url = "https://cuaca.bmkg.go.id/data/public/sidarma/IMAGE/CMAX_Jayapura.png";
            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                return $this->returnTransparentPixel();
            }

            // Muat gambar biner hasil download ke GD library
            $sourceImage = imagecreatefromstring($response->body());
            if (!$sourceImage) {
                return $this->returnTransparentPixel();
            }

            $width = imagesx($sourceImage);
            $height = imagesy($sourceImage);

            // Gunakan ukuran lebar utuh agar proyeksi peta di Leaflet tetap presisi 100%
            $cleanImage = imagecreatetruecolor($width, $height);
            imagealphablending($cleanImage, false);
            imagesavealpha($cleanImage, true);
            
            $transparentColor = imagecolorallocatealpha($cleanImage, 0, 0, 0, 127);
            imagefill($cleanImage, 0, 0, $transparentColor);

            // UPDATE PERLUASAN AREA PROTEKSI (ZONA TRANSPARAN MUTLAK):
            // Kita geser batas pembersihan ke kiri sejauh 130 piksel dari ujung kanan gambar asli.
            // Langkah ini menjamin seluruh variasi posisi pita warna vertikal dBZ akan terhapus total.
            $forbiddenZoneX = $width - 130;

            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    
                    // KUNCI UTAMA: Jika piksel berada di zona terlarang (area legenda), langsung buang!
                    if ($x >= $forbiddenZoneX) {
                        continue;
                    }

                    $rgb = imagecolorat($sourceImage, $x, $y);
                    $colors = imagecolorsforindex($sourceImage, $rgb);
                    
                    $r = $colors['red'];
                    $g = $colors['green'];
                    $b = $colors['blue'];
                    $alpha = $colors['alpha'];

                    // Jika dari sananya sudah transparan, lewatkan
                    if ($alpha >= 120) continue;

                    // ------------------------------------------------------------------
                    // LOGIKA ISOLASI SPEKTRUM WARNA RADAR BMKG (CMAX)
                    // ------------------------------------------------------------------
                    $isRadarCloud = false;

                    // A. Spektrum Biru Tua hingga Light Cyan (dBZ 5 - 20)
                    if ($b > 165 && $r < 110) {
                        $isRadarCloud = true;
                    }
                    
                    // B. Spektrum Hijau Tua hingga Hijau Muda (dBZ 20 - 35)
                    if ($g > 135 && $r < 135 && $b < 135) {
                        $isRadarCloud = true;
                    }

                    // C. Spektrum Kuning - Jingga - Merah (dBZ 35 - 50)
                    if ($r > 165 && $b < 100) {
                        if (($g > 175 && $r > 200) || $g < 105) {
                            $isRadarCloud = true;
                        }
                    }

                    // D. Spektrum Ungu - Magenta - Pink (dBZ > 50)
                    if ($r > 135 && $b > 135 && $g < 110) {
                        $isRadarCloud = true;
                    }

                    // JIKA PIKSEL ADALAH AWAN RADAR ASLI, COPY KE KANVAS BARU
                    if ($isRadarCloud) {
                        imagesetpixel($cleanImage, $x, $y, $rgb);
                    }
                }
            }

            ob_start();
            imagepng($cleanImage);
            $imageData = ob_get_clean();

            imagedestroy($sourceImage);
            imagedestroy($cleanImage);

            return response($imageData, 200)->header('Content-Type', 'image/png');

        } catch (\Exception $e) {
            return $this->returnTransparentPixel();
        }
    }

    // Helper jika gambar gagal diproses/didownload
    private function returnTransparentPixel()
    {
        $transparentPixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
        return response($transparentPixel, 200)->header('Content-Type', 'image/png');
    }

    // === RADAR BMKG SIDARMA BIAK ===
    public function getBiakRadar()
    {
        try {
            $url = "https://cuaca.bmkg.go.id/data/public/sidarma/IMAGE/CMAX_Biak.png";
            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                return $this->returnTransparentPixel();
            }

            return $this->processSidarmaImage($response->body());
        } catch (\Exception $e) {
            return $this->returnTransparentPixel();
        }
    }

    // === RADAR BMKG SIDARMA WAMENA ===
    public function getWamenaRadar()
    {
        try {
            $url = "https://cuaca.bmkg.go.id/data/public/sidarma/IMAGE/CMAX_Wamena.png";
            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                return $this->returnTransparentPixel();
            }

            return $this->processSidarmaImage($response->body());
        } catch (\Exception $e) {
            return $this->returnTransparentPixel();
        }
    }

    // === RADAR BMKG SIDARMA TIMIKA ===
    public function getTimikaRadar()
    {
        try {
            $url = "https://cuaca.bmkg.go.id/data/public/sidarma/IMAGE/CMAX_Timika.png";
            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                return $this->returnTransparentPixel();
            }

            return $this->processSidarmaImage($response->body());
        } catch (\Exception $e) {
            return $this->returnTransparentPixel();
        }
    }

    // === RADAR BMKG SIDARMA MERAUKE ===
    public function getMeraukeRadar()
    {
        try {
            $url = "https://cuaca.bmkg.go.id/data/public/sidarma/IMAGE/CMAX_Merauke.png";
            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                return $this->returnTransparentPixel();
            }

            return $this->processSidarmaImage($response->body());
        } catch (\Exception $e) {
            return $this->returnTransparentPixel();
        }
    }

    // === RADAR BMKG SIDARMA SORONG ===
    public function getSorongRadar()
    {
        try {
            $url = "https://cuaca.bmkg.go.id/data/public/sidarma/IMAGE/CMAX_Sorong.png";
            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                return $this->returnTransparentPixel();
            }

            return $this->processSidarmaImage($response->body());
        } catch (\Exception $e) {
            return $this->returnTransparentPixel();
        }
    }

    // Helper Refactoring: Memisahkan logika pembersihan gambar agar kode tidak duplikat
    // Tambahkan tipe data string secara eksplisit pada parameter $imageBinary
    private function processSidarmaImage(string $imageBinary)
    {
        $sourceImage = imagecreatefromstring($imageBinary);
        if (!$sourceImage) {
            return $this->returnTransparentPixel();
        }

        $width = imagesx($sourceImage);
        $height = imagesy($sourceImage);

        $cleanImage = imagecreatetruecolor($width, $height);
        imagealphablending($cleanImage, false);
        imagesavealpha($cleanImage, true);
        
        $transparentColor = imagecolorallocatealpha($cleanImage, 0, 0, 0, 127);
        imagefill($cleanImage, 0, 0, $transparentColor);

        $forbiddenZoneX = $width - 130;

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                if ($x >= $forbiddenZoneX) continue;

                $rgb = imagecolorat($sourceImage, $x, $y);
                $colors = imagecolorsforindex($sourceImage, $rgb);
                
                $r = $colors['red'];
                $g = $colors['green'];
                $b = $colors['blue'];
                $alpha = $colors['alpha'];

                if ($alpha >= 120) continue;

                $isRadarCloud = false;

                if ($b > 165 && $r < 110) $isRadarCloud = true;
                if ($g > 135 && $r < 135 && $b < 135) $isRadarCloud = true;
                if ($r > 165 && $b < 100) {
                    if (($g > 175 && $r > 200) || $g < 105) $isRadarCloud = true;
                }
                if ($r > 135 && $b > 135 && $g < 110) $isRadarCloud = true;

                if ($isRadarCloud) {
                    imagesetpixel($cleanImage, $x, $y, $rgb);
                }
            }
        }

        ob_start();
        imagepng($cleanImage);
        $imageData = ob_get_clean();

        imagedestroy($sourceImage);
        imagedestroy($cleanImage);

        return response($imageData, 200)->header('Content-Type', 'image/png');
    }

    // === PROXY AMAN UNTUK CITRA SATELIT ANIMASI BMKG REGION 5 ===
    public function getSatelliteAnimation()
    {
        try {
            $url = "http://202.90.198.22/IMAGE/ANIMASI/H08_EH_Region5_m18.gif";
            // Tarik data animasi gif dari server eksternal
            $response = Http::timeout(12)->get($url);

            if ($response->successful()) {
                return response($response->body(), 200)
                         ->header('Content-Type', 'image/gif');
            }
            
            return $this->returnTransparentPixel(); //
        } catch (\Exception $e) {
            return $this->returnTransparentPixel(); //
        }
    }

    public function getAircraftData()
    {
        try {
            // 1. Ambil data mentah dari server FR24 lokal
            $response = Http::timeout(3)->get('http://172.21.81.63/flights.js');

            if ($response->successful()) {
                $rawContent = trim($response->body());

                // 2. Potong string manual berbasis posisi kurung buka pertama dan kurung tutup terakhir
                $startPos = strpos($rawContent, '(');
                $endPos = strrpos($rawContent, ')');

                if ($startPos !== false && $endPos !== false) {
                    $cleanJsonText = substr($rawContent, $startPos + 1, $endPos - $startPos - 1);
                    $cleanJsonText = trim($cleanJsonText);

                    // --- PROSES SANITASI JSON NON-STANDAR (Mencegah SyntaxError di Browser) ---
                    // Mengubah koma beruntun (,,) menjadi standar (, "",) agar tidak merusak parser JSON
                    $cleanJsonText = preg_replace('/,(\s*),/', ', "", ', $cleanJsonText);
                    $cleanJsonText = preg_replace('/,(\s*),/', ', "", ', $cleanJsonText); // Ekstra repetisi untuk struktur triple koma
                    $cleanJsonText = preg_replace('/:(\s*),/', ': "", ', $cleanJsonText);
                    $cleanJsonText = preg_replace('/\[(\s*),/', '[ "", ', $cleanJsonText);
                    // --------------------------------------------------------------------------

                    // Validasi apakah hasil potongan dan pembersihan sudah valid JSON
                    $dataArray = json_decode($cleanJsonText, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return response()->json($dataArray, 200);
                    }

                    // Jika decode objek asosiatif masih gagal, kita bungkus sebagai teks aplikasi JSON yang aman
                    return response($cleanJsonText, 200)->header('Content-Type', 'application/json');
                }

                // Jika data dari awal sudah berupa JSON murni
                $directJson = json_decode($rawContent, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return response()->json($directJson, 200);
                }

                return response()->json(['error' => 'Gagal memproses struktur string callback'], 500);
            }

            return response()->json(['error' => 'Gagal mengambil data dari receiver ADSB'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Koneksi ke server ADSB terputus: ' . $e->getMessage()], 500);
        }
    }
}