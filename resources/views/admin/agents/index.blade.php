@extends('layouts.layout')

@section('content')
    <div x-data="agentsApp()" class="w-full min-h-screen px-4 py-6 bg-gray-50 dark:bg-gray-900">

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Agents Management</h1>
                <button @click="openModal()"
                    class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg shadow transition">
                    + Add Agent
                </button>
            </div>

            <!-- Search -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <input type="text" x-model="search" placeholder="Search agent..."
                    class="w-full px-3 py-2 rounded-md border bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
            </div>

            <!-- Agents Table -->
            <div class="px-6 py-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                User</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Center</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="agent in paginatedAgents()" :key="agent.uuid">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400" x-text="agent.user.name"></td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400"
                                    x-text="agent.center ? agent.center.name : 'Unassigned'"></td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400" x-text="capitalize(agent.status)">
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <button @click="editAgent(agent.uuid)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg transition">Edit</button>
                                    <button @click="deleteAgent(agent.uuid)"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition">
                                        Delete
                                    </button>

                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredAgents().length === 0">
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No agents
                                found.</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-3 flex justify-between items-center">
                    <div class="dark:text-gray-500">
                        Page <span x-text="currentPage"></span> of <span x-text="totalPages()"></span>
                    </div>
                    <div class="space-x-1 dark:text-gray-300 flex items-center">
                        <button @click="goToPage(currentPage - 1)"
                            class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">Prev</button>
                        <template x-for="page in totalPages()" :key="page">
                            <button @click="goToPage(page)"
                                :class="{
                                    'bg-blue-500 text-white': currentPage ===
                                        page,
                                    'bg-gray-200 dark:bg-gray-700': currentPage !== page
                                }"
                                class="px-3 py-1 rounded hover:bg-blue-400 transition">
                                <span x-text="page"></span>
                            </button>
                        </template>
                        <button @click="goToPage(currentPage + 1)"
                            class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600">Next</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agent Modal -->
        <div x-show="showModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div @click.away="closeModal()"
                class="bg-white dark:bg-gray-800 rounded-xl p-6 w-96 shadow-lg transform transition-all">
                <h2 class="text-lg font-bold mb-4 dark:text-white" x-text="form.uuid ? 'Edit Agent' : 'Add Agent'"></h2>
                <form :action="form.uuid ? `/admin/agents/${form.uuid}/update` : '/admin/agents/store'" method="POST">
                    @csrf
                    <input type="hidden" name="_method" :value="form.uuid ? 'PUT' : 'POST'">
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 dark:text-gray-300">User</label>
                        <select name="user_id" x-model="form.user_id"
                            class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Select User</option>
                            <template x-for="user in users" :key="user.id">
                                <option :value="user.id" x-text="user.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Center</label>
                        <select name="center_id" x-model="form.center_id"
                            class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Select Center</option>
                            <template x-for="center in centers" :key="center.id">
                                <option :value="center.id" x-text="center.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" x-model="form.status"
                            class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="submit"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg transition">Save</button>
                        <button type="button" @click="closeModal()"
                            class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg transition">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('agentsApp', () => ({
                search: '',
                showModal: false,
                form: {
                    uuid: null,
                    user_id: '',
                    center_id: '',
                    status: 'active'
                },
                users: @json($users),
                centers: @json($centers),
                agents: @json($agents),

                currentPage: 1,
                perPage: 5,

                filteredAgents() {
                    if (!this.search) return this.agents;
                    return this.agents.filter(a => a.user.name.toLowerCase().includes(this.search
                        .toLowerCase()));
                },

                paginatedAgents() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredAgents().slice(start, start + this.perPage);
                },

                totalPages() {
                    return Math.ceil(this.filteredAgents().length / this.perPage) || 1;
                },

                goToPage(page) {
                    if (page < 1) page = 1;
                    if (page > this.totalPages()) page = this.totalPages();
                    this.currentPage = page;
                },

                capitalize(str) {
                    return str.charAt(0).toUpperCase() + str.slice(1);
                },

                openModal() {
                    this.form = {
                        uuid: null,
                        user_id: '',
                        center_id: '',
                        status: 'active'
                    };
                    this.showModal = true;
                },
                closeModal() {
                    this.showModal = false
                },

                async editAgent(uuid) {
                    try {
                        // Fetch fresh agent data from server
                        const response = await fetch(`/admin/agents/${uuid}/edit`);
                        const data = await response.json();

                        if (data.agent) {
                            this.form = {
                                uuid: data.agent.uuid,
                                user_id: data.agent.user_id || '',
                                center_id: data.agent.center_id || '',
                                status: data.agent.status || 'active'
                            };

                            // Update users and centers if provided
                            if (data.users) this.users = data.users;
                            if (data.centers) this.centers = data.centers;

                            console.log('Editing agent with fresh data:', this.form);
                            this.showModal = true;
                        } else {
                            console.error('No agent data received');
                        }
                    } catch (error) {
                        console.error('Error fetching agent data:', error);
                        // Fallback to client-side data
                        let agent = this.agents.find(a => a.uuid === uuid);
                        if (agent) {
                            this.form = {
                                uuid: agent.uuid,
                                user_id: agent.user ? agent.user.id : '',
                                center_id: agent.center ? agent.center.id : '',
                                status: agent.status || 'active'
                            };
                            this.showModal = true;
                        }
                    }
                },

                deleteAgent(uuid) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/admin/agents/${uuid}/delete`;
                            form.innerHTML = `
                @csrf
                <input type="hidden" name="_method" value="DELETE">
            `;
                            document.body.appendChild(form);
                            form.submit(); // reloads page so ToastMagic shows
                        }
                    });
                }

            }));
        });
    </script>
@endsection
