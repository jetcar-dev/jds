<div>
    @foreach ([
        'transparent' => ['Transparent', '투명 배경', '다른 카드 안에 들어가는 요약에 사용합니다.'],
        'default' => ['Default', '기본 표면', '예약 내역처럼 일반적인 정보를 담습니다.'],
        'secondary' => ['Secondary', '보조 표면', '기본 표면과 구분할 영역에 사용합니다.'],
        'tertiary' => ['Tertiary', '세 번째 표면', '다른 카드와 시각적으로 구분해 배치합니다.'],
    ] as $variant => [$title, $description, $content])
        <x-jds::card :variant="$variant">
            <x-jds::card.header>
                <x-jds::card.title>{{ $title }}</x-jds::card.title>
                <x-jds::card.description>{{ $description }}</x-jds::card.description>
            </x-jds::card.header>
            <x-jds::card.content><p>{{ $content }}</p></x-jds::card.content>
        </x-jds::card>
    @endforeach
</div>
