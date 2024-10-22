<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\OurServices;
use Livewire\Component;

class ClientForm extends Component
{
    public $client;

    public $serviceTypes = [];

    public $isModalOpen = false;

    public $selectedServices = [];

    public $newService = '';

    public $clientId;

    public function mount($clientId = null)
    {
        $this->isModalOpen = false; // Set modal open state correctly
        if ($clientId) {
            $this->clientId = $clientId;
            $this->client = Client::findOrFail($clientId);
            $this->selectedServices = $this->client->clientServices()->pluck('service_id')->toArray();
        } else {
            $this->client = new Client;
        }

        $this->serviceTypes = OurServices::all();
    }

    public function saveClient()
    {
        $validatedClient = $this->validate([
            'client.name' => 'required',
            'client.client_type' => 'nullable',
            'client.address' => 'required',
            'client.email' => 'required|email|unique:clients,email'.($this->clientId ? ",{$this->clientId}" : ''),
            'client.phone' => 'required|unique:clients,phone'.($this->clientId ? ",{$this->clientId}" : ''),
            'client.pan_no' => 'nullable|string|unique:clients,pan_no'.($this->clientId ? ",{$this->clientId}" : ''),
            'selectedServices' => 'nullable|array',
            'selectedServices.*' => 'exists:our_services,id',
            'newService' => 'nullable|string|max:255',
            'client.hosting_service' => 'nullable|string',
            'client.email_service' => 'nullable|string',
        ]);

        // Create or update the client
        $client = $this->clientId ? $this->client : Client::create($validatedClient['client']);

        // Handle new service creation
        if ($this->newService) {
            $existingServiceTypes = OurServices::where('name', $this->newService)->first();
            if (! $existingServiceTypes) {
                $existingServiceTypes = OurServices::create(['name' => $this->newService]);
            }
            $this->selectedServices[] = $existingServiceTypes->id;
        }

        // Sync services for the client
        $client->services()->sync(array_unique($this->selectedServices));

        session()->flash('success', 'Client '.$client->name.($this->clientId ? ' updated' : ' created').' successfully.');

        return redirect()->route('clients.index'); // Adjust redirect as needed
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function render()
    {
        return view('livewire.client-form');
    }
}
