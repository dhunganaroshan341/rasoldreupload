@extends('layouts.main')

@section('script')
    <script src="{{ asset('js/toggleIncomeSelect.js') }}"></script>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h2>{{ $formTitle }}</h2>
            <a href="{{ route($backRoute) }}" class="btn btn-secondary">Back</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $formAction }}" method="POST">
            @csrf
            @isset($income)
                @method('PUT')
            @endisset

            <div class="form-group">
                <label for="income_source">Income Source:</label>
                <!-- Radio buttons to toggle between select and input -->
                <div class="form-check">
                    <input type="radio" id="select_projects" name="source_type" value="projects"
                        {{ old('source_type', $income->source_type ?? 'other') === 'projects' ? 'checked' : '' }}>
                    <label for="select_projects">Select from Existing Client Projects</label>
                </div>
                <div class="form-check">
                    <input type="radio" id="create_new" name="source_type" value="new"
                        {{ old('source_type', $income->source_type ?? 'other') === 'new' ? 'checked' : '' }}>
                    <label for="create_new">Create New Client Project</label>
                </div>
                <div class="form-check">
                    <input type="radio" id="enter_source" name="source_type" value="other"
                        {{ old('source_type', $income->source_type ?? 'other') === 'other' ? 'checked' : '' }}>
                    <label for="enter_source">Input the Source</label>
                </div>

                <!-- Dropdown for existing client projects -->
                <div id="projects_dropdown"
                    style="display: {{ old('source_type', $income->source_type ?? 'other') === 'projects' ? 'block' : 'none' }}">
                    <select class="custom-select" name="income_source" id="income_source" required>
                        @foreach ($projects as $project)
                            <option value="{{ $project->name }}"
                                {{ old('income_source', $income->income_source ?? '') === $project->name ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                        <option value="new_project" {{ old('income_source') === 'new_project' ? 'selected' : '' }}>
                            New Project
                        </option>
                    </select>
                </div>

                <!-- Input field for new client project -->
                <div id="new_project_input"
                    style="display: {{ old('source_type', $income->source_type ?? 'other') === 'new' ? 'block' : 'none' }}">
                    <input type="text" class="form-control" name="income_source" id="income_source_new"
                        value="{{ old('income_source', $income->income_source ?? '') }}"
                        placeholder="Specify new client project" required>
                </div>

                <!-- Input field for other source -->
                <div id="other_source_input"
                    style="display: {{ old('source_type', $income->source_type ?? 'other') === 'other' ? 'block' : 'none' }}">
                    <input type="text" class="form-control" name="income_source" id="income_source_other"
                        value="{{ old('income_source', $income->income_source ?? '') }}" placeholder="Specify other source"
                        required>
                </div>
            </div>

            <div class="form-group">
                <label for="transaction_date">Transaction Date:</label>
                <input type="date" class="form-control" name="transaction_date" id="transaction_date"
                    value="{{ old('transaction_date', $income->transaction_date ?? now()->format('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                    value="{{ old('amount', $income->amount ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="medium">Transaction Medium:</label>
                <select class="custom-select" name="medium" id="medium">
                    <option value="cash" {{ old('medium', $income->medium ?? '') == 'cash' ? 'selected' : '' }}>Cash
                    </option>
                    <option value="cheque" {{ old('medium', $income->medium ?? '') == 'cheque' ? 'selected' : '' }}>Cheque
                    </option>
                    <option value="mobile_transfer"
                        {{ old('medium', $income->medium ?? '') == 'mobile_transfer' ? 'selected' : '' }}>Mobile Transfer
                    </option>
                    <option value="other" {{ old('medium', $income->medium ?? '') == 'other' ? 'selected' : '' }}>Other
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="remarks">Remarks:</label>
                <input type="text" class="form-control" name="remarks" id="remarks" aria-describedby="helpId"
                    placeholder="Remarks" value="{{ old('remarks', $income->remarks ?? '') }}">
                <small id="helpId" class="form-text text-muted">Additional comments or information</small>
            </div>

            {{-- Additional fields for income-specific details --}}
            @yield('extra_fields')

            <button type="submit" class="btn btn-primary mt-3">{{ isset($income) ? 'Update' : 'Save' }}</button>
        </form>
    </div>
@endsection
