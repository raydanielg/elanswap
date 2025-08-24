@php
    $size = $size ?? 24;
    $class = $class ?? 'w-6 h-6';
    $rawName = strtolower($name ?? 'bolt');
    $mappedName = $rawName;

    if (\Illuminate\Support\Str::startsWith($rawName, 'ms:')) {
        $ms = \Illuminate\Support\Str::after($rawName, 'ms:');
        $map = [
            'bolt' => 'bolt',
            'shield' => 'shield',
            'map' => 'map',
            'analytics' => 'chart',
            'insights' => 'chart',
            'bar_chart' => 'chart',
            'group' => 'users',
            'groups' => 'users',
            'schedule' => 'clock',
            'clock' => 'clock',
            'settings' => 'cog',
            'tune' => 'cog',
            'search' => 'search',
            'swap_horiz' => 'swap',
            'compare_arrows' => 'swap',
            'notifications' => 'bell',
            'support' => 'lifebuoy',
            'help' => 'lifebuoy',
            'lock' => 'lock',
            'verified' => 'verified',
            'shield_person' => 'verified',
        ];
        if (isset($map[$ms])) {
            $mappedName = $map[$ms];
        } else {
            // If not mappable, fall back to a simple circle icon rather than relying on the font
            $mappedName = 'circle';
        }
    }
@endphp

@if($mappedName === 'bolt')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M14.615 3.513A.75.75 0 0013.5 3h-3a.75.75 0 00-.712.513l-3 9A.75.75 0 007.5 13.5h2.694l-1.31 6.551a.75.75 0 001.318.63l7.5-9A.75.75 0 0017.25 10.5H13.5l1.115-6.987z"/>
    </svg>
@elseif($mappedName === 'shield')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M12 2.25a.75.75 0 01.33.077l6.75 3.375a.75.75 0 01.42.673V12a9.75 9.75 0 01-7.2 9.404.75.75 0 01-.6 0A9.75 9.75 0 014.5 12V6.375a.75.75 0 01.42-.673L11.67 2.327A.75.75 0 0112 2.25z"/>
    </svg>
@elseif($mappedName === 'map')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M9.75 3.428l-5.5 2.2a.75.75 0 00-.45.69v12.5a.75.75 0 001.02.694l4.93-1.972 5.5 2.2 5.5-2.2a.75.75 0 00.45-.69V4.35a.75.75 0 00-1.02-.694l-4.93 1.972-5.5-2.2zM9 5.44v11.13l6 2.4V7.84L9 5.44z"/>
    </svg>
@elseif($mappedName === 'chart')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M3.75 3a.75.75 0 00-.75.75v16.5a.75.75 0 00.75.75h16.5a.75.75 0 00.75-.75V3.75A.75.75 0 0020.25 3H3.75zm3 12.75a.75.75 0 01.75-.75h1.5a.75.75 0 01.75.75v2.25a.75.75 0 01-.75.75h-1.5a.75.75 0 01-.75-.75V15.75zm4.5-6a.75.75 0 01.75-.75h1.5a.75.75 0 01.75.75v8.25a.75.75 0 01-.75.75h-1.5a.75.75 0 01-.75-.75V9.75zm4.5-3a.75.75 0 01.75-.75h1.5a.75.75 0 01.75.75v11.25a.75.75 0 01-.75.75h-1.5a.75.75 0 01-.75-.75V6.75z"/>
    </svg>
@elseif($mappedName === 'users')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M7.5 6.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM2.25 18a5.25 5.25 0 0110.5 0v1.5H2.25V18zm13.5-.75a3 3 0 116 0v2.25h-6V17.25z"/>
    </svg>
@elseif($mappedName === 'clock')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5zM12.75 6a.75.75 0 00-1.5 0v6a.75.75 0 00.33.623l3.75 2.5a.75.75 0 10.84-1.246l-3.42-2.28V6z"/>
    </svg>
