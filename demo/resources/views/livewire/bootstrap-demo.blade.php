<div>

    {{-- Single Select --}}
    <div class="mb-5">
        <h5 class="fw-semibold mb-1">Single Select</h5>
        <p class="text-muted small mb-3">Basic searchable dropdown — pick one country.</p>

        <label class="form-label">Country</label>
        <x-searchable-select
            theme="bootstrap"
            :options="$countries"
            wire-model="country_id"
            :selected-value="$country_id"
            placeholder="Select a country..."
            search-placeholder="Search countries..."
        />

        @if ($country_id)
            <div class="alert alert-success d-flex align-items-center gap-2 mt-3 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill flex-shrink-0" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <span>Selected: <strong>{{ collect($countries)->firstWhere('id', $country_id)['name'] ?? '—' }}</strong> (ID: {{ $country_id }})</span>
            </div>
        @endif
    </div>

    <hr class="my-4">

    {{-- Multi-Select --}}
    <div class="mb-5">
        <h5 class="fw-semibold mb-1">Multi-Select</h5>
        <p class="text-muted small mb-3">Select multiple countries — chosen items appear as removable badges.</p>

        <label class="form-label">Countries</label>
        <x-searchable-select
            theme="bootstrap"
            :options="$countries"
            wire-model="selected_countries"
            :selected-value="$selected_countries"
            :multiple="true"
            placeholder="Select countries..."
            search-placeholder="Search countries..."
        />

        @if (count($selected_countries) > 0)
            <div class="mt-3">
                <p class="text-muted small mb-2">{{ count($selected_countries) }} selected:</p>
                <div class="d-flex flex-wrap gap-1">
                    @foreach ($selected_countries as $id)
                        <span class="badge bg-primary rounded-pill">
                            {{ collect($countries)->firstWhere('id', $id)['name'] ?? $id }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <hr class="my-4">

    {{-- Grouped Options --}}
    <div class="mb-5">
        <h5 class="fw-semibold mb-1">Grouped Options</h5>
        <p class="text-muted small mb-3">Options organised into labelled groups by region.</p>

        <label class="form-label">Region / Country</label>
        <x-searchable-select
            theme="bootstrap"
            :options="$groupedRegions"
            wire-model="region_id"
            :selected-value="$region_id"
            :grouped="true"
            placeholder="Select a country by region..."
            search-placeholder="Search..."
        />

        @if ($region_id)
            @php
                $allOptions = collect($groupedRegions)->flatMap(fn($g) => $g['options']);
            @endphp
            <div class="alert alert-info d-flex align-items-center gap-2 mt-3 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill flex-shrink-0" viewBox="0 0 16 16">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                </svg>
                <span>Selected: <strong>{{ $allOptions->firstWhere('id', $region_id)['name'] ?? '—' }}</strong> (ID: {{ $region_id }})</span>
            </div>
        @endif
    </div>

    <hr class="my-4">

    {{-- Disabled State --}}
    <div class="mb-5">
        <h5 class="fw-semibold mb-1">Disabled State</h5>
        <p class="text-muted small mb-3">The component is fully inert when <code>disabled</code> is set.</p>

        <label class="form-label text-muted">Country (disabled)</label>
        <x-searchable-select
            theme="bootstrap"
            :options="$countries"
            wire-model="country_id"
            :selected-value="2"
            placeholder="Cannot interact..."
            :disabled="true"
        />
    </div>

    <hr class="my-4">

    {{-- API Search --}}
    <div class="mb-2">
        <h5 class="fw-semibold mb-1">API-Powered Search</h5>
        <p class="text-muted small mb-3">
            Options are fetched from <code>/api/countries?search=…</code> as you type — no pre-loaded data needed.
        </p>

        <label class="form-label">Country (live search)</label>
        <x-searchable-select
            theme="bootstrap"
            :options="[]"
            wire-model="api_country_id"
            :selected-value="$api_country_id"
            api-url="/api/countries"
            option-value="value"
            option-label="label"
            placeholder="Type to search countries..."
            search-placeholder="Search countries..."
            empty-message="No countries found"
        />

        @if ($api_country_id)
            <div class="alert alert-success d-flex align-items-center gap-2 mt-3 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill flex-shrink-0" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <span>Selected ID: <strong>{{ $api_country_id }}</strong></span>
            </div>
        @endif
    </div>

</div>
