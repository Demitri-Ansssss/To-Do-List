<div>
    <div class="flex justify-center items-center mt-8 flex-col gap-6">
        <h1 class="text-4xl font-bold text-center mt-14 text-white text-shadow-slate-900 uppercase">To Do List</h1>
        
        <flux:modal.trigger name="add-todo">
            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-all active:scale-95">Tambah Data</button>
        </flux:modal.trigger>

        <flux:modal name="add-todo" class="md:w-[500px] space-y-6">
            <div>
                <flux:heading size="lg">Tambah Tugas Baru</flux:heading>
                <flux:subheading>Masukkan detail tugas yang ingin ditambahkan.</flux:subheading>
            </div>

            <form @submit.prevent="saveTodo" class="space-y-6">
                <flux:input x-model="todoForm.name" label="Nama Tugas" placeholder="Contoh: Belajar Laravel" />

                <flux:select x-model="todoForm.status" label="Status">
                    <option value="List Tugas">List Tugas</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </flux:select>

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Simpan Tugas</flux:button>
                </div>
            </form>
        </flux:modal>
    </div>

    <div class="flex justify-center mt-10 gap-6 w-full min-h-[680px]">
        <!-- List Tugas -->
        <flux:card class="w-1/3 h-auto text-center gap-2 flex flex-col shadow-2xl border-accent border-2">
            <flux:heading size="xl">List Tugas</flux:heading>
            <div class="space-y-2 mt-4">
                <template x-for="todo in getFilteredTodos('List Tugas')" :key="todo.id">
                    <div class="p-3 bg-white/10 rounded-lg w-auto text-white text-left flex justify-between items-center group">
                        <span x-text="todo.name"></span>
                        <div class="flex flex-col gap-2">
                            <flux:button @click="updateStatus(todo.id, 'In Progress')" variant="primary" size="sm" class="group-hover:opacity-100 transition-opacity">Selesai</flux:button>
                            <flux:button @click="openDeleteModal(todo)" variant="danger" size="sm" class="group-hover:opacity-100 transition-opacity">Hapus</flux:button>
                        </div>
                    </div>
                </template>
            </div>
        </flux:card>

        <!-- In Progress -->
        <flux:card class="w-1/3 h-auto text-center gap-2 flex flex-col shadow-2xl border-accent border-2">
            <flux:heading size="xl">In Progress</flux:heading>
            <div class="space-y-2 mt-4">
                <template x-for="todo in getFilteredTodos('In Progress')" :key="todo.id">
                    <div class="p-3 bg-green-500/20 rounded-lg w-auto text-white text-left flex justify-between items-center group">
                        <span x-text="todo.name"></span>
                        <div class="flex flex-col gap-2">
                            <flux:button @click="updateStatus(todo.id, 'Completed')" variant="primary" size="sm" class="group-hover:opacity-100 transition-opacity">Selesai</flux:button>
                            <flux:button @click="openDeleteModal(todo)" variant="danger" size="sm" class="group-hover:opacity-100 transition-opacity">Hapus</flux:button>
                        </div>
                    </div>
                </template>
            </div>
        </flux:card>

        <!-- Completed -->
        <flux:card class="w-1/3 h-auto text-center gap-2 flex flex-col shadow-2xl border-accent border-2">
            <flux:heading size="xl">Completed</flux:heading>
            <div class="space-y-2 mt-4">
                <template x-for="todo in getFilteredTodos('Completed')" :key="todo.id">
                    <div class="p-3 bg-red-500/20 rounded-lg w-auto text-white text-left flex justify-between items-center group">
                        <span x-text="todo.name"></span>
                        <div class="flex flex-col gap-2">
                            <flux:button @click="updateStatus(todo.id, 'Completed')" variant="primary" size="sm" class="group-hover:opacity-100 transition-opacity">Selesai</flux:button>
                            <flux:button @click="updateStatus(todo.id, 'In Progress')" variant="primary" size="sm" class="group-hover:opacity-100 transition-opacity">In Progress</flux:button>
                            <flux:button @click="openDeleteModal(todo)" variant="danger" size="sm" class="group-hover:opacity-100 transition-opacity">Hapus</flux:button>
                        </div>
                    </div>
                </template>
            </div>
        </flux:card>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <flux:modal name="confirm-delete" variant="danger" class="md:w-[400px] space-y-6">
        <div>
            <flux:heading size="lg">Hapus Tugas</flux:heading>
            <flux:subheading>Apakah Anda yakin ingin menghapus tugas <span class="font-bold text-white" x-text="todoToDelete?.name"></span>?</flux:subheading>
        </div>

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Batal</flux:button>
            </flux:modal.close>
            <flux:button @click="deleteTodo(todoToDelete.id)" variant="danger">Ya, Hapus</flux:button>
        </div>
    </flux:modal>
</div>

