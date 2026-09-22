@props([
    'name',
    'value' => '',
    'placeholder' => 'Write something...',
    'id' => null,
])

@php
    $editorId = $id ?? 'editor-' . md5($name);
@endphp

<div class="forum-editor">
    <div id="{{ $editorId }}" style="min-height: 200px;">{!! $value !!}</div>
    <input type="hidden" name="{{ $name }}" id="{{ $editorId }}_input" value="{{ $value }}">
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.forum-editor').forEach(function (el) {
                    const container = el.querySelector('[id]:not([id$="_input"])');
                    if (container && container.id) {
                        initEditor(container.id, {
                            placeholder: '{{ addslashes($placeholder) }}'
                        });
                    }
                });
            });
        </script>
    @endpush
@endonce
