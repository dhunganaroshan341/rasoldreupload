@extends('layouts.main')

@section('script')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/2.8.2/alpine.min.js" defer></script>
@endsection

@section('content')
    <div class="container mx-auto mt-5" x-data="{ services: [], serviceId: '', serviceQuantity: 1, serviceDuration: '' }">
        <div class="flex justify-between mb-3">
            <h2 class="text-xl font-semibold">{{ isset($client) ? 'Edit Client' : 'Add Client' }}</h2>
            <a href="{{ route('clients.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-200 text-red-800 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($client) ? route('clients.update', ['id' => $client->id]) : route('clients.store') }}"
            method="POST" class="space-y-4">
            @csrf
            @if (isset($client))
                @method('PUT')
            @endif

            <div>
                <label for="name" class="block">Client Name:</label>
                <input type="text" class="border rounded w-full p-2" name="name" id="name" required
                    placeholder="Enter client's full name" value="{{ old('name', $client->name ?? '') }}">
            </div>

            <div>
                <label for="service_type" class="block">Services Used:</label>
                <select name="service_type" id="service_type" class="border rounded w-full p-2">
                    <option value="">Select Service Type</option>
                    @foreach ($existingServiceTypes as $serviceType)
                        <option value="{{ $serviceType->id }}"
                            {{ isset($client) && $client->service_type_id == $serviceType->id ? 'selected' : '' }}>
                            {{ $serviceType->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block">Existing Services:</label>
                <div class="space-y-2">
                    <template x-for="service in services" :key="service.id">
                        <div class="flex items-center bg-gray-100 border rounded p-2">
                            <input type="checkbox" x-model="service.checked" class="mr-2">
                            <span x-text="service.name"></span>
                            <button type="button" class="ml-2 text-red-500" @click="removeService(service.id)">
                                &times;
                            </button>
                        </div>
                    </template>
                </div>
                <button type="button" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded" @click="showMoreDetails()">
                    Add More
                </button>
            </div>

            <div>
                <label for="hosting_service" class="block">Hosting Service:</label>
                <input type="text" class="border rounded w-full p-2" id="hosting_service" name="hosting_service"
                    placeholder="host.hosting.com" value="{{ old('hosting_service', $client->hosting_service ?? '') }}">
            </div>

            <div>
                <label for="email_service" class="block">Email Service:</label>
                <input type="text" class="border rounded w-full p-2" id="email_service" name="email_service"
                    placeholder="email@domain.com" value="{{ old('email_service', $client->email_service ?? '') }}">
            </div>

            <div>
                <label for="address" class="block">Office Address:</label>
                <input type="text" class="border rounded w-full p-2" name="address" id="address"
                    placeholder="Enter office address" value="{{ old('address', $client->address ?? '') }}">
            </div>

            <div>
                <label for="pan_no" class="block">PAN/VAT Number:</label>
                <input type="text" class="border rounded w-full p-2" name="pan_no" id="pan_no" required
                    placeholder="e.g., 1234567890" value="{{ old('pan_no', $client->pan_no ?? '') }}">
            </div>

            <div>
                <label for="email" class="block">Email Address:</label>
                <input type="email" class="border rounded w-full p-2" name="email" id="email" required
                    placeholder="e.g., example@domain.com" value="{{ old('email', $client->email ?? '') }}">
            </div>

            <div>
                <label for="phone" class="block">Phone Number:</label>
                <input type="text" class="border rounded w-full p-2" name="phone" id="phone" required
                    placeholder="e.g., +1234567890" value="{{ old('phone', $client->phone ?? '') }}">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                {{ isset($client) ? 'Update Client' : 'Save Client' }}
            </button>
        </form>

        <!-- Modal -->
        <div x-data="serviceHandler()" x-show="modalOpen"
            class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-5 rounded shadow-lg">
                <h5 class="text-lg font-semibold">More Details</h5>
                <div class="mt-3">
                    <label for="service_duration_quantity" class="block">Service Duration</label>
                    <input type="number" class="border rounded w-full p-2" id="service_duration_quantity"
                        x-model="serviceQuantity" min="1">
                </div>
                <div class="mt-3">
                    <label for="service_duration" class="block">Select Duration</label>
                    <select id="service_duration" x-model="serviceDuration" class="border rounded w-full p-2">
                        <option value="">Select Duration</option>
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                    </select>
                </div>
                <div class="mt-4">
                    <button @click="saveMoreDetails()" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    <button @click="modalOpen = false"
                        class="bg-gray-300 text-black px-4 py-2 rounded ml-2">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function serviceHandler() {
            return {
                services: [],
                serviceId: '',
                serviceQuantity: 1,
                serviceDuration: '',
                modalOpen: false,

                showMoreDetails() {
                    this.modalOpen = true;
                },

                saveMoreDetails() {
                    // Logic to save more details about the service
                    this.modalOpen = false;
                },

                removeService(id) {
                    this.services = this.services.filter(service => service.id !== id);
                }
            };
        }
    </script>
@endsection
