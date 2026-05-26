<div>
    {{-- It always seems impossible until it is done. - Nelson Mandela --}}
    <div>

        <div class="row g-4">

            {{-- ARRAY DEMO --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="mb-0">
                            Array Data Demo
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">
                                Select Framework
                            </label>

                            {{-- YOUR PACKAGE COMPONENT HERE --}}
                            {{-- Example --}}
                            <x-searchable-select
                                wire:model.live="selectedFramework"
                                :options="$frameworks"
                                label="name"
                                value="id"
                                placeholder="Choose framework..."
                            />
                        </div>

                        <div class="alert alert-info">
                            Selected:
                            <strong>{{ $selectedFramework }}</strong>
                        </div>

                    </div>
                </div>
            </div>

            {{-- MODEL DEMO --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="mb-0">
                            Eloquent Model Demo
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">
                                Select Country
                            </label>

                            {{-- YOUR PACKAGE COMPONENT HERE --}}
                            <x-searchable-select
                                wire:model.live="selectedCountry"
                                :options="$countries"
                                label="name"
                                value="id"
                                placeholder="Choose country..."
                            />
                        </div>

                        <div class="alert alert-success">
                            Selected:
                            <strong>{{ $selectedCountry }}</strong>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- MODAL TEST --}}
        <div class="mt-5">

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                Bootstrap Modal Test
                            </h4>

                            <p class="text-muted mb-0">
                                Verify dropdown positioning, z-index, focus and interaction.
                            </p>
                        </div>

                        <button
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#demoModal"
                        >
                            Open Modal
                        </button>
                    </div>

                </div>
            </div>

        </div>

        {{-- MODAL --}}
        <div
            class="modal fade"
            id="demoModal"
            tabindex="-1"
            aria-hidden="true"
            wire:ignore.self
        >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Searchable Select Modal Demo
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">
                                Country Inside Modal
                            </label>

                            <x-searchable-select
                                wire:model.live="selectedCountry"
                                :options="$countries"
                                label="name"
                                value="id"
                                placeholder="Search country..."
                                :teleport="false"
                            />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Framework Inside Modal
                            </label>

                            <x-searchable-select
                                wire:model.live="selectedFramework"
                                :options="$frameworks"
                                label="name"
                                value="id"
                                placeholder="Search framework..."
                                :teleport="false"
                            />
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
