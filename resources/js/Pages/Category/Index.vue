<template>
<app-layout>
    <template #header>
        <h2 class="font-semibold text-xl leading-tight">
            Manage <a href="/category" class="hover:text-green-500 text-green-800">Category</a>
        </h2>
    </template>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md my-3" role="alert" v-if="$page.props.flash.message">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ $page.props.flash.message }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <button @click="openModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create New Category</button>
                    <div class="flex relative h-1/2 w-1/2">
                        <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                                <path d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
                                </path>
                            </svg>
                        </span>
                        <input type="text" v-model="term" @keyup="search" placeholder="Search" class="appearance-none rounded-r rounded-l sm:rounded-l-none border border-gray-400 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none" />
                    </div>
                </div>
                <table class="table-fixed w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 w-20">No.</th>
                            <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in categories.data" :key="row.id">
                            <td class="border px-4 py-2">{{ (categories.current_page -1) * categories.per_page + index + 1 }}</td>
                            <td class="border px-4 py-2">{{ row.title }}</td>
                            <td class="border px-4 py-2 flex flex-row justify-center items-center">
                                <button @click="edit(row)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mx-3">Edit</button>
                                <button @click="deleteRow(row)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mx-3">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between          ">
                    <span class="text-xs xs:text-sm text-gray-900">
                        Showing {{ categories.from }} to {{ categories.to }} of {{ categories.total }} Entries
                    </span>
                    <div class="flex justify-between w-full mt-2 xs:mt-0">
                        <inertia-link as="button" :href="categories.prev_page_url || '' " class="border border-green-500 text-green-500 block rounded-sm font-bold py-4 px-6 mr-2 flex items-center hover:bg-green-500 hover:text-white">
                            <svg class="h-5 w-5 mr-2 fill-current" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-49 141 512 512" style="enable-background:new -49 141 512 512;" xml:space="preserve">
                                <path id="XMLID_10_" d="M438,372H36.355l72.822-72.822c9.763-9.763,9.763-25.592,0-35.355c-9.763-9.764-25.593-9.762-35.355,0 l-115.5,115.5C-46.366,384.01-49,390.369-49,397s2.634,12.989,7.322,17.678l115.5,115.5c9.763,9.762,25.593,9.763,35.355,0 c9.763-9.763,9.763-25.592,0-35.355L36.355,422H438c13.808,0,25-11.193,25-25S451.808,372,438,372z"></path>
                            </svg>
                            Previous page
                        </inertia-link>
                        <inertia-link as="button" :href="categories.next_page_url || '' " class="border border-green-500 bg-green-500 text-white block rounded-sm font-bold py-4 px-6 ml-2 flex items-center">
                            Next page
                            <svg class="h-5 w-5 ml-2 fill-current" clasversion="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                viewBox="-49 141 512 512" style="enable-background:new -49 141 512 512;" xml:space="preserve">
                            <path id="XMLID_11_" d="M-24,422h401.645l-72.822,72.822c-9.763,9.763-9.763,25.592,0,35.355c9.763,9.764,25.593,9.762,35.355,0
                                l115.5-115.5C460.366,409.989,463,403.63,463,397s-2.634-12.989-7.322-17.678l-115.5-115.5c-9.763-9.762-25.593-9.763-35.355,0
                                c-9.763,9.763-9.763,25.592,0,35.355l72.822,72.822H-24c-13.808,0-25,11.193-25,25S-37.808,422-24,422z"/>
                            </svg>
                        </inertia-link>  
                    <div>
                </div>
                <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                            <form enctype="multipart/form-data">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="">
                                        <div class="mb-4">
                                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">title:</label>
                                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" placeholder="Enter title" v-model="form.title">
                                            <div v-if="$page.props.errors.title" class="text-red-500">{{ $page.props.errors.title }}</div>
                                        </div>                               
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                        <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="!editMode" @click="save(form)">
                                            Save
                                        </button>
                                    </span>
                                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                        <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="editMode" @click="update(form)">
                                            Update
                                        </button>
                                    </span>
                                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                        <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                            Cancel
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</app-layout>
</template>

<script>

import AppLayout from '@/Layouts/AppLayout'
import _ from "lodash"

export default {

    components: {
        AppLayout,
    },
    
    props: {
        categories: Object,
        errors: Object,
    },
    data() {
        return {
            term: '',
            editMode: false,
            isOpen: false,
            form: {
                title: null,
            },
        }
    },

    methods: {

        openModal: function () {
            this.isOpen = true;
        },

        closeModal: function () {
            this.isOpen = false;
            this.reset();
            this.editMode = false;
        },
        reset: function () {
            this.form = {
                title: null,
            }
        },

        save: function (data) {
            this.$inertia.post('/category', data)
            this.reset();
            this.closeModal();
            this.editMode = false;            
        },

        edit: function (data) {
            this.form = Object.assign({}, data);
            this.form.photo = null;
            this.editMode = true;
            this.openModal();
        },

        update: function (data) {
            let forms = new FormData()
            forms.append('_method', 'put')
            forms.append('title', data.title)
            this.$inertia.post('/category/' + data.id, forms)
            this.errors
            this.reset();
            this.closeModal();
        },

        deleteRow: function (data) {

            // if (!confirm('Are you sure want to remove?')) return;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                // cancelButtonColor: '#fff',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    data._method = 'DELETE';
                    this.$inertia.post('/category/' + data.id, data)
                    this.reset();
                    this.closeModal();
                    Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                    )
                }
            })
        },

        search: _.throttle(function () {
            if (!this.trashedMode) {
                this.$inertia.get('/category', {
                    term: this.term
                }, {
                    replace: true,
                    preserveScroll: true
                })
            } else {
                this.$inertia.get('/category/trashed', {
                    term: this.term
                }, {
                    replace: true,
                    preserveScroll: true
                })
            }
        }, 200),

    }

}
</script>
