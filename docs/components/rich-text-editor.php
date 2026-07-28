<?php

return [
    'title' => 'Rich Text Editor',
    'description' => '서식, 목록, 링크, 이미지 등을 포함한 HTML 내용을 편집합니다.',
    'parts' => [
        0 => 'rich-text-editor',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => 'name과 placeholder만 지정하면 서식 도구가 포함된 편집기를 사용할 수 있습니다.',
            'code' => <<<'BLADE'
<x-rich-text-editor
    name="content"
    :value="old('content', $post->content)"
    placeholder="내용을 입력해 주세요"
/>
BLADE,
        ],
        [
            'key' => 'initial-value',
            'title' => '초기 내용 표시',
            'description' => '저장된 HTML 콘텐츠를 초기값으로 표시합니다.',
            'code' => <<<'BLADE'
<x-rich-text-editor name="content" value="<h2>공지사항</h2><p>내용을 수정하세요.</p>" />
BLADE,
        ],
        [
            'key' => 'form',
            'title' => '입력 필드로 사용',
            'description' => '라벨과 오류 상태를 포함한 폼 필드로 구성합니다.',
            'code' => <<<'BLADE'
<x-field :invalid="$errors->has('content')">
    <x-label for="content" :required="true">본문</x-label>
    <x-rich-text-editor id="content" name="content" :value="old('content')" />
    <x-field-error :messages="$errors->get('content')" />
</x-field>
BLADE,
        ],
        [
            'key' => 'announcement',
            'title' => '공지사항 작성',
            'description' => '제목 입력과 공개 설정을 함께 배치한 실제 공지 작성 화면 예제입니다.',
            'code' => <<<'BLADE'
<x-card style="width: min(100%, 42rem);">
    <x-card-header>
        <x-card-title>공지사항 작성</x-card-title>
        <x-card-description>임직원에게 표시할 공지를 작성합니다.</x-card-description>
    </x-card-header>
    <x-card-content>
        <x-field><x-label for="notice-title">제목</x-label><x-input id="notice-title" name="title" :full-width="true" /></x-field>
        <x-field><x-label for="notice-body">내용</x-label><x-rich-text-editor id="notice-body" name="body" placeholder="공지 내용을 입력하세요" /></x-field>
    </x-card-content>
    <x-card-footer style="justify-content: flex-end; gap: 0.5rem;">
        <x-button variant="ghost">임시 저장</x-button><x-button>게시</x-button>
    </x-card-footer>
</x-card>
BLADE,
        ],
    ],
];
