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
<body class="font-sans h-screen flex flex-col text-[0.8rem]">
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
    <header class="gap-4 h-44 border-b-4 flex-col border-black pb-3 mb-6">
        <div class="flex justify-between items-center h-full">
            <div class="flex items-center h-full">
                <img src="{{ public_path('images/applications/west-java-logo.png') }}" alt="" class="h-full w-auto max-w-none">
            </div>
            <div class="flex justify-center items-center text-sm flex-col flex-1 h-full">
                <p class="uppercase tracking-wide text-xs">{{ $school->name }}</p>
                <p class="text-xs">{{ $school->address }}</p>
                @if($cityLine)
                    <p class="text-xs">{{ $cityLine }}</p>
                @endif
                @if($contactLines)
                    <p class="text-xs">{{ $contactLines }}</p>
                @endif
            </div>
        </div>
    </header>
    <main class="flex flex-col flex-1 text-[0.75rem]">
        <div class="mb-6">
            <p>No: {{ sprintf('APP/%s/%s', str_pad($application->id, 4, '0', STR_PAD_LEFT), strtoupper($school->code)) }}</p>
            <p>Subject: Internship Placement Request</p>
            <p>Date: {{ $submittedDate }}</p>
        </div>
        <div class="mb-6">
            <p class="font-semibold">To:</p>
            <p class="font-semibold text-sm">{{ $application->institution_name }}</p>
            <p>{{ $application->institution_address }}</p>
            <p>{{ collect([$application->institution_city, $application->institution_province])->filter()->implode(', ') }}</p>
            @if($application->institution_contact_name)
                <p>Attn: {{ $application->institution_contact_name }}</p>
            @endif
        </div>
        <div class="mb-4 leading-relaxed">
            <p>We kindly request your approval to place students from the <strong>{{ $application->student_major ?? '—' }}</strong> major at your esteemed institution for their internship program. Details of the proposed activity are provided below:</p>
        </div>
        <div class="mb-4">
            <table class="mb-3">
                <tr>
                    <th class="text-left font-normal">Major</th>
                    <td>: {{ $application->student_major ?? '—' }}</td>
                </tr>
                <tr>
                    <th class="text-left font-normal">Number of Students</th>
                    <td>: {{ $studentCount }}</td>
                </tr>
                <tr>
                    <th class="text-left font-normal">Internship Schedule</th>
                    <td>: {{ $scheduleDisplay }}</td>
                </tr>
            </table>
            <table class="mb-4 border border-black border-collapse w-full">
                <thead>
                    <tr>
                        <th class="border border-black p-1 text-center w-12">No</th>
                        <th class="border border-black p-1 text-center">Student Name</th>
                        <th class="border border-black p-1 text-center">Student Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                        <tr>
                            <td class="border border-black p-1 text-center">{{ $index + 1 }}</td>
                            <td class="border border-black p-1">{{ $student->student_name }}</td>
                            <td class="border border-black p-1 text-center">{{ $student->student_number ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mb-4 leading-relaxed">
            <p>We believe this internship will help our students strengthen their technical and professional competencies. We hope your organisation can support this request and provide further coordination if additional requirements are needed.</p>
        </div>
        <div class="mb-4 leading-relaxed">
            <p>Thank you for your kind attention and cooperation.</p>
        </div>
        <div class="mb-12 h-28 flex flex-col justify-between items-end">
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
        <div class="flex flex-col gap-1 mt-auto">
            <p class="font-semibold">Contact Person:</p>
            <p>Name: {{ $staffName }}</p>
            <p>Email: {{ $staffEmail }}</p>
            <p>Phone: {{ $staffPhone }}</p>
            <p>Supervisor Number: {{ $staffNumber }}</p>
        </div>
    </main>
</body>
</html>
