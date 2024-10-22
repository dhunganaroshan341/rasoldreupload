<!-- resources/views/livewire/create-expense-category.blade.php -->
<div class="modal show" style="display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">expense Category</h5>
                <button type="button" wire:click="closeModal" class="close">&times;</button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" class="form-control" id="name" wire:model="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button wire:click.prevent="store" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
