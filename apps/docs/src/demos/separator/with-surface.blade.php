<div style="display: grid; width: min(100%, 20rem); gap: 2rem;">
    @foreach (['default', 'secondary', 'tertiary', 'transparent'] as $surface)
        <x-jds::surface element-type="section" :variant="$surface">
            <h4>{{ ucfirst($surface) }} Surface</h4>
            <x-jds::separator :variant="$surface === 'transparent' ? 'default' : $surface" />
            <p>구분선 아래에 내용을 배치합니다.</p>
        </x-jds::surface>
    @endforeach
</div>
