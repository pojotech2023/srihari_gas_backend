@extends('layouts.admin')

@section('title', 'Deliveryman Management')
@section('header_title', 'Delivery Personnel')
@section('header_subtitle', 'Manage drivers, verify uploaded documents, and dispatch delivery agents')

@section('content')
<div class="dashboard-row">
    <!-- Deliverymen List -->
    <div class="panel-card">
        <div class="panel-header">
            <h3>Registered Delivery Agents</h3>
            <span style="font-size: 0.85rem; color: var(--text-muted);">Total Agents: {{ $deliverymen->count() }}</span>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Agent Name</th>
                        <th>Mobile Number</th>
                        <th>Assigned Orders</th>
                        <th>Documents Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliverymen as $man)
                        @php
                            $hasAadhar = !empty($man->aadhar_card_url);
                            $hasLicence = !empty($man->driving_licence_url);
                            $hasInsurance = !empty($man->insurance_url);
                            $docsCount = ($hasAadhar ? 1 : 0) + ($hasLicence ? 1 : 0) + ($hasInsurance ? 1 : 0);
                        @endphp
                        <tr>
                            <td><strong>{{ $man->name }}</strong></td>
                            <td>{{ $man->mobile_number }}</td>
                            <td>
                                <span class="badge" style="background-color: rgba(139, 92, 246, 0.1); color: var(--accent-violet);">
                                    {{ $man->orders->count() }} orders
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="{{ $docsCount === 3 ? 'background: rgba(16, 185, 129, 0.15); color: #10b981;' : ($docsCount > 0 ? 'background: rgba(245, 158, 11, 0.15); color: #f59e0b;' : 'background: rgba(148, 163, 184, 0.15); color: #94a3b8;') }}">
                                    {{ $docsCount }}/3 Uploaded
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <button type="button" 
                                            class="btn btn-secondary" 
                                            style="padding: 0.4rem 0.8rem; font-size: 0.85rem; cursor: pointer;"
                                            onclick="openDocsModal(
                                                '{{ addslashes($man->name) }}',
                                                '{{ addslashes($man->mobile_number) }}',
                                                '{{ $man->aadhar_card_url ?? '' }}',
                                                '{{ $man->driving_licence_url ?? '' }}',
                                                '{{ $man->insurance_url ?? '' }}'
                                            )">
                                        View
                                    </button>
                                    <a href="{{ route('admin.deliverymen.edit', $man->id) }}" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; text-decoration: none;">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.deliverymen.destroy', $man->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this delivery agent?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No delivery agents registered. Use the panel on the right to add one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Deliveryman Form -->
    <div class="panel-card" style="height: fit-content;">
        <div class="panel-header">
            <h3>Add Delivery Agent</h3>
        </div>
        
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.deliverymen.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Agent Name *</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Ramesh Kumar" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="mobile_number">Mobile Number *</label>
                <input type="text" name="mobile_number" id="mobile_number" class="form-control" placeholder="e.g. 9876543222" value="{{ old('mobile_number') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
                Add Agent
            </button>
        </form>
    </div>
</div>

<!-- Deliveryman Documents Modal -->
<div id="docsModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.75); z-index: 9999; backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; width: 100%; max-width: 850px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 id="modalAgentName" style="margin: 0; font-size: 1.2rem; color: var(--text-primary);">Delivery Agent Documents</h3>
                <p id="modalAgentMobile" style="margin: 0.25rem 0 0 0; font-size: 0.85rem; color: var(--text-muted);"></p>
            </div>
            <button type="button" onclick="closeDocsModal()" style="background: none; border: none; color: var(--text-muted); font-size: 1.5rem; cursor: pointer; line-height: 1; padding: 0.25rem;">
                &times;
            </button>
        </div>

        <div style="padding: 1.5rem; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1.25rem;">
            <!-- Document 1: Aadhaar Card -->
            <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
                <h4 style="margin: 0; font-size: 0.95rem; color: var(--accent-indigo); display: flex; align-items: center; gap: 0.5rem;">
                    1. Aadhaar Card
                </h4>
                <div id="aadharContainer" style="flex: 1; min-height: 180px; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.2); border-radius: 6px; overflow: hidden; border: 1px dashed var(--border-color);">
                    <!-- Dynamic preview -->
                </div>
            </div>

            <!-- Document 2: Driving Licence -->
            <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
                <h4 style="margin: 0; font-size: 0.95rem; color: var(--accent-violet); display: flex; align-items: center; gap: 0.5rem;">
                    2. Driving Licence
                </h4>
                <div id="licenceContainer" style="flex: 1; min-height: 180px; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.2); border-radius: 6px; overflow: hidden; border: 1px dashed var(--border-color);">
                    <!-- Dynamic preview -->
                </div>
            </div>

            <!-- Document 3: Insurance -->
            <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
                <h4 style="margin: 0; font-size: 0.95rem; color: #10b981; display: flex; align-items: center; gap: 0.5rem;">
                    3. Insurance
                </h4>
                <div id="insuranceContainer" style="flex: 1; min-height: 180px; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.2); border-radius: 6px; overflow: hidden; border: 1px dashed var(--border-color);">
                    <!-- Dynamic preview -->
                </div>
            </div>
        </div>

        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeDocsModal()">
                Close
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function renderDocPreview(containerId, url, title) {
        const container = document.getElementById(containerId);
        if (url && url.trim() !== '') {
            container.innerHTML = `
                <div style="width: 100%; text-align: center;">
                    <a href="${url}" target="_blank" title="Click to view full size">
                        <img src="${url}" alt="${title}" style="max-width: 100%; max-height: 170px; object-fit: contain; border-radius: 4px;">
                    </a>
                    <div style="margin-top: 0.5rem;">
                        <a href="${url}" target="_blank" class="btn btn-secondary" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; text-decoration: none; display: inline-block;">
                            View Full Size &rarr;
                        </a>
                    </div>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem; padding: 1.5rem 0.5rem;">
                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 0.5rem; opacity: 0.5;">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div>Not uploaded yet</div>
                </div>
            `;
        }
    }

    function openDocsModal(name, mobile, aadharUrl, licenceUrl, insuranceUrl) {
        document.getElementById('modalAgentName').innerText = 'Documents: ' + name;
        document.getElementById('modalAgentMobile').innerText = 'Mobile: ' + mobile;

        renderDocPreview('aadharContainer', aadharUrl, 'Aadhaar Card');
        renderDocPreview('licenceContainer', licenceUrl, 'Driving Licence');
        renderDocPreview('insuranceContainer', insuranceUrl, 'Insurance');

        const modal = document.getElementById('docsModal');
        modal.style.display = 'flex';
    }

    function closeDocsModal() {
        document.getElementById('docsModal').style.display = 'none';
    }

    // Close on backdrop click
    document.getElementById('docsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDocsModal();
        }
    });
</script>
@endsection
