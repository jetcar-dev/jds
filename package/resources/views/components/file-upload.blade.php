@props([
    'name' => null,
    'valueName' => null,
    'files' => [],
    'multiple' => false,
    'accept' => null,
    'maxSizeLabel' => null,
    'maxFiles' => null,
    'maxFileSize' => 10,
    'id' => null,
    'disabled' => false,
    'previewable' => true,
    'deletable' => true,
    'downloadAllUrl' => null,
])

@php
    $fieldId = $id ?? 'file-upload-' . Illuminate\Support\Str::random(8);
    $resolvedMaxFiles = $maxFiles ?? ($multiple ? 10 : 1);
    $inputName = $name ? ($multiple && ! str_ends_with($name, '[]') ? $name . '[]' : $name) : null;
    $existingValueName = $valueName ?: ($name ? rtrim($name, '[]') . '_existing[]' : null);
    $hint = $maxSizeLabel ?: trim(($accept ?: '모든 파일') . ' · 파일당 ' . $maxFileSize . 'MB');
    $wireModelAttributes = $attributes->whereStartsWith('wire:model');
    $rootAttributes = $attributes->whereDoesntStartWith('wire:model');
@endphp

<div
    {{ $rootAttributes->class(['app-file', 'app-file-disabled' => $disabled]) }}
    id="{{ $fieldId }}"
    data-slot="file-upload"
    data-app-file
    data-multiple="{{ $multiple ? '1' : '0' }}"
    data-max-files="{{ $resolvedMaxFiles }}"
    data-max-file-size="{{ $maxFileSize }}"
    data-accept="{{ $accept ?: '*/*' }}"
    data-previewable="{{ $previewable ? '1' : '0' }}"
    data-deletable="{{ $deletable ? '1' : '0' }}"
    data-input-name="{{ $inputName }}"
    data-value-name="{{ $existingValueName }}"
    @if($downloadAllUrl) data-download-all-url="{{ $downloadAllUrl }}" @endif
