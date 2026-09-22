import Quill from 'quill';
import 'quill/dist/quill.snow.css';

window.Quill = Quill;

window.initEditor = function (containerId, options = {}) {
    const container = document.getElementById(containerId);
    if (!container) return null;

    const toolbarOptions = [
        [{ header: [2, 3, 4, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['link'],
        ['clean'],
    ];

    const quill = new Quill(`#${containerId}`, {
        theme: 'snow',
        modules: {
            toolbar: toolbarOptions,
        },
        placeholder: options.placeholder || 'Write something...',
    });

    const hiddenInput = document.getElementById(containerId + '_input');
    if (hiddenInput) {
        quill.on('text-change', function () {
            hiddenInput.value = quill.root.innerHTML;
        });

        if (hiddenInput.value) {
            quill.root.innerHTML = hiddenInput.value;
        }
    }

    return quill;
};
