<div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
    <h3 class="text-lg font-semibold mb-4">Order History</h3>
    <template x-for="entry in form.history" :key="entry.id">
        <div class="border-b py-2 flex justify-between text-sm">
            <span x-text="entry.old_status + ' → ' + entry.new_status"></span>
            <span x-text="entry.changed_by_name + ' @ ' + entry.created_at"></span>
        </div>
    </template>
</div>
