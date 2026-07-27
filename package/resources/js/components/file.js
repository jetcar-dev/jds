(function () {
    const fileStates = new WeakMap()
    let activePasteRoot = null
    let pasteListenerReady = false

    // 여러 업로드 영역 중 마지막으로 가리키거나 포커스한 한 곳에만 붙여넣음
    const bindPasteListener = function () {
        if (pasteListenerReady) {
            return
        }

        document.addEventListener('paste', function (event) {
            const root = activePasteRoot
            const state = root ? fileStates.get(root) : null

            if (!state || root.classList.contains('app-file-disabled') || typeof state.upload != 'function') {
                return
            }

            const files = Array.from(event.clipboardData?.items || [])
                .filter(function (item) {
                    return item.kind == 'file'
                })
                .map(function (item) {
                    return item.getAsFile()
                })
                .filter(Boolean)

            if (!files.length) {
                return
            }

            event.preventDefault()
            state.upload(files, 'paste')
        })
        pasteListenerReady = true
    }

    // byte 값을 파일 목록에서 읽기 쉬운 단위로 표시
    const formatSize = function (bytes) {
        const size = Number(bytes || 0)

        if (size < 1024) {
            return size + ' B'
        }

        if (size < 1024 * 1024) {
            return (size / 1024).toFixed(1) + ' KB'
        }

        if (size < 1024 * 1024 * 1024) {
            return (size / 1024 / 1024).toFixed(1) + ' MB'
        }

        return (size / 1024 / 1024 / 1024).toFixed(1) + ' GB'
    }

    // MIME과 확장자를 함께 확인해 미리보기 방식을 결정
    const getFileType = function (file) {
        const mime = String(file.mime || '').toLowerCase()
        const extension = String(file.extension || '').toLowerCase()
        const previewType = String(file.previewType || '').toLowerCase()

        if (['image', 'pdf', 'video', 'audio', 'document'].includes(previewType)) {
            return previewType
        }

        if (mime == 'application/pdf' || extension == 'pdf') {
            return 'pdf'
        }

        if ((mime.startsWith('image/') && mime != 'image/svg+xml') || ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(extension)) {
            return 'image'
        }

        if (file.type == 'video' || mime.startsWith('video/')) {
            return 'video'
        }

        if (file.type == 'audio' || mime.startsWith('audio/')) {
            return 'audio'
        }

        if (file.type == 'image' && extension != 'svg') {
            return 'image'
        }

        if (mime.startsWith('text/') || ['txt', 'csv', 'json', 'xml', 'html', 'htm', 'md'].includes(extension)) {
            return 'document'
        }

        return 'etc'
    }

    // 사용하는 화면에서 전달한 파일 정보를 컴포넌트 표준 형태로 맞춤
    const normalizeFile = function (file) {
        const id = Number(file.id || 0)
        const name = file.name || '첨부파일'
        const extension = String(file.extension || name.split('.').pop() || '').toLowerCase()

        return {
            id: id,
            name: name,
            size: Number(file.size || 0),
            extension: extension,
            mime: file.mime || '',
            type: file.type || '',
            previewType: file.previewType || '',
            download: file.download || '',
            preview: file.preview || file.download || '',
            raw: file.raw || null
        }
    }

    // 파일 한 개를 목록 마크업으로 추가
    const addFileItem = function (root, state, file) {
        const item = document.createElement('div')
        const normalized = normalizeFile(file)
        const type = getFileType(normalized)

        item.className = 'app-file-item'
        item.dataset.fileId = normalized.id
        item.dataset.fileName = normalized.name
        item.dataset.fileSize = normalized.size
        item.dataset.fileExtension = normalized.extension
        item.dataset.fileType = normalized.type
        item.dataset.filePreviewType = normalized.previewType
        item.dataset.fileMime = normalized.mime
        item.dataset.fileDownload = normalized.download
        item.dataset.filePreview = normalized.preview
        item.innerHTML = `
            <button type="button" class="app-file-open" data-file-open>
                <span class="app-file-thumbnail"><span data-slot="icon" data-icon="lucide:file" aria-hidden="true"></span></span>
                <span class="app-file-info">
                    <span class="app-file-name"></span>
                    <span class="app-file-meta"></span>
                </span>
            </button>
            <div class="app-file-actions">
                <a class="app-file-action" title="다운로드"><span data-slot="icon" data-icon="solar:download-minimalistic-linear" aria-hidden="true"></span></a>
                ${root.dataset.deletable == '1' && !root.classList.contains('app-file-disabled')
                    ? '<button type="button" class="app-file-action app-file-delete" data-file-delete title="삭제"><span data-slot="icon" data-icon="solar:trash-bin-trash-linear" aria-hidden="true"></span></button>'
                    : ''}
            </div>
            <input type="hidden" data-file-value>
        `

        item.querySelector('.app-file-name').textContent = normalized.name
        item.querySelector('.app-file-meta').textContent =
            (normalized.extension ? normalized.extension.toUpperCase() + ' · ' : '') + formatSize(normalized.size)
        const downloadLink = item.querySelector('a')
        const valueInput = item.querySelector('[data-file-value]')

        if (normalized.download) {
            downloadLink.href = normalized.download
            downloadLink.setAttribute('aria-label', normalized.name + ' 다운로드')
        } else {
            downloadLink.remove()
        }

        if (normalized.id && root.dataset.valueName) {
            valueInput.name = root.dataset.valueName
            valueInput.value = normalized.id
        } else {
            valueInput.remove()
        }

        if (type == 'image') {
            const image = document.createElement('img')
            image.src = normalized.preview
            image.alt = ''
            image.loading = 'lazy'
            item.querySelector('.app-file-thumbnail').replaceChildren(image)
        }

        state.list.appendChild(item)
        state.files.push(normalized)
    }

    // 파일 개수에 맞춰 드롭존의 전체 작업 버튼을 표시
    const refresh = function (root, state) {
        const fileCount = state.list.children.length
        const totalSize = state.files.reduce(function (sum, file) {
            return sum + Number(file.size || 0)
        }, 0)

        root.classList.toggle('app-file-has-files', fileCount > 0)
        state.toolbar.hidden = fileCount == 0
        state.downloadAllButton.hidden = !state.files.some(function (file) {
            return Boolean(file.download)
        })
        state.summary.textContent = fileCount ? '파일 ' + fileCount + '개 · ' + formatSize(totalSize) : ''
    }

    // 아직 업로드하지 않은 파일을 실제 file input에 유지해 일반 폼 저장에 포함한다
    const syncLocalFiles = function (state) {
        const transfer = new DataTransfer()

        state.files.forEach(function (file) {
            if (file.raw) {
                transfer.items.add(file.raw)
            }
        })
        state.input.files = transfer.files
    }

    // 현재 파일 순서에 맞춰 큰 미리보기와 캐러셀 버튼을 표시
    const showPreview = function (root, state, index) {
        if (!state.files.length || root.dataset.previewable != '1') {
            return false
        }

        state.previewIndex = (index + state.files.length) % state.files.length
        const file = state.files[state.previewIndex]
        const type = getFileType(file)
        const content = state.viewer.querySelector('[data-file-viewer-content]')

        state.previewType = type
        state.zoom = 1
        state.rotation = 0
        state.transformTarget = null
        content.replaceChildren()

        if (type == 'image') {
            const image = document.createElement('img')
            image.src = file.preview
            image.alt = file.name
            content.appendChild(image)
            state.transformTarget = image
        } else if (type == 'video') {
            const video = document.createElement('video')
            video.src = file.preview
            video.controls = true
            content.appendChild(video)
        } else if (type == 'audio') {
            const audio = document.createElement('audio')
            audio.src = file.preview
            audio.controls = true
            content.appendChild(audio)
        } else if (type == 'pdf' || type == 'document') {
            const frame = document.createElement('iframe')
            frame.src = file.preview
            frame.title = file.name
            content.appendChild(frame)
        } else {
            const empty = document.createElement('div')
            empty.className = 'app-file-viewer-empty'
            empty.innerHTML = '<span data-slot="icon" data-icon="lucide:file" aria-hidden="true"></span>'
            const name = document.createElement('span')
            name.textContent = file.name
            empty.appendChild(name)
            content.appendChild(empty)
        }

        state.viewer.querySelector('[data-file-viewer-name]').textContent = file.name
        state.viewer.querySelector('[data-file-viewer-meta]').textContent =
            (file.extension ? file.extension.toUpperCase() + ' · ' : '') + formatSize(file.size)
        state.viewer.querySelector('[data-file-viewer-count]').textContent =
            (state.previewIndex + 1) + ' / ' + state.files.length
        const viewerDownload = state.viewer.querySelector('[data-file-viewer-download]')
        const downloadUrl = file.download || file.preview
        viewerDownload.hidden = !downloadUrl
        viewerDownload.href = downloadUrl || '#'
        state.viewer.querySelector('[data-file-prev]').hidden = state.files.length < 2
        state.viewer.querySelector('[data-file-next]').hidden = state.files.length < 2
        state.viewer.querySelectorAll('[data-file-fit], [data-file-zoom-out], [data-file-zoom-in], [data-file-rotate]')
            .forEach(function (button) {
                button.disabled = type != 'image'
            })

        return Boolean(file.preview || file.download)
    }

    // 한 파일 컴포넌트의 업로드, 삭제, 미리보기 이벤트를 연결
    const init = function (root, adapter) {
        if (!root) {
            return
        }

        if (fileStates.has(root)) {
            if (adapter) {
                fileStates.get(root).adapter = adapter
            }
            return
        }

        const state = {
            input: root.querySelector('[data-file-input]'),
            dropzone: root.querySelector('[data-file-dropzone]'),
            list: root.querySelector('[data-file-list]'),
            toolbar: root.querySelector('[data-file-toolbar]'),
            downloadAllButton: root.querySelector('[data-file-download-all]'),
            deleteAllButton: root.querySelector('[data-file-delete-all]'),
            summary: root.querySelector('[data-file-summary]'),
            status: root.querySelector('[data-file-status]'),
            viewer: root.querySelector('[data-file-viewer]'),
            files: [],
            previewIndex: 0,
            previewType: '',
            zoom: 1,
            rotation: 0,
            transformTarget: null,
            viewerTrigger: null,
            pending: 0,
            adapter: adapter || null
        }

        fileStates.set(root, state)

        // 전체 화면 뷰어를 닫고 파일을 열었던 항목으로 포커스를 돌림
        const closeViewer = function () {
            state.viewer.hidden = true
            state.viewer.querySelector('[data-file-viewer-content]').replaceChildren()
            state.transformTarget = null

            if (!document.querySelector('.app-file-viewer:not([hidden])')) {
                document.body.classList.remove('app-file-viewer-open')
            }

            if (state.viewerTrigger && document.contains(state.viewerTrigger)) {
                state.viewerTrigger.focus()
            }
        }

        // 이미지에만 확대와 회전값을 적용
        const updateViewerTransform = function () {
            if (state.transformTarget) {
                state.transformTarget.style.transform =
                    'scale(' + state.zoom + ') rotate(' + state.rotation + 'deg)'
            }
        }

        // 서버에서 전달한 기존 목록도 새 업로드 응답과 같은 형태로 읽음
        Array.from(state.list.querySelectorAll('.app-file-item')).forEach(function (item) {
            const file = normalizeFile({
                id: item.dataset.fileId,
                name: item.dataset.fileName,
                size: item.dataset.fileSize,
                extension: item.dataset.fileExtension,
                type: item.dataset.fileType,
                previewType: item.dataset.filePreviewType,
                mime: item.dataset.fileMime,
                download: item.dataset.fileDownload,
                preview: item.dataset.filePreview
            })

            state.files.push(file)
            item.querySelector('.app-file-meta').textContent =
                (file.extension ? file.extension.toUpperCase() + ' · ' : '') + formatSize(file.size)

            if (getFileType(file) == 'image') {
                const image = document.createElement('img')
                image.src = file.preview
                image.alt = ''
                image.loading = 'lazy'
                item.querySelector('.app-file-thumbnail').replaceChildren(image)
            }
        })

        const upload = async function (selectedFiles, source) {
            let files = Array.from(selectedFiles || [])

            if (!root.dataset.multiple || root.dataset.multiple == '0') {
                files = files.slice(0, 1)
            }

            if (!files.length) {
                return
            }

            if (state.files.length + files.length > Number(root.dataset.maxFiles)) {
                state.status.textContent = '최대 ' + root.dataset.maxFiles + '개까지 추가할 수 있습니다'
                root.classList.add('app-file-error')
                return
            }

            const maxBytes = Number(root.dataset.maxFileSize) * 1024 * 1024
            const acceptRules = String(root.dataset.accept || '*/*').split(',').map(function (rule) {
                return rule.trim().toLowerCase()
            })
            const invalidFile = files.find(function (file) {
                const extension = '.' + String(file.name.split('.').pop() || '').toLowerCase()
                const accepted = acceptRules.includes('*/*') || acceptRules.some(function (rule) {
                    return rule == extension || (rule.endsWith('/*') && file.type.startsWith(rule.slice(0, -1))) || rule == file.type
                })

                return file.size > maxBytes || !accepted
            })

            if (invalidFile) {
                state.status.textContent = invalidFile.size > maxBytes
                    ? '파일당 ' + root.dataset.maxFileSize + 'MB까지 가능합니다'
                    : '지원하지 않는 파일 형식입니다'
                root.classList.add('app-file-error')
                return
            }

            // 어댑터가 없는 신규 입력 화면은 서버로 보내지 않고 폼의 file input에 보관한다
            if (!state.adapter || typeof state.adapter.upload != 'function') {
                files.forEach(function (file) {
                    addFileItem(root, state, {
                        name: file.name,
                        size: file.size,
                        extension: String(file.name.split('.').pop() || '').toLowerCase(),
                        mime: file.type,
                        type: file.type,
                        preview: URL.createObjectURL(file),
                        raw: file
                    })
                })
                syncLocalFiles(state)
                state.status.textContent = ''
                root.classList.remove('app-file-error')
                refresh(root, state)
                if (source == 'paste') {
                    root.dispatchEvent(new CustomEvent('app-file:pasted', {detail: files}))
                }
                return
            }

            state.pending++
            root.classList.add('app-file-uploading')
            state.status.textContent = '업로드 중'

            try {
                const uploadedFiles = await state.adapter.upload(files)

                uploadedFiles.forEach(function (file) {
                    addFileItem(root, state, file)
                })
                state.status.textContent = ''
                root.classList.remove('app-file-error')
                root.dispatchEvent(new CustomEvent('app-file:uploaded', {detail: uploadedFiles}))
                if (source == 'paste') {
                    root.dispatchEvent(new CustomEvent('app-file:pasted', {detail: uploadedFiles}))
                }
            } catch (error) {
                state.status.textContent = error.message || '파일을 업로드하지 못했습니다'
                root.classList.add('app-file-error')
            } finally {
                state.pending--
                state.input.value = ''
                root.classList.toggle('app-file-uploading', state.pending > 0)
                refresh(root, state)
            }
        }

        state.upload = upload
        bindPasteListener()

        root.addEventListener('pointerenter', function () {
            activePasteRoot = root
        })
        root.addEventListener('focusin', function () {
            activePasteRoot = root
        })

        state.dropzone.addEventListener('click', function (event) {
            if (event.target.closest('.app-file-tool')) {
                return
            }

            if (!root.classList.contains('app-file-disabled')) {
                state.input.click()
            }
        })
        state.dropzone.addEventListener('keydown', function (event) {
            if (event.target.closest('.app-file-tool')) {
                return
            }

            if (event.key == 'Enter' || event.key == ' ') {
                event.preventDefault()
                state.dropzone.click()
            }
        })
        state.input.addEventListener('change', function () {
            upload(this.files)
        })

        ;['dragenter', 'dragover'].forEach(function (eventName) {
            root.addEventListener(eventName, function (event) {
                event.preventDefault()
                root.classList.add('app-file-dragging')
            })
        })
        ;['dragleave', 'drop'].forEach(function (eventName) {
            root.addEventListener(eventName, function (event) {
                event.preventDefault()
                root.classList.remove('app-file-dragging')
            })
        })
        root.addEventListener('drop', function (event) {
            if (!root.classList.contains('app-file-disabled')) {
                upload(event.dataTransfer.files)
            }
        })

        state.list.addEventListener('click', async function (event) {
            const item = event.target.closest('.app-file-item')

            if (!item) {
                return
            }

            if (event.target.closest('[data-file-open]')) {
                state.viewerTrigger = event.target.closest('[data-file-open]')
                if (!showPreview(root, state, Array.from(state.list.children).indexOf(item))) {
                    return
                }
                state.viewer.hidden = false
                document.body.classList.add('app-file-viewer-open')
                state.viewer.querySelector('[data-file-viewer-close]').focus()
                return
            }

            if (!event.target.closest('[data-file-delete]') || !window.confirm('파일을 삭제할까요?')) {
                return
            }

            try {
                const index = Array.from(state.list.children).indexOf(item)
                const file = state.files[index]

                if (file.raw) {
                    if (file.preview.startsWith('blob:')) {
                        URL.revokeObjectURL(file.preview)
                    }
                } else if (state.adapter && typeof state.adapter.remove == 'function') {
                    await state.adapter.remove(file)
                }

                state.files.splice(index, 1)
                item.remove()
                syncLocalFiles(state)
                state.status.textContent = ''
                refresh(root, state)
                root.dispatchEvent(new CustomEvent('app-file:deleted', {detail: Number(item.dataset.fileId)}))
            } catch (error) {
                state.status.textContent = error.message || '파일을 삭제하지 못했습니다'
            }
        })

        state.downloadAllButton.addEventListener('click', async function () {
            const downloadFiles = state.files.filter(function (file) {
                return Boolean(file.download)
            })

            if (!downloadFiles.length) {
                return
            }

            if (downloadFiles.length == 1) {
                window.location.href = downloadFiles[0].download
                return
            }

            if (root.dataset.downloadAllUrl) {
                window.location.href = root.dataset.downloadAllUrl
                return
            }

            state.status.textContent = 'ZIP 준비 중'

            try {
                if (!state.adapter || typeof state.adapter.downloadAll != 'function') {
                    throw new Error('전체 다운로드 연결이 필요합니다')
                }

                await state.adapter.downloadAll(downloadFiles)
                state.status.textContent = ''
            } catch (error) {
                state.status.textContent = error.message || 'ZIP 파일을 만들지 못했습니다'
            }
        })

        if (state.deleteAllButton) {
            state.deleteAllButton.addEventListener('click', async function () {
                if (!window.confirm('전체 파일을 삭제할까요?')) {
                    return
                }

                await window.appFile.removeAll(root)
            })
        }

        state.viewer.querySelector('[data-file-prev]').addEventListener('click', function () {
            showPreview(root, state, state.previewIndex - 1)
        })
        state.viewer.querySelector('[data-file-next]').addEventListener('click', function () {
            showPreview(root, state, state.previewIndex + 1)
        })
        state.viewer.querySelector('[data-file-viewer-close]').addEventListener('click', closeViewer)
        state.viewer.querySelector('[data-file-fit]').addEventListener('click', function () {
            state.zoom = 1
            state.rotation = 0
            updateViewerTransform()
        })
        state.viewer.querySelector('[data-file-zoom-out]').addEventListener('click', function () {
            state.zoom = Math.max(0.25, state.zoom - 0.25)
            updateViewerTransform()
        })
        state.viewer.querySelector('[data-file-zoom-in]').addEventListener('click', function () {
            state.zoom = Math.min(4, state.zoom + 0.25)
            updateViewerTransform()
        })
        state.viewer.querySelector('[data-file-rotate]').addEventListener('click', function () {
            state.rotation = (state.rotation + 90) % 360
            updateViewerTransform()
        })
        state.viewer.addEventListener('keydown', function (event) {
            if (event.key == 'Escape') {
                closeViewer()
            }
            if (event.key == 'ArrowLeft') {
                showPreview(root, state, state.previewIndex - 1)
            }
            if (event.key == 'ArrowRight') {
                showPreview(root, state, state.previewIndex + 1)
            }
        })

        refresh(root, state)
    }

    // 동적으로 추가한 운전자 행에서도 같은 컴포넌트를 사용할 수 있도록 공개
    window.appFile = {
        init: init,
        hasPending: function (scope) {
            return Boolean((scope || document).querySelector('.app-file-uploading'))
        },
        hasError: function (scope) {
            return Boolean((scope || document).querySelector('.app-file-error'))
        },
        setName: function (root, name, valueName) {
            root.dataset.inputName = name
            root.dataset.valueName = valueName || ''
            root.querySelector('[data-file-input]').name = name
            root.querySelectorAll('[data-file-value]').forEach(function (input) {
                input.name = valueName || ''
            })
        },
        setAdapter: function (root, adapter) {
            init(root, adapter)
        },
        reset: function (root, options) {
            const viewer = root.querySelector('[data-file-viewer]')
            const id = options.id

            root.id = id
            root.dataset.inputName = options.name
            root.dataset.valueName = options.valueName || ''
            root.querySelector('[data-file-input]').id = id + '-input'
            root.querySelector('[data-file-input]').name = options.name
            fileStates.get(root)?.files.forEach(function (file) {
                if (file.preview && file.preview.startsWith('blob:')) {
                    URL.revokeObjectURL(file.preview)
                }
            })
            root.querySelector('[data-file-list]').replaceChildren()
            root.querySelector('[data-file-status]').textContent = ''
            root.classList.remove('app-file-error', 'app-file-uploading', 'app-file-dragging')
            viewer.id = id + '-preview'
            viewer.querySelector('[data-file-viewer-content]').replaceChildren()
            viewer.querySelector('[data-file-viewer-name]').textContent = ''
            viewer.querySelector('[data-file-viewer-meta]').textContent = ''
            viewer.querySelector('[data-file-viewer-count]').textContent = ''
            viewer.hidden = true
            delete root.dataset.appFileReady
            fileStates.delete(root)
        },
        removeAll: async function (root) {
            const state = fileStates.get(root)

            if (!state || !state.files.length) {
                return true
            }

            try {
                const uploadedFiles = state.files.filter(function (file) {
                    return !file.raw
                })

                if (uploadedFiles.length && state.adapter && typeof state.adapter.removeAll == 'function') {
                    await state.adapter.removeAll(uploadedFiles)
                }

                state.files.forEach(function (file) {
                    if (file.preview && file.preview.startsWith('blob:')) {
                        URL.revokeObjectURL(file.preview)
                    }
                })
                state.files = []
                state.list.replaceChildren()
                syncLocalFiles(state)
                state.status.textContent = ''
                root.classList.remove('app-file-error')
                refresh(root, state)
                return true
            } catch (error) {
                state.status.textContent = error.message || '파일을 삭제하지 못했습니다'
                return false
            }
        }
    }

    if (window.AppUI && typeof window.AppUI.register == 'function') {
        window.AppUI.register('file-upload', '[data-app-file]', function (root) {
            init(root)
        })
    } else {
        document.querySelectorAll('[data-app-file]').forEach(init)
    }
})()
