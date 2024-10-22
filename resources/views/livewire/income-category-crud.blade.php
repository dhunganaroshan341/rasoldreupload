<!-- resources/views/livewire/income-category-crud.blade.php -->
<div>
    <button wire:click="create" class="btn btn-success">Add Income Category</button>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>
                        <button wire:click="edit({{ $category->id }})" class="btn btn-info">Edit</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($isModalOpen)
        <div class="modal show" tabindex="-1" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $category_id ? 'Edit' : 'Create' }} Income Category</h5>
                        <button type="button" class="close" wire:click="$set('isModalOpen', false)">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="store">
                            <div class="form-group">
                                <label for="name">Category Name</label>
                                <input type="text" id="name" class="form-control" wire:model="name" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
