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
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
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
            'title' => 'Initial Value',
            'description' => '저장된 HTML 콘텐츠를 초기값으로 표시합니다.',
            'code' => <<<'BLADE'
<x-rich-text-editor name="content" value="<h2>공지사항</h2><p>내용을 수정하세요.</p>" />
BLADE,
        ],
        [
            'key' => 'form',
            'title' => 'Form Field',
            'description' => '라벨과 오류 상태를 포함한 폼 필드로 구성합니다.',
            'code' => <<<'BLADE'
<x-field :invalid="$errors->has('content')"><x-field-label for="content">본문</x-field-label><x-rich-text-editor id="content" name="content" :value="old('content')" /><x-field-error :messages="$errors->get('content')" /></x-field>
BLADE,
        ],
    ],
];
