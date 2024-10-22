<?php

// app/Http/Livewire/IncomeCategoryCrud.php

namespace App\Livewire;

use App\Models\IncomeCategory;
use Livewire\Component;

class IncomeCategoryCrud extends Component
{
    public $categories;

    public $name;

    public $category_id;

    public $isModalOpen = false;

    public function render()
    {
        $this->categories = IncomeCategory::all();

        return view('livewire.income-category-crud');
    }

    public function create()
    {
        $this->resetCreateForm();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetCreateForm()
    {
        $this->name = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);

        IncomeCategory::updateOrCreate(['id' => $this->category_id], ['name' => $this->name]);

        session()->flash('message', $this->category_id ? 'Category updated.' : 'Category created.');

        $this->closeModal();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $category = IncomeCategory::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;

        $this->openModal();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
        ]);

        IncomeCategory::updateOrCreate(['id' => $this->category_id], ['name' => $this->name]);

        session()->flash('message', $this->category_id ? 'Category updated.' : 'Category created.');

        $this->closeModal();
        $this->resetCreateForm();
    }

    public function delete($id)
    {
        IncomeCategory::find($id)->delete();
        session()->flash('message', 'Category deleted.');
    }
}