>
    <input
        {{ $wireModelAttributes }}
        id="{{ $fieldId }}-input"
        class="app-file-input"
        data-file-input
        type="file"
        @if($inputName) name="{{ $inputName }}" @endif
        @if($accept) accept="{{ $accept }}" @endif
        @if($multiple) multiple @endif
        @disabled($disabled)
    />

    <div
        class="app-file-dropzone"
        data-file-dropzone
        role="button"
        tabindex="{{ $disabled ? '-1' : '0' }}"
        aria-controls="{{ $fieldId }}-input"
        aria-disabled="{{ $disabled ? 'true' : 'false' }}"
    >
        <span class="app-file-upload-icon">
            <x-icon name="lucide:cloud-upload" aria-hidden="true" />
        </span>
        <div class="app-file-prompt">
            <span><strong>파일을 선택</strong>하거나 끌어 놓거나 붙여 넣으세요</span>
            <span class="app-file-hint">{{ $hint }}</span>
        </div>
    </div>

    <div class="app-file-toolbar" data-file-toolbar hidden>
        <span class="app-file-summary" data-file-summary></span>
        <div class="app-file-toolbar-actions">
            <button type="button" class="app-file-tool" data-file-download-all>
                <x-icon name="download-minimalistic-linear" aria-hidden="true" />
                전체 다운로드
            </button>
            @if($deletable && !$disabled)
                <button type="button" class="app-file-tool app-file-tool-danger" data-file-delete-all>
                    <x-icon name="trash-bin-trash-linear" aria-hidden="true" />
                    전체 삭제
                </button>
            @endif
        </div>
    </div>

    <div class="app-file-list" data-file-list role="list">
        @foreach($files as $file)
            @php
                $file = is_object($file) ? (array) $file : $file;
                $fileId = $file['id'] ?? 0;
                $fileName = $file['name'] ?? $file['original_name'] ?? '첨부파일';
                $fileSize = $file['size'] ?? 0;
                $fileExtension = strtolower($file['extension'] ?? pathinfo($fileName, PATHINFO_EXTENSION));
                $fileMime = $file['mime'] ?? $file['mime_type'] ?? '';
                $fileType = $file['type'] ?? '';
                $previewType = $file['previewType'] ?? $file['preview_type'] ?? '';
                $download = $file['download'] ?? $file['downloadUrl'] ?? $file['download_url'] ?? $file['url'] ?? '';
                $preview = $file['preview'] ?? $file['previewUrl'] ?? $file['preview_url'] ?? $file['thumbnail'] ?? $download;
                $isImage = $previewType === 'image'
                    || $fileType === 'image'
                    || str_starts_with(strtolower($fileMime), 'image/')
                    || in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
            @endphp
            <div
                class="app-file-item"
                role="listitem"
                data-file-id="{{ $fileId }}"
                data-file-name="{{ $fileName }}"
                data-file-size="{{ $fileSize }}"
                data-file-extension="{{ $fileExtension }}"
                data-file-type="{{ $fileType }}"
                data-file-preview-type="{{ $previewType }}"
                data-file-mime="{{ $fileMime }}"
                data-file-download="{{ $download }}"
                data-file-preview="{{ $preview }}"
            >
                <button type="button" class="app-file-open" data-file-open @disabled(!$previewable || !$preview)>
                    <span class="app-file-thumbnail">
                        @if($isImage && $preview)
                            <img src="{{ $preview }}" alt="" loading="lazy" />
                        @else
                            <x-icon name="lucide:file" aria-hidden="true" />
                        @endif
                    </span>
                    <span class="app-file-info">
                        <span class="app-file-name">{{ $fileName }}</span>
                        <span class="app-file-meta"></span>
                    </span>
                </button>
                <div class="app-file-actions">
                    @if($download)
                        <a class="app-file-action" href="{{ $download }}" download aria-label="{{ $fileName }} 다운로드" title="다운로드">
                            <x-icon name="download-minimalistic-linear" aria-hidden="true" />
                        </a>
                    @endif
                    @if($deletable && !$disabled)
                        <button type="button" class="app-file-action app-file-delete" data-file-delete aria-label="{{ $fileName }} 삭제" title="삭제">
                            <x-icon name="trash-bin-trash-linear" aria-hidden="true" />
                        </button>
                    @endif
                </div>
                @if($fileId && $existingValueName)
                    <input type="hidden" data-file-value name="{{ $existingValueName }}" value="{{ $fileId }}" />
                @endif
            </div>
        @endforeach
    </div>

    <p class="app-file-status" data-file-status role="status" aria-live="polite"></p>

    <div id="{{ $fieldId }}-preview" class="app-file-viewer" data-file-viewer role="dialog" aria-modal="true" aria-label="파일 미리보기" tabindex="-1" hidden>
        <header class="app-file-viewer-header">
            <span class="app-file-viewer-count" data-file-viewer-count></span>
            <div class="app-file-viewer-tools">
                <button type="button" data-file-fit aria-label="화면에 맞춤" title="화면에 맞춤"><x-icon name="lucide:maximize-2" aria-hidden="true" /></button>
                <button type="button" data-file-zoom-out aria-label="축소" title="축소"><x-icon name="lucide:zoom-out" aria-hidden="true" /></button>
                <button type="button" data-file-zoom-in aria-label="확대" title="확대"><x-icon name="lucide:zoom-in" aria-hidden="true" /></button>
                <button type="button" data-file-rotate aria-label="회전" title="회전"><x-icon name="lucide:rotate-cw" aria-hidden="true" /></button>
                <a data-file-viewer-download download aria-label="다운로드" title="다운로드"><x-icon name="download-minimalistic-linear" aria-hidden="true" /></a>
                <button type="button" data-file-viewer-close aria-label="닫기" title="닫기"><x-icon name="lucide:x" aria-hidden="true" /></button>
            </div>
        </header>
        <div class="app-file-viewer-stage">
            <button type="button" class="app-file-viewer-nav app-file-viewer-prev" data-file-prev aria-label="이전 파일"><x-icon name="lucide:chevron-left" aria-hidden="true" /></button>
            <div class="app-file-viewer-content" data-file-viewer-content></div>
            <button type="button" class="app-file-viewer-nav app-file-viewer-next" data-file-next aria-label="다음 파일"><x-icon name="lucide:chevron-right" aria-hidden="true" /></button>
        </div>
        <footer class="app-file-viewer-info">
            <strong data-file-viewer-name></strong>
            <span data-file-viewer-meta></span>
        </footer>
    </div>
</div>
