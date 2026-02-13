<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @fluxAppearance
    <!-- @fluxStyles -->
    @livewireStyles

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    <title>Homepage</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</head>

<body>
    <div x-data="homepage()" x-init="getUser()" class="flex flex-col  w-full min-h-screen bg-blend-luminosity bg-blue-600">
        <nav class="relative bg-gray-800/50 after:pointer-events-none after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-white/10">
            <div class="mx-auto container sm:px-6 lg:px-8">
                <div class="relative flex h-16 items-center">
                    <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                        <div class="flex shrink-0 items-center">
                            <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company" class="h-8 w-auto" />
                        </div>
                        <h1 class="px-3 py-2 text-xl font-bold text-white">To Do List </h1>
                    </div>
                    <div class="hidden sm:flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                        <div class="flex shrink-0 items-center">
                            <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company" class="h-8 w-auto" />
                        </div>
                        <div class="hidden sm:ml-6 sm:block">
                            <div class="flex space-x-4">
                                <h1 class="px-3 py-2 text-xl font-bold text-white">To Do List</h1>
                            </div>
                        </div>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                        <el-dropdown class="relative ml-3">
                            <button class="relative flex rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                                <span class="absolute -inset-1.5"></span>
                                <span class="sr-only">Open user menu</span>
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-8 rounded-full bg-gray-800 outline -outline-offset-1 outline-white/10" />
                            </button>
                            <el-menu anchor="bottom end" popover class="w-48 origin-top-right rounded-md bg-gray-800 py-1 outline -outline-offset-1 outline-white/10 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in">
                                <h2 x-text="user.name" x-show="user.name" class="block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:outline-hidden"></h2>
                                <a href="#" @click="logout()"  class="block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:outline-hidden">Logout</a>
                            </el-menu>
                        </el-dropdown>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex flex-col container mx-auto w-full mb-4">
            @include('partials.todos')
        </div>
    </div>

    @fluxScripts
    @livewireScripts
    <script>
        function homepage() {
            return {
                user: { name: '' },
                todos: [],
                todoToDelete: null,
                todoForm: {
                    name: '',
                    status: 'List Tugas'
                },
                
                async getUser() {
                    const token = localStorage.getItem('token');
                    if (!token) {
                        window.location.href = '/';
                        return;
                    }
                    try {
                        const response = await fetch('/api/user', {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + token,
                            },
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.user = data;
                            // After getting user, get todos
                            this.getTodos();
                        } else {
                            localStorage.removeItem('token');
                            window.location.href = '/';
                        }
                    } catch (error) {
                        console.error('error fetching user:', error);
                    }
                },

                async getTodos() {
                    const token = localStorage.getItem('token');
                    try {
                        const response = await fetch('/api/todos', {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + token,
                            }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.todos = data.data;
                        }
                    } catch (error) {
                        console.error('getTodos error:', error);
                    }
                },

                getFilteredTodos(status) {
                    let filtered = this.todos.filter(todo => todo.status === status);
                    
                    if (status === 'List Tugas') {
                        // Sort by ID descending (newest first)
                        return filtered.sort((a, b) => b.id - a.id);
                    } else {
                        // Sort by updated_at ascending (first moved stays first)
                        return filtered.sort((a, b) => {
                            return new Date(a.updated_at) - new Date(b.updated_at);
                        });
                    }
                },

                async saveTodo() {
                    const token = localStorage.getItem('token');
                    try {
                        const response = await fetch('/api/todos', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + token,
                            },
                            body: JSON.stringify(this.todoForm)
                        });
                        const data = await response.json();
                        if (response.ok) {
                            this.todos.unshift(data.data);
                            this.todoForm.name = '';
                            this.todoForm.status = 'List Tugas';
                            Flux.modal('add-todo').close();
                        }
                    } catch (error) {
                        console.error('saveTodo error:', error);
                    }
                },

                async updateStatus(id, status) {
                    const token = localStorage.getItem('token');
                    try {
                        const response = await fetch(`/api/todos/${id}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + token,
                            },
                            body: JSON.stringify({ status: status })
                        });
                        const data = await response.json();
                        if (response.ok) {
                            const updatedTodo = data.data;
                            const index = this.todos.findIndex(t => t.id === id);
                            if (index !== -1) {
                                this.todos[index] = updatedTodo;
                            }
                        }
                    } catch (error) {
                        console.error('updateStatus error:', error);
                    }
                },

                async deleteTodo(id) {
                    const token = localStorage.getItem('token');
                    try {
                        const response = await fetch(`/api/todos/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + token,
                            }
                        });
                        if (response.ok) {
                            this.todos = this.todos.filter(t => t.id !== id);
                            Flux.modal('confirm-delete').close();
                        }
                    } catch (error) {
                        console.error('deleteTodo error:', error);
                    }
                },

                openDeleteModal(todo) {
                    this.todoToDelete = todo;
                    Flux.modal('confirm-delete').show();
                },

                logout() {
                    localStorage.removeItem('token');
                    window.location.href = '/';
                }
            };
        }
    </script>
</body>

</html>