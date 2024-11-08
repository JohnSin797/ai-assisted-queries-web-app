<template>
    <div class="w-full min-h-screen p-10 space-y-5">
        <!-- <section class="w-full p-5 rounded-lg shadow-xl border border-gray-400 flex justify-between items-center" v-if="notice">
            <p class="text-xs">
                <span class="text-amber-400 font-bold">Please note:</span>
                Knowledge Base file formatting should use Heading styles as it will be used to programmatically segment the file into sections.
            </p>
            <button @click="()=>notice=false" class="rounded text-black text-gray-400 hover:text-black">
                <v-icon name="bi-x" />
            </button>
        </section>
        <section class="w-full p-10 rounded-lg shadow-xl border border-gray-400">
            <header class="mb-5 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Knowledge Base</h1>
                <label for="file-upload" class="p-2 text-sm bg-blue-400 hover:bg-blue-600 text-white font-bold rounded cursor-pointer">Upload</label>
                <input @change="handleUpload" type="file" name="file-upload" id="file-upload" class="hidden">
            </header>
            <div class="relative h-80 2xl:h-96 overflow-y-auto">
                <table class="table-auto w-full">
                    <thead class="sticky top-0 bg-gray-400 text-white">
                        <tr>
                            <th>File</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(file, index) in this.files" :key="index">
                            <td class="p-2 border-b border-gray-400 font-bold">{{ file }}</td>
                            <td class="p-2 border-b border-gray-400">
                                <div class="w-full flex flex-wrap justify-center items-center gap-2">
                                    <button @click="confirmDeleteFile(file)" class="p-2 text-xs text-white font-bold bg-red-400 hover:bg-red-600 rounded">
                                        <v-icon name="fa-trash" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section> -->
        <section class="w-full p-10 rounded-lg shadow-xl border border-gray-400">
            <header class="mb-5 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Knowledge Base</h1>
                <router-link class="p-2 text-sm bg-blue-400 hover:bg-blue-600 text-white font-bold rounded" to="/knowledge-base/create">Add new</router-link>
            </header>
            <div class="relative h-80 2xl:h-96 overflow-y-auto">
                <table class="table-fixed w-full text-center">
                    <thead class="sticky top-0 bg-gray-400 text-white">
                        <tr>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(answer, index) in this.answers" :key="index">
                            <td class="p-2 border-b border-gray-400 font-bold">{{ answer.category }}</td>
                            <td class="p-2 border-b border-gray-400 text-indigo-600">{{ answer.sub_category }}</td>
                            <td class="p-2 border-b border-gray-400 text-blue-400">{{ getDate(answer.created_at) }}</td>
                            <td class="p-2 border-b border-gray-400">
                                <div class="w-full flex flex-wrap justify-center items-center gap-2">
                                    <button class="p-1 text-xs rounded bg-green-400 hover:bg-green-600 text-white">
                                        <v-icon name="fa-eye" />
                                    </button>
                                    <button class="p-1 text-xs rounded bg-blue-400 hover:bg-blue-600 text-white">
                                        <v-icon name="fa-pencil-alt" />
                                    </button>
                                    <button @click="confirmDelete(answer.id)" class="p-1 text-xs rounded bg-red-400 hover:bg-red-600 text-white">
                                        <v-icon name="fa-trash" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</template>

<script>
    import axios from 'axios';
    import alertify from 'alertifyjs';
    import 'alertifyjs/build/css/alertify.css';
    import 'alertifyjs/build/css/themes/default.css';

    export default {
        data() {
            return {
                answers: [],
                files: [],
                notice: true,
            }
        },
        methods: {
            handleUpload(event) {
                alertify.confirm(
                    'Upload Confirmation',
                    'Continue upload?',
                    () => {
                        const file = event.target.files[0]
                        this.uploadFile(file)
                    },
                    () => {
                        alertify.error('Canceled')
                    }
                )
            },
            uploadFile(file) {
                const formData = new FormData()
                formData.append('file', file)
                axios.post('/knowledge-base/store', formData)
                .then(response => {
                    alertify.success('SUCCESS')
                    this.files = response.data?.files
                })
                .catch(error => {
                    console.log(error)
                    alertify.error('ERROR')
                    alertify.alert(
                        'Error',
                        error?.response?.data?.message
                    )
                })
            },
            confirmDeleteFile(file) {
                alertify.confirm(
                    'Delete',
                    `Are you sure you want to delete ${file}?`,
                    () => {
                        this.deleteFile(file)
                    },
                    () => {
                        alertify.error('Canceled')
                    }
                )
            },
            deleteFile(file) {
                axios.post('/knowledge-base/delete', { file_name: file})
                .then(response => {
                    this.files = response.data?.files
                })
                .catch(error => {
                    console.log(error)
                })
            },
            getKnowledgeBase() {
                axios.post('/knowledge-base')
                .then(response => {
                    this.files = response.data?.files
                })
                .catch(error => {
                    console.log(error)
                })
            },
            getAnswers() {
                axios.post('/answer')
                .then(response => {
                    this.answers = response.data?.answers
                })
                .catch(error => {
                    console.log(error)
                })
            },
            getDate(date) {
                const formatted = new Date(date)
                return formatted.toLocaleDateString('en-US')
            },
            confirmDelete(id) {
                alertify.confirm(
                    'Delete Confirmation',
                    'Are you sure you want to delete?',
                    () => {
                        this.deleteItem(id)
                    },
                    function() {
                        alertify.error('CANCELED')
                    }
                )
            },
            deleteItem(id) {
                axios.post('/answer/delete', { answer_id: id })
                .then(response => {
                    this.answers = response.data?.answers
                    alertify.success('SUCCESS')
                })
                .catch(error => {
                    console.log(error)
                    alertify.error('ERROR')
                    alertify.alert(error?.response?.data?.message)
                })
            },
        },
        mounted() {
            this.getAnswers()
            this.getKnowledgeBase()
        }
    }
</script>