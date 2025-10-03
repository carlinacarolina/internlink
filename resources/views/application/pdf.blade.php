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
            margin: 10mm;
        }
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
<body class="font-sans h-screen flex flex-col">
@php
    use Illuminate\Support\Carbon;

    $studentCount = $students->count();
    $formattedStart = $application->planned_start_date ? Carbon::parse($application->planned_start_date)->format('d F Y') : null;
    $formattedEnd = $application->planned_end_date ? Carbon::parse($application->planned_end_date)->format('d F Y') : null;
    $scheduleDisplay = ($formattedStart && $formattedEnd)
        ? $formattedStart . ' – ' . $formattedEnd
        : trim(collect([$application->period_year, $application->period_term ? 'Term ' . $application->period_term : null])->filter()->implode(' '));
    if ($scheduleDisplay === '') {
        $scheduleDisplay = '—';
    }
    $submittedDate = Carbon::parse($application->submitted_at)->format('d F Y');
    $cityLine = trim(collect([$school->city, $school->postal_code])->filter()->implode(' '));
    $contactLines = collect([
        $school->phone ? 'Phone ' . $school->phone : null,
        $school->email ? 'Email ' . $school->email : null,
        $school->website ? 'Website ' . $school->website : null,
    ])->filter()->implode(' · ');
    $staffName = $application->staff_name ?? '—';
    $staffEmail = $application->staff_email ?? '—';
    $staffPhone = $application->staff_phone ?? '—';
    $staffNumber = $application->staff_supervisor_number ?? '—';
@endphp
    <header class="gap-4 h-auto border-b-4 flex-col border-black pb-2 mb-2">
        <div class="flex flex-row justify-between items-center h-full">
            <div class="flex items-center h-auto">
                <img src="{{ public_path('images/applications/west-java-logo.png') }}" alt="" class="h-32 w-auto max-w-none">
            </div>
            <div class="flex items-center text-base flex-col flex-1 h-auto leading-tight">
                <p>PEMERINTAH DAERAH PROVINSI JAWA BARAT</p>
                <p class="font-bold">DINAS PENDIDIKAN</p>
                <p>CABANG DINAS PENDIDIKAN WILAYAH VII</p>
                <p class="font-bold uppercase">{{ $school->name }}</p>
                <p class="text-[0.8rem]">{{ $school->address }}</p>
                <div class="flex flex-wrap justify-center gap-x-2 text-[0.8rem]">
                    @if($school->phone)
                        <p>Telepon {{ preg_replace('/[\s\-\+]/', '', $school->phone) }}</p>
                    @endif
                    @if($school->city)
                        <p>{{ $school->city }}</p>
                    @endif
                    @if($school->postal_code)
                        <p>{{ $school->postal_code }}</p>
                    @endif
                    @if($school->email)
                        <p>Email: {{ $school->email }}</p>
                    @endif
                    @if($school->website)
                        <p>Website: {{ $school->website }}</p>
                    @endif
                </div>

            </div>
        </div>
    </header>
    <main class="flex flex-col flex-1 text-[0.8rem]">
        <div class="mb-6 leading-tight">
            <p>Nomor: {{ sprintf('APP/%s/%s', str_pad($application->id, 4, '0', STR_PAD_LEFT), strtoupper($school->code)) }}</p>
            <p>Lampiran: - </p>
            <p>Hal: Pengajuan Praktik Kerja Lapangan (PKL) dan Uji Kompetensi (Ukom)</p>
        </div>
        <div class="mb-4 leading-tight">
            <p class="font-bold text-base">YTH, HRD {{ $application->institution_name }}</p>
            <p>di</p>
            <p>{{ $application->institution_address }}</p>
        </div>
        <div class="mb-4 leading-tight">
            <p>Dengan hormat, </p>
        </div>
        <div class="mb-4 leading-tight">
            <p>Berdasarkan pada kurikulum merdeka, bahwa siswa SMK tingkat akhir (kelas XII) harus melaksanakan Praktik Kerja Lapangan dan Uji Kompetensi (UKOM) sebagai salah satu syarat kelulusan. Oleh karena itu, kami mengajukan permohonan untuk melaksanakan kegiatan uji kompetensi bagi siswa kami sebagai berikut: </p>
        </div>
        <div class="mb-4 flex flex-col items-center">
            <table class="mb-4 w-4/5 flex justify-center leading-tight">
                <tr>
                    <th class="text-left font-normal">Kompetensi Keahlian</th>
                    <td>: {{ $application->student_major ?? '—' }}</td>
                </tr>
                <tr>
                    <th class="text-left font-normal">Jumlah Siswa</th>
                    <td>: {{ $studentCount }}</td>
                </tr>
                <tr>
                    <th class="text-left font-normal">Jadwal</th>
                    <td>: {{ $scheduleDisplay }}</td>
                </tr>
            </table>
            <table class="border w-3/5 border-black border-collapse leading-tight">
                <thead>
                    <tr>
                        <th class="border border-black text-center w-12">No</th>
                        <th class="border border-black text-center">Student Name</th>
                        <th class="border border-black text-center">Student Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                        <tr>
                            <td class="border border-black text-center">{{ $index + 1 }}</td>
                            <td class="border border-black">{{ $student->student_name }}</td>
                            <td class="border border-black p-1 text-center">{{ $student->student_number ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mb-4 leading-tight">
            <p>Berikut adalah kompetensi yang sudah diperoleh siswa sampai dengan semester IV: Menginstal Sistem Operasi, Pemrograman Dasar, Basis Data, Pemrograman Web, Pemrograman Berbasis Text, dan Pemrograman Perangkat Bergerak. </p>
        </div>
        <div class="mb-4 leading-tight">
            <p>Sehubungan dengan hal tersebut kami mohon Bapak/Ibu berkenan dapat memberikan izin dengan mengirimkan surat balasan ke contact person di bawah. Demikian pengajuan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih. </p>
        </div>
        <div class="mb-12 h-28 flex flex-col justify-between items-end leading-tight">
            <div class="text-right">
                <p>{{ $school->city ?? '________' }}, {{ $submittedDate }}</p>
                <p>{{ $school->principal_name ? 'Principal,' : 'Principal' }}</p>
            </div>
            <div class="text-right">
                <p class="font-semibold">{{ $school->principal_name ?? '___________________' }}</p>
                @if($school->principal_nip)
                    <p>NIP: {{ $school->principal_nip }}</p>
                @endif
            </div>
        </div>
        <div class="flex flex-col leading-tight">
            <p class="italic font-semibold">Contact Person:</p>
            <p>{{ $staffName }}</p>
            <p><span class="italic">Email:</span> {{ $staffEmail }}</p>
            <p>Telepon: {{ $staffPhone }}</p>
        </div>
    </main>
</body>
</html>
