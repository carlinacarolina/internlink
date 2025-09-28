<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Summary</title>
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#2563eb',
                            soft: '#eff6ff',
                            border: '#bfdbfe',
                        },
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
<body class="font-sans h-screen flex flex-col">
    <header class="gap-4 h-40 border-b-4 flex-col border-black pb-3 mb-3">
        <div class="flex justify-between h-full">
            <div class="flex items-center h-full">
                <img src="{{ public_path('images/applications/west-java-logo.png') }}" alt="" class="h-full w-auto max-w-none">
            </div>
            <div class="flex justify-center items-center text-base flex-col flex-1 h-full">
                <p>PEMERINTAH DAERAH PROVINSI JAWAB BARAT</p>
                <p><b>DINAS PENDIDIKAN</b></p>
                <p>CABANG DINAS PENDIDIKAN WILAYAH VII</p>
                <p><b>SEKOLAH MENENGAH KEJURUAN NEGERI 13</b></p>
                <p class="text-sm">Jalan Soekarno Hatta Km. 10 Kel. Jatisari Kec. Buahbatu</p>
                <p class="text-sm">Telepon (022) 7318960 - Bandung 40286 Email: smk13bdg@gmail.com</p>
                <p class="text-sm">Home page: http://www.smkn13.sch.id</p>
            </div>
        </div>
    </header>
    <main class="text-sm">
        <div class="mb-9">
            <p>Nomor: Application/{{ $application->id }}/SMKN13BDG</p>
            <p>Lamp :-</p>
            <p>Hal : Pengajuan Praktik Kerja Lapangan (PKL) dan Uji Kompetensi (Ukom)</p>
        </div>
        <div class="mb-4">
            <p class="text-base"><b>Yth. HRD PT EUREKA MERDEKA INDONESIA (SMKDEV) - EUDEKA</b></p>
            <p>di</p>
            <p>Summarecon Bandung, Jl. Magna Barat Blok MD No.02, Rancabolang, Kec. Gedebage, Kota Bandung, Jawa Barat 40295 </p>
        </div>
        <div class="mb-4">
            <p>Dengan Hormat,</p>
        </div>
        <div class="mb-4">
            <p>Berdasarkan pada kurikulum merdeka, bahwa siswa SMK tingkat akhir (kelas XII) harus melaksanakan Praktik Kerja Lapangan dan Uji Kompetensi (UKOM) sebagai salah satu syarat kelulusan. Oleh karena itu, kami mengajukan permohonan untuk melaksanakan kegiatan uji kompetensi bagi siswa kami sebagai berikut:</p>
        </div>
        <div class="mb-4 flex flex-col items-center">
            <table class="mb-4">
                <tr>
                    <th class="text-left font-normal">Kompetensi Keahlian</th>
                    <td>: {{ $application->student_major }}</td>
                </tr>
                <tr>
                    <th class="text-left font-normal">Jumlah siswa</th>
                    <td>: 2</td>
                </tr>
                <tr>
                    <th class="text-left font-normal">Jadwal</th>
                    <td>: 3 November 2025 - 30 April 2026</td>
                </tr>
            </table>
            <table class="mb-4 border border-black border-collapse w-11/12">
                <tr>
                    <th class="border border-black p-1 text-center">NO</th>
                    <th class="border border-black p-1 text-center">NAMA</th>
                    <th class="border border-black p-1 text-center">NIS</th>
                </tr>
        
                <tr>
                    <td class="border border-black p-1 text-center">1</td>
                    <td class="border border-black p-1 text-center">{{ $application->student_name }}</td>
                    <td class="border border-black p-1 text-center">{{ $application->student_number }}</td>
                </tr>
            </table>
        </div>
        <div class="mb-4">
            <p>Berikut adalah kompetensi yang sudah diperoleh siswa sampai dengan semester IV: Menginstal Sistem Operasi, Pemrograman Dasar, Basis Data, Pemrograman Web, Pemrograman Berbasis Text, dan Pemrograman Perangkat Bergerak.</p>
        </div>
        <div>
            <p>Sehubungan dengan hal tersebut kami mohon Bapak/Ibu berkenan dapat memberikan izin dengan mengirimkan surat balasan ke contact person di bawah. Demikian pengajuan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih. </p>
        </div>
    </main>
</body>
</html>