@elseif($mappedName === 'cog' || $mappedName === 'settings')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M11.25 2.25a.75.75 0 01.75 0l2.122 1.226a.75.75 0 00.71.01l1.93-.964a.75.75 0 011.06.69l-.058 2.15a.75.75 0 00.39.676l1.913 1.05a.75.75 0 01.252 1.102l-1.2 1.653a.75.75 0 000 .864l1.2 1.653a.75.75 0 01-.252 1.102l-1.913 1.05a.75.75 0 00-.39.676l.058 2.15a.75.75 0 01-1.06.69l-1.93-.964a.75.75 0 00-.71.01L12 21.75a.75.75 0 01-.75 0l-2.122-1.226a.75.75 0 00-.71-.01l-1.93.964a.75.75 0 01-1.06-.69l.058-2.15a.75.75 0 00-.39-.676l-1.913-1.05a.75.75 0 01-.252-1.102L4.13 12a.75.75 0 000-.864L2.93 9.483a.75.75 0 01.252-1.102l1.913-1.05a.75.75 0 00.39-.676l-.058-2.15a.75.75 0 011.06-.69l1.93.964a.75.75 0 00.71-.01L11.25 2.25zM12 9a3 3 0 100 6 3 3 0 000-6z"/>
    </svg>
@elseif($mappedName === 'search' || $mappedName === 'magnify')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M10.5 3.75a6.75 6.75 0 104.243 12.03l3.738 3.738a.75.75 0 101.06-1.06l-3.738-3.739A6.75 6.75 0 0010.5 3.75zm0 1.5a5.25 5.25 0 110 10.5 5.25 5.25 0 010-10.5z"/>
    </svg>
@elseif($mappedName === 'swap' || $mappedName === 'arrows')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M7.5 4.5a.75.75 0 01.75.75v5.25h8.19l-2.47-2.47a.75.75 0 111.06-1.06l3.75 3.75a.75.75 0 010 1.06l-3.75 3.75a.75.75 0 11-1.06-1.06l2.47-2.47H8.25V18.75a.75.75 0 01-1.5 0V5.25A.75.75 0 017.5 4.5z"/>
    </svg>
@elseif($mappedName === 'bell' || $mappedName === 'notifications')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M12 2.25a3 3 0 00-3 3v.337A6.75 6.75 0 006 12v3.75l-1.5 1.5v.75h15v-.75l-1.5-1.5V12a6.75 6.75 0 00-3-5.413V5.25a3 3 0 00-3-3zM9.75 19.5a2.25 2.25 0 004.5 0h-4.5z"/>
    </svg>
@elseif($mappedName === 'lifebuoy' || $mappedName === 'support')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5zm0 3a6.75 6.75 0 110 13.5 6.75 6.75 0 010-13.5zm0 1.5a5.25 5.25 0 100 10.5 5.25 5.25 0 000-10.5z"/>
    </svg>
@elseif($mappedName === 'lock' || $mappedName === 'lock-closed')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M12 1.5a4.5 4.5 0 00-4.5 4.5V9H6.75A2.25 2.25 0 004.5 11.25v7.5A2.25 2.25 0 006.75 21h10.5A2.25 2.25 0 0019.5 18.75v-7.5A2.25 2.25 0 0017.25 9H16.5V6A4.5 4.5 0 0012 1.5zm-1.5 7.5V6A1.5 1.5 0 0112 4.5 1.5 1.5 0 0113.5 6v3H10.5z"/>
    </svg>
@elseif($mappedName === 'verified' || $mappedName === 'shield-check')
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <path d="M12 2.25l7.5 3.75V12a9.75 9.75 0 01-7.5 9.404A9.75 9.75 0 014.5 12V6l7.5-3.75z"/>
        <path d="M16.03 9.97a.75.75 0 010 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-2-2a.75.75 0 111.06-1.06l1.47 1.47 3.97-3.97a.75.75 0 011.06 0z"/>
    </svg>
@else
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="{{ $class }}" width="{{ $size }}" height="{{ $size }}">
        <circle cx="12" cy="12" r="9" />
    </svg>
@endif
