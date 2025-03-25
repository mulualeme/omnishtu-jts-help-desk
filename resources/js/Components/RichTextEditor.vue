<script setup>
import { Editor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import Link from "@tiptap/extension-link";
import Image from "@tiptap/extension-image";
import Placeholder from "@tiptap/extension-placeholder";
import { ref, onMounted, onBeforeUnmount } from "vue";

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },
    placeholder: {
        type: String,
        default: "Write something...",
    },
});

const emit = defineEmits(["update:modelValue"]);
const editor = ref(null);
const fileInput = ref(null);
const videoInput = ref(null);

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const fileExtension = file.name.split(".").pop().toLowerCase();
            const isImage = ["jpg", "jpeg", "png", "gif", "webp"].includes(
                fileExtension
            );
            const isVideo = ["mp4", "webm", "ogg"].includes(fileExtension);

            if (isImage) {
                if (editor.value) {
                    editor.value
                        .chain()
                        .focus()
                        .setImage({ src: e.target.result })
                        .run();
                }
            } else if (isVideo) {
                if (editor.value) {
                    editor.value
                        .chain()
                        .focus()
                        .insertContent(
                            `
                            <div class="video-file my-4">
                                <video 
                                    controls 
                                    class="w-full rounded-lg shadow-sm"
                                    style="max-height: 400px;"
                                >
                                    <source src="${e.target.result}" type="video/${fileExtension}">
                                    Your browser does not support the video tag.
                                </video>
                                <div class="text-sm text-gray-500 mt-1">${file.name}</div>
                            </div>
                            `
                        )
                        .run();
                }
            } else {
                // For non-image/video files, create a download link
                const fileName = file.name;
                const fileSize = (file.size / 1024).toFixed(2) + " KB";
                if (editor.value) {
                    editor.value
                        .chain()
                        .focus()
                        .insertContent(
                            `
                        <div class="file-attachment p-3 my-2 border rounded-lg bg-gray-50 flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <div>
                                <div class="text-sm font-medium">${fileName}</div>
                                <div class="text-xs text-gray-500">${fileSize}</div>
                            </div>
                            <a href="${e.target.result}" download="${fileName}" class="ml-auto text-sm text-blue-600 hover:text-blue-800">Download</a>
                        </div>
                    `
                        )
                        .run();
                }
            }
        };
        reader.readAsDataURL(file);
    }
};

onMounted(() => {
    editor.value = new Editor({
        content: props.modelValue,
        extensions: [
            StarterKit.configure({
                heading: {
                    levels: [2],
                },
                bulletList: {
                    keepMarks: true,
                    keepAttributes: false,
                    HTMLAttributes: {
                        class: "list-disc list-outside",
                    },
                },
                orderedList: {
                    keepMarks: true,
                    keepAttributes: false,
                    HTMLAttributes: {
                        class: "list-decimal list-outside",
                    },
                },
            }),
            Link.configure({
                openOnClick: false,
                HTMLAttributes: {
                    class: "text-blue-600 hover:text-blue-800",
                },
            }),
            Image.configure({
                inline: true,
                allowBase64: true,
            }),
            Placeholder.configure({
                placeholder: props.placeholder,
            }),
        ],
        editorProps: {
            attributes: {
                class: "prose prose-sm sm:prose lg:prose-lg xl:prose-xl focus:outline-none",
            },
        },
        onUpdate: ({ editor }) => {
            emit("update:modelValue", editor.getHTML());
        },
    });
});

onBeforeUnmount(() => {
    if (editor.value) {
        editor.value.destroy();
    }
});

const toggleHeading = () => {
    if (editor.value) {
        editor.value.chain().focus().toggleHeading({ level: 2 }).run();
    }
};

const toggleBold = () => {
    if (editor.value) {
        editor.value.chain().focus().toggleBold().run();
    }
};

const toggleItalic = () => {
    if (editor.value) {
        editor.value.chain().focus().toggleItalic().run();
    }
};

const toggleBulletList = () => {
    if (editor.value) {
        editor.value.chain().focus().toggleBulletList().run();
    }
};

const toggleOrderedList = () => {
    if (editor.value) {
        editor.value.chain().focus().toggleOrderedList().run();
    }
};

const addImage = () => {
    const input = document.createElement("input");
    input.type = "file";
    input.accept = "image/*";
    input.onchange = async () => {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                if (editor.value) {
                    editor.value
                        .chain()
                        .focus()
                        .setImage({ src: e.target.result })
                        .run();
                }
            };
            reader.readAsDataURL(file);
        }
    };
    input.click();
};

const addLink = () => {
    if (editor.value) {
        const previousUrl = editor.value.getAttributes("link").href;
        const url = window.prompt("URL", previousUrl);

        if (url === null) {
            return;
        }

        if (url === "") {
            editor.value
                .chain()
                .focus()
                .extendMarkRange("link")
                .unsetLink()
                .run();
            return;
        }

        editor.value
            .chain()
            .focus()
            .extendMarkRange("link")
            .setLink({ href: url })
            .run();
    }
};

const isActive = (type, attrs = {}) => {
    if (editor.value) {
        return editor.value.isActive(type, attrs);
    }
    return false;
};
</script>

