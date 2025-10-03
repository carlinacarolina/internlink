<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'InternLink')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('layouts.partials.header')
@php
    use Illuminate\Support\Str;

    $role = session('role');
    $currentPath = request()->path();
    $normalizedPath = trim($currentPath, '/');
    if ($normalizedPath === '') {
        $normalizedPath = '/';
    }

    $hasSchool = app()->bound('currentSchool');
    $schoolCode = $hasSchool ? (string) app('currentSchool')->code : null;
    $schoolSegment = $schoolCode !== null ? rawurlencode($schoolCode) : null;

    $schoolUrl = function (string $path = '') use ($hasSchool, $schoolSegment) {
        if ($hasSchool && $schoolSegment !== null) {
            $trimmed = trim($path, '/');
            $segment = $schoolSegment . ($trimmed !== '' ? '/' . $trimmed : '');
            return url($segment === '' ? '/' : $segment);
        }

        $trimmed = trim($path, '/');
        return url($trimmed === '' ? '/' : $trimmed);
    };

    if (!$hasSchool) {
        $navItems = [
            [
                'label' => 'Dashboard',
                'href' => url('/'),
                'icon' => 'bi-speedometer2',
                'patterns' => ['/', 'dashboard'],
            ],
        ];

        if ($role === 'developer') {
            $navItems[] = [
                'label' => 'Developers',
                'href' => url('/developers'),
                'icon' => 'bi-code-slash',
                'patterns' => ['developers*'],
            ];

            $navItems[] = [
                'label' => 'Schools',
                'href' => url('/schools'),
                'icon' => 'bi-building-check',
                'patterns' => ['schools*'],
            ];
        }
    } else {
        $basePrefix = $schoolSegment . '/';

        $navItems = [
            [
                'label' => 'Dashboard',
                'href' => $schoolUrl(),
                'icon' => 'bi-speedometer2',
                'patterns' => [$schoolSegment, $basePrefix . 'dashboard*'],
            ],
            [
                'label' => 'Students',
                'href' => $schoolUrl('students'),
                'icon' => 'bi-mortarboard',
                'patterns' => [$basePrefix . 'students*'],
            ],
        [
            'label' => 'Supervisors',
            'href' => $schoolUrl('supervisors'),
            'icon' => 'bi-people',
            'patterns' => [$basePrefix . 'supervisors*'],
        ],
        [
            'label' => 'Major Contacts',
            'href' => $schoolUrl('major-contacts'),
            'icon' => 'bi-person-lines-fill',
            'patterns' => [$basePrefix . 'major-contacts*'],
            'roles' => ['admin', 'developer'],
        ],
        [
            'label' => 'Admins',
            'href' => $schoolUrl('admins'),
                'icon' => 'bi-shield-lock',
                'patterns' => [$basePrefix . 'admins*'],
                'roles' => ['admin', 'developer'],
            ],
            [
                'label' => 'Schools',
                'href' => url('/schools'),
                'icon' => 'bi-building-check',
                'patterns' => ['schools*'],
                'roles' => ['developer'],
            ],
            [
                'label' => 'Institutions',
                'href' => $schoolUrl('institutions'),
                'icon' => 'bi-building',
                'patterns' => [$basePrefix . 'institutions*'],
            ],
            [
                'label' => 'Applications',
                'href' => $schoolUrl('applications'),
                'icon' => 'bi-file-earmark-text',
                'patterns' => [$basePrefix . 'applications*'],
            ],
            [
                'label' => 'Internships',
                'href' => $schoolUrl('internships'),
                'icon' => 'bi-briefcase',
                'patterns' => [$basePrefix . 'internships*'],
            ],
            [
                'label' => 'Monitorings',
                'href' => $schoolUrl('monitorings'),
                'icon' => 'bi-clipboard-data',
                'patterns' => [$basePrefix . 'monitorings*'],
            ],
        ];
    }

    $navItems = array_values(array_filter($navItems, function ($item) use ($role) {
        if (!isset($item['roles'])) {
            return true;
        }

        return in_array($role, $item['roles'], true);
    }));
@endphp
<div id="appShell" class="app-shell">
    <nav id="sidebar" class="app-sidebar" aria-label="Main navigation">
        <div class="sidebar-brand">
            <span class="sidebar-logo">IN</span>
            <span class="sidebar-label">InternLink</span>
        </div>
        <div class="list-group list-group-flush">
            @foreach($navItems as $item)
                @php
                    $isActive = false;
                    foreach ($item['patterns'] as $pattern) {
                        if ($pattern === '/' && $normalizedPath === '/') {
                            $isActive = true;
                            break;
                        }

                        if ($pattern !== '/' && Str::is($pattern, $normalizedPath)) {
                            $isActive = true;
                            break;
                        }
                    }
                @endphp
                <a href="{{ $item['href'] }}" class="list-group-item list-group-item-action {{ $isActive ? 'active' : '' }}" @if($isActive) aria-current="page" @endif>
                    <span class="sidebar-icon"><i class="bi {{ $item['icon'] }}"></i></span>
                    <span class="sidebar-label">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>
    <main id="appContent" class="app-content">
        @if (session('status'))
            <div class="alert alert-info mb-4">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
</div>
<div id="appBackdrop" class="app-backdrop"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
