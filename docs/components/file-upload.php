<?php

return [
    'title' => 'File Upload',
    'description' => '선택, 드래그, 붙여넣기로 파일을 추가하고 저장된 파일을 미리보기·다운로드합니다.',
    'parts' => [
        0 => 'file-upload',
    ],
    'examples' => [
        [
            'key' => 'default',
            'title' => '기본 사용법',
            'description' => '파일을 선택하거나 끌어 놓고, 클립보드에서 붙여 넣어 업로드 목록에 추가합니다.',
            'code' => <<<'BLADE'
<x-file-upload
    name="attachments"
    value-name="existing_attachment_ids[]"
    multiple
    accept="image/*,application/pdf"
    :max-files="10"
    :max-file-size="10"
    :files="$savedFiles"
    download-all-url="/attachments/download-all"
/>
BLADE,
        ],
        [
            'key' => 'single-image',
            'title' => '이미지 하나 업로드',
            'description' => '이미지 하나만 선택하고 미리보기를 사용합니다.',
            'code' => <<<'BLADE'
<x-file-upload name="avatar" accept="image/*" :max-files="1" :max-file-size="5" />
BLADE,
        ],
        [
            'key' => 'saved-files',
            'title' => '저장된 파일 표시',
            'description' => '서버에 저장된 파일을 복원해 미리보기·다운로드·삭제합니다.',
            'code' => <<<'BLADE'
<x-file-upload name="attachments" value-name="existing_ids[]" :files="$savedFiles" :previewable="true" :deletable="true" download-all-url="/files/download-all" />
BLADE,
        ],
        [
            'key' => 'document-only',
            'title' => '문서 업로드',
            'description' => '허용 형식과 개수, 파일당 최대 용량을 업무에 맞게 제한합니다. 클릭·드래그·클립보드 붙여넣기를 모두 지원합니다.',
            'code' => <<<'BLADE'
<x-field>
    <x-label for="contract-files" :required="true">계약 서류</x-label>
    <x-file-upload
        id="contract-files"
        name="contracts"
        accept="application/pdf,.doc,.docx,.xls,.xlsx"
        :multiple="true"
        :max-files="5"
        :max-file-size="20"
        :previewable="true"
    />
    <x-field-description>PDF, Word, Excel · 파일당 최대 20MB</x-field-description>
</x-field>
BLADE,
        ],
    ],
];