<template>
    <div class="rich-text-editor border border-gray-300 rounded-md">
        <div
            class="toolbar border-b border-gray-300 p-2 flex items-center gap-2 flex-wrap"
        >
            <!-- Text Formatting -->
            <div class="flex items-center gap-2 pr-2 border-r border-gray-300">
                <button
                    type="button"
                    @click="toggleHeading"
                    class="p-2 hover:bg-gray-100 rounded"
                    :class="{
                        'bg-gray-200': isActive('heading', { level: 2 }),
                    }"
                    title="Heading"
                >
                    H2
                </button>
                <button
                    type="button"
                    @click="toggleBold"
                    class="p-2 hover:bg-gray-100 rounded"
                    :class="{ 'bg-gray-200': isActive('bold') }"
                    title="Bold"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            d="M12.8 14c-.4.4-1 .6-1.7.6H6.5V5h4.4c.7 0 1.3.2 1.7.6.4.4.6.9.6 1.5 0 .4-.1.8-.3 1.1-.2.3-.5.6-.8.7.5.1.9.4 1.2.7.3.3.4.7.4 1.2 0 .6-.2 1.1-.6 1.5zM9 7.4v2h1.5c.3 0 .6-.1.8-.3.2-.2.3-.4.3-.7 0-.3-.1-.5-.3-.7-.2-.2-.5-.3-.8-.3H9zm0 4v2h1.7c.3 0 .6-.1.8-.3.2-.2.3-.4.3-.7s-.1-.5-.3-.7c-.2-.2-.5-.3-.8-.3H9z"
                        />
                    </svg>
                </button>
                <button
                    type="button"
                    @click="toggleItalic"
                    class="p-2 hover:bg-gray-100 rounded"
                    :class="{ 'bg-gray-200': isActive('italic') }"
                    title="Italic"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path d="M14 5H6v2h3.5l-3 6H3v2h8v-2H7.5l3-6H14V5z" />
                    </svg>
                </button>
            </div>

            <!-- Lists -->
            <div class="flex items-center gap-2 pr-2 border-r border-gray-300">
                <button
                    type="button"
                    @click="toggleBulletList"
                    class="p-2 hover:bg-gray-100 rounded"
                    :class="{ 'bg-gray-200': isActive('bulletList') }"
                    title="Bullet List"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            d="M7 6h9v2H7V6zm0 4h9v2H7v-2zm0 4h9v2H7v-2zM4 7a1 1 0 110-2 1 1 0 010 2zm0 4a1 1 0 110-2 1 1 0 010 2zm0 4a1 1 0 110-2 1 1 0 010 2z"
                        />
                    </svg>
                </button>
                <button
                    type="button"
                    @click="toggleOrderedList"
                    class="p-2 hover:bg-gray-100 rounded"
                    :class="{ 'bg-gray-200': isActive('orderedList') }"
                    title="Numbered List"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path d="M3 5h12v2H3V5zm0 4h12v2H3V9zm0 4h12v2H3v-2z" />
                    </svg>
                </button>
            </div>

            <!-- Media -->
            <div class="flex items-center gap-2">
                <!-- Image Upload -->
                <button
                    type="button"
                    @click="addImage"
                    class="p-2 hover:bg-gray-100 rounded"
                    title="Upload Image"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2H4zm12 12H4a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2z"
                            clip-rule="evenodd"
                        />
                        <path d="M8.5 13.5l2.5-3.5L14.5 15h-9l3-4z" />
                    </svg>
                </button>

                <!-- Video Upload -->
                <button
                    type="button"
                    @click="$refs.videoInput.click()"
                    class="inline-flex items-center gap-1 px-3 py-2 hover:bg-gray-100 rounded-md"
                    title="Upload Video File"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"
                        />
                    </svg>
                </button>

                <!-- File Upload -->
                <button
                    type="button"
                    @click="$refs.fileInput.click()"
                    class="p-2 hover:bg-gray-100 rounded"
                    title="Upload File (PDF, DOC, etc.)"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>

                <!-- Link -->
                <button
                    type="button"
                    @click="addLink"
                    class="p-2 hover:bg-gray-100 rounded"
                    :class="{ 'bg-gray-200': isActive('link') }"
                    title="Add Link"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>
        </div>
        <EditorContent :editor="editor" class="prose max-w-none p-4" />

        <!-- Hidden File Inputs -->
        <input
            ref="fileInput"
            type="file"
            @change="handleFileUpload"
            class="hidden"
            accept=".pdf,.doc,.docx,.xls,.xlsx,.txt"
        />
        <input
            ref="videoInput"
            type="file"
            @change="handleFileUpload"
            class="hidden"
            accept="video/*"
        />
    </div>
</template>

<style>
.rich-text-editor {
    min-height: 200px;
}
.ProseMirror {
    min-height: 150px;
    outline: none;
}
.ProseMirror p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    float: left;
    color: #adb5bd;
    pointer-events: none;
    height: 0;
}
.ProseMirror img {
    max-width: 100%;
    height: auto;
    margin: 1rem 0;
}
.ProseMirror ul {
    list-style-type: disc;
    padding-left: 1.5rem;
    margin: 1rem 0;
}
.ProseMirror ol {
    list-style-type: decimal;
    padding-left: 1.5rem;
    margin: 1rem 0;
}
.ProseMirror li {
    margin: 0.5rem 0;
}
.ProseMirror li > p {
    margin: 0;
}
.ProseMirror h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 1rem 0;
}
.file-attachment {
    max-width: 100%;
    overflow: hidden;
}
.video-embed {
    position: relative;
    width: 100%;
}
.video-embed iframe {
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
}
.video-file {
    max-width: 100%;
}
.video-file video {
    background-color: #000;
    width: 100%;
    border-radius: 0.5rem;
}
.video-file .text-sm {
    margin-top: 0.5rem;
    color: #6b7280;
}
</style>
