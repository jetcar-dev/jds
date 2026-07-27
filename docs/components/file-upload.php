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
            'title' => 'Basic',
            'description' => '가장 기본적인 사용 방법입니다.',
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
            'title' => 'Single Image',
            'description' => '이미지 하나만 선택하고 미리보기를 사용합니다.',
            'code' => <<<'BLADE'
<x-file-upload name="avatar" accept="image/*" :max-files="1" :max-file-size="5" />
BLADE,
        ],
        [
            'key' => 'saved-files',
            'title' => 'Saved Files',
            'description' => '서버에 저장된 파일을 복원해 미리보기·다운로드·삭제합니다.',
            'code' => <<<'BLADE'
<x-file-upload name="attachments" value-name="existing_ids[]" :files="$savedFiles" :previewable="true" :deletable="true" download-all-url="/files/download-all" />
BLADE,
        ],
    ],
];
