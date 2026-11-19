<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; width: 100%;">
    @foreach ([['ioniq-5.jpg', '전기차 드라이버', '멤버 148명', '서', '서윤'], ['mountain-road.jpg', '주말 드라이브', '멤버 362명', '지', '지원']] as [$image, $title, $members, $initial, $host])
        <x-jds::card style="width: 200px; max-width: 100%;">
            <img class="group-avatar" src="/images/card/{{ $image }}" alt="" />
            <x-jds::card.header>
                <x-jds::card.title>{{ $title }}</x-jds::card.title>
                <x-jds::card.description>{{ $members }}</x-jds::card.description>
            </x-jds::card.header>
            <x-jds::card.footer style="gap: .5rem; font-size: .75rem;">
                <span class="host-avatar" aria-hidden="true">{{ $initial }}</span>
                <span>운영자 {{ $host }}</span>
            </x-jds::card.footer>
        </x-jds::card>
    @endforeach
</div>

<style>
    .group-avatar { width: 3.5rem; height: 3.5rem; border-radius: 1rem; object-fit: cover; }
    .host-avatar { display: grid; place-items: center; flex-shrink: 0; width: 1.25rem; height: 1.25rem; border-radius: 50%; background: var(--default); font-size: .6875rem; }
</style>
