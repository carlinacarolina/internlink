<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <span class="fs-5 fw-semibold">Settings</span>
        </div>
        <nav class="nav flex-column nav-pills gap-2" aria-label="Settings navigation">
            @php
                $links = [
                    ['key' => 'profile', 'label' => 'Profile', 'href' => $schoolRoute('settings/profile')],
                    ['key' => 'security', 'label' => 'Security', 'href' => $schoolRoute('settings/security')],
                ];
                if (!empty($environmentAvailable)) {
                    $links[] = ['key' => 'environments', 'label' => 'Environments', 'href' => $schoolRoute('settings/environments')];
                }
            @endphp
            @foreach($links as $link)
                <a class="nav-link {{ $active === $link['key'] ? 'active' : '' }}" href="{{ $link['href'] }}">{{ $link['label'] }}</a>
            @endforeach
        </nav>
    </div>
</div>
