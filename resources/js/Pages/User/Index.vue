<template>
<app-layout>

    <template #header>

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Manage User 

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
                    <button @click="openModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create New User</button>

                    <div class="flex relative h-1/2 w-1/2">
                        <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                                <path d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
                                </path>
                            </svg>
                        </span>

                        <input type="text" v-model="term" @keyup="search" placeholder="Search" class="appearance-none rounded-r rounded-l sm:rounded-l-none border border-gray-400 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none" />
                    </div>

                    <div class="flex relative h-1/2 text-red-500" v-show="!trashedMode">
                        <span class="h-full absolute inset-y-0 -left-2 flex items-center">
                            <svg class="h-4 w-4 fill-current text-red-800" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 383.411 383.411" style="enable-background:new 0 0 383.411 383.411;" xml:space="preserve">
                                <path d="M335.588,69.176L257.006,7.262C251.838,3.189,242.635,0,236.056,0H53.063c-8.271,0-15,6.729-15,15v353.411
                    c0,8.271,6.729,15,15,15h277.285c8.271,0,15-6.729,15-15V89.293C345.348,82.209,341.152,73.562,335.588,69.176z M179.039,284.85
                    l-53.439-0.368l-5.402-8.877l-21.036-36.817l10.971-19.921l-11.55-6.966l40.954-9.972l9.525,40.309l-11.019-6.563l-8.852,15.498
                    l50.101,0.419L179.039,284.85z M194.444,145.327l-25.273,43.262l-28.731-16.754l23.251-39.837c2.289-3.906,4.106-6.621,4.106-6.621
                    l52.322,0.053l11.83,19.423l11.786-6.558l-11.709,40.492l-39.709-11.777l11.173-6.298L194.444,145.327z M280.907,245.073
                    l-22.102,36.187h-22.742l-0.53,13.477l-28.492-31.063l30.714-27.788l-0.433,12.818l17.845,0.278l-23.802-44.089l29.255-15.823
                    l25.88,46.497L280.907,245.073z M308.882,85.441h-62.499c-5.5,0-10-4.5-10-10V28.289c0-5.5,3.49-7.159,7.756-3.687l66.988,54.526
                    C315.392,82.6,314.382,85.441,308.882,85.441z" />
                            </svg>
                        </span>
                        <inertia-link href='/user/trashed'>Trashed data</inertia-link>
                    </div>

                    <div v-show="trashedMode">
                        <button @click="restoreAll()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Restore All</button>

                        <button @click="deleteAll()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete All</button>
                    </div>

                </div>
                <table class="table-fixed w-full">

                    <thead>

                        <tr class="bg-gray-100">

                            <th class="px-4 py-2 w-20">No.</th>

                            <th class="px-4 py-2">Photo</th>

                            <th class="px-4 py-2">Name</th>

                            <th class="px-4 py-2">email</th>

                            <th class="px-4 py-2">role</th>

                            <th class="px-4 py-2">Action</th>

                        </tr>

                    </thead>

                    <tbody>
                        <tr v-for="(row, index) in users.data" :key="row.id">

                            <td class="border px-4 py-2">{{ (users.current_page -1) * users.per_page + index + 1 }}</td>

                            <td class="border px-4 py-2">
                                <img :src="row.profile_photo_url" :alt="row.name" class="mx-auto w-32 h-32 rounded-full shadow-md">
                            </td>

                            <td class="border px-4 py-2">{{ row.name }}</td>

                            <td class="border px-4 py-2">{{ row.email }}</td>

                            <td class="border px-4 py-2">
                                <span class="rounded-full bg-green-100 px-4" v-for="role in row.roles" :key="role.id"> {{ role.title }} </span>
                            </td>

                            <td class="border px-4 py-2" v-show="!trashedMode">

                                <button @click="edit(row)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</button>

                                <button @click="deleteRow(row)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>

                            </td>

                            <td class="border px-4 py-2" v-show="trashedMode">

                                <button @click="restore(row)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Restore</button>

                                <button @click="forceDeleteRow(row)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete Permanent</button>

                            </td>

                        </tr>

                    </tbody>

                </table>

                <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between          ">
                    <span class="text-xs xs:text-sm text-gray-900">
                        Showing {{ users.from }} to {{ users.to }} of {{ users.total }} Entries
                    </span>
                    <div class="flex justify-between w-full mt-2 xs:mt-0">
                        <inertia-link as="button" :href="users.prev_page_url || '' " class="border border-green-500 text-green-500 block rounded-sm font-bold py-4 px-6 mr-2 flex items-center hover:bg-green-500 hover:text-white">
                            <svg class="h-5 w-5 mr-2 fill-current" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-49 141 512 512" style="enable-background:new -49 141 512 512;" xml:space="preserve">
                                <path id="XMLID_10_" d="M438,372H36.355l72.822-72.822c9.763-9.763,9.763-25.592,0-35.355c-9.763-9.764-25.593-9.762-35.355,0 l-115.5,115.5C-46.366,384.01-49,390.369-49,397s2.634,12.989,7.322,17.678l115.5,115.5c9.763,9.762,25.593,9.763,35.355,0 c9.763-9.763,9.763-25.592,0-35.355L36.355,422H438c13.808,0,25-11.193,25-25S451.808,372,438,372z"></path>
                            </svg>
                            Previous page
                        </inertia-link>

                        <inertia-link as="button" :href="users.next_page_url || '' " class="border border-green-500 bg-green-500 text-white block rounded-sm font-bold py-4 px-6 ml-2 flex items-center">
                            Next page
                            <svg class="h-5 w-5 ml-2 fill-current" clasversion="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-49 141 512 512" style="enable-background:new -49 141 512 512;" xml:space="preserve">
                                <path id="XMLID_11_" d="M-24,422h401.645l-72.822,72.822c-9.763,9.763-9.763,25.592,0,35.355c9.763,9.764,25.593,9.762,35.355,0
                                l115.5-115.5C460.366,409.989,463,403.63,463,397s-2.634-12.989-7.322-17.678l-115.5-115.5c-9.763-9.762-25.593-9.763-35.355,0
                                c-9.763,9.763-9.763,25.592,0,35.355l72.822,72.822H-24c-13.808,0-25,11.193-25,25S-37.808,422-24,422z" />
                            </svg>
                        </inertia-link>

                    </div>
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

                                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">name:</label>

                                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" placeholder="Enter name" v-model="form.name">

                                            <div v-if="$page.props.errors.name" class="text-red-500">{{ $page.props.errors.name }}</div>

                                        </div>

                                        <div class="mb-4">

                                            <label for="exampleFormControlInput2" class="block text-gray-700 text-sm font-bold mb-2">email:</label>

                                            <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" placeholder="Enter email" v-model="form.email">

                                            <div v-if="$page.props.errors.email" class="text-red-500">{{ $page.props.errors.email }}</div>

                                        </div>

                                        <div class="mb-4">

                                            <label for="exampleFormControlInput2" class="block text-gray-700 text-sm font-bold mb-2">password:</label>

                                            <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" placeholder="Enter password" v-model="form.password">

                                            <div v-if="$page.props.errors.password" class="text-red-500">{{ $page.props.errors.password }}</div>

                                        </div>

                                        <div class="mb-4">

                                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">role:</label>

                                            <select v-model="form.role" id="role">
                                                <option v-for="role in roles" :key="role.id" :value="role.id"  :selected="form.role == role.id ? 'selected' : '' "  > {{ role.title  }}</option>
                                            </select>

                                            <div v-if="$page.props.errors.role" class="text-red-500">{{ $page.props.errors.role }}</div>

                                        </div>

                                        <div class="mb-4">

                                            <label for="exampleFormControlInput1" class="block text-gray-700 text-sm font-bold mb-2">photo:</label>

                                            <input type="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" placeholder="Enter Image" @change="onImageChange">

                                            <div v-if="$page.props.errors.profile_photo_path" class="text-red-500">{{ $page.props.errors.profile_photo_path }}</div>

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
        users: Object,
        roles: Array,
        errors: Object,
        trashedMode: Boolean,
    },
    data() {

        return {

            term: '',

            editMode: false,

            isOpen: false,

            form: {

                name: null,

                email: null,

                password: null,

                role: null,

                profile_photo_path: null,

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

                name: null,

                email: null,

                password: null,

                role: null,

                profile_photo_path: null,

            }
        },

        save: function (data) {
            data.role = data.role.id
            this.$inertia.post('/user', data)

            this.reset();

            this.closeModal();

            this.editMode = false;

        },

        edit: function (data) {

            this.form = Object.assign({}, data)
            this.form.role = this.roles[0].id
            this.form.password = null
            this.form.profile_photo_path = null

            this.editMode = true;

            this.openModal();

        },

        update: function (data) {

            let forms = new FormData()
            forms.append('_method', 'put')
            forms.append('name', data.name)
            forms.append('email', data.email)
            forms.append('password', data.password)
            forms.append('role', data.role)
            forms.append('profile_photo_path', data.profile_photo_path)
            this.$inertia.post('/user/' + data.id, forms)

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
                    this.$inertia.post('/user/' + data.id, data)
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
                this.$inertia.get('/user', {
                    term: this.term
                }, {
                    replace: true,
                    preserveScroll: true
                })
            } else {
                this.$inertia.get('/user/trashed', {
                    term: this.term
                }, {
                    replace: true,
                    preserveScroll: true
                })
            }
        }, 200),

        onImageChange(e) {
            e.preventDefault();
            this.form.profile_photo_path = e.target.files[0];
        },

        restore: function (data) {
            // if (!confirm('Are you sure want to restore?')) return;

            Swal.fire({
                title: 'Are you sure?',
                text: "This data will be restored. You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    data._method = 'PUT';
                    this.$inertia.post('/user/trashed/' + data.id, data)
                    Swal.fire(
                        'Restored!',
                        'Your file has been restored.',
                        'success'
                    )
                }
            })

        },

        forceDeleteRow: function (data) {
            // if (!confirm('Are you sure want to delete?')) return;
            Swal.fire({
                title: 'Are you sure?',
                text: "This data will be deleted permanently. You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes,permanent delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    data._method = 'DELETE';
                    this.$inertia.post('/user/trashed/' + data.id, data)
                    Swal.fire(
                        'Deleted!',
                        'Your file has been permanently deleted.',
                        'success'
                    )
                }
            })
        },

        restoreAll: function (data) {
            // if (!confirm('Are you sure want to restore all data?')) return;

            Swal.fire({
                title: 'Are you sure?',
                text: "All data will be restored. You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes,restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.get('/user/trashed/restore-all')
                    Swal.fire(
                        'All data restored!',
                        'All your file has been restored.',
                        'success'
                    )
                }
            })
        },

        deleteAll: function () {
            // if (!confirm('Are you sure want to permanetly delete all data?')) return;

            Swal.fire({
                title: 'Are you sure?',
                text: "All data will be deleted permanently. You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes,permanent delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete('/user/trashed/delete-all')
                    Swal.fire(
                        'All data deleted!',
                        'All your file has been permanently deleted.',
                        'success'
                    )
                }
            })
        }

    }

}
</script>
