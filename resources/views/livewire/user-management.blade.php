<div class="user-management-wrapper" style="background: #f4f6fc; min-height: 100vh; padding: 40px;">
    <style>
    
    .user-management-wrapper {
        margin: -25px!important;
    }
        :root {
            --glass-white: rgba(255, 255, 255, 0.85);
            --trend-gradient: linear-gradient(135deg, #da8cff 0%, #9a55ff 100%);
            --shadow-soft: 0 10px 40px rgba(154, 85, 255, 0.05);
        }

        /* Glass Panel Styling */
        .glass-panel {
            background: var(--glass-white);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: var(--shadow-soft);
            overflow: hidden;
        }

        /* Trend Buttons */
        .btn-trend {
            background: var(--trend-gradient);
            color: white !important;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-trend:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(154, 85, 255, 0.3); }

        /* Custom Role Badges */
        .badge-role {
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .role-admin { background: rgba(0, 184, 217, 0.1); color: #00b8d9; }
        .role-manager { background: rgba(154, 85, 255, 0.1); color: #9a55ff; }

        /* Modern Table */
        .table-trend thead th {
            background: transparent;
            color: #8898aa;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 20px;
            border: none;
        }
        .table-trend tbody tr { border-top: 1px solid rgba(0,0,0,0.03); transition: 0.2s; }
        .table-trend tbody tr:hover { background: rgba(154, 85, 255, 0.02); }

        /* Trend Modals */
        .modal-glass {
            border-radius: 35px;
            border: none;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
        }
        .rounded-input { border-radius: 15px; background: #f8f9fa; border: 1px solid #eee; padding: 12px 20px; }
        .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px; /* Slightly rounded as per your Orders screen */
        border: none;
        transition: all 0.2s ease;
        margin-right: 5px;
    }

    /* Edit Button: Light Blue/Purple Tint */
    .btn-edit-soft {
        background-color: #f0f7ff;
        color: #3b82f6;
    }
    .btn-edit-soft:hover {
        background-color: #e0efff;
        transform: scale(1.1);
    }

    /* Delete Button: Light Pink/Red Tint */
    .btn-delete-soft {
        background-color: #fff0f0;
        color: #ef4444;
    }
    .btn-delete-soft:hover {
        background-color: #ffe4e4;
        transform: scale(1.1);
    }

    /* Icons inside buttons */
    .btn-action i {
        font-size: 14px;
    }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold text-dark m-0" style="letter-spacing: -1px;">User Management</h1>
            <p class="text-muted small">Configure team roles and access permissions</p>
        </div>
        <button wire:click="openCreateModal" class="btn btn-trend">
            <i class="fas fa-user-plus me-2"></i> Add New User
        </button>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">{{ session('message') }}</div>
    @endif

    <div class="glass-panel">
        <div class="table-responsive">
            <table class="table table-trend align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Team Member</th>
                        <th>Email Address</th>
                        <th>Status / Role</th>
                        <th>Joined Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td class="ps-4 py-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-primary shadow-sm" style="width: 45px; height: 45px;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="fw-bold text-dark fs-6">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td>
                                <span class="badge-role {{ str_contains($user->role, 'admin') ? 'role-admin' : 'role-manager' }}">
                                    {{ str_replace('_', ' ', $user->role ?? 'User') }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</td>
                            <td class="text-nowrap text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <select class="form-select form-select-sm rounded-pill border-0 shadow-sm w-auto" 
                                            wire:change="updateRole({{ $user->id }}, $event.target.value)">
                                        <option value="">Change Role</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="shop_manager" {{ $user->role == 'shop_manager' ? 'selected' : '' }}>Shop Manager</option>
                                        <option value="account_manager" {{ $user->role == 'account_manager' ? 'selected' : '' }}>Account Manager</option>
                                    </select>
                                    <button wire:click="editUser({{ $user->id }})" class="btn-action btn-edit-soft">
        <i class="fas fa-edit"></i>
    </button>
                                    <button wire:click="deleteUser({{ $user->id }})" 
            wire:confirm="Are you sure you want to delete this user?"
            class="btn-action btn-delete-soft">
        <i class="fas fa-trash"></i>
    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($isEditModalOpen || $isCreateModalOpen)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.3); backdrop-filter: blur(8px);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-glass p-3 shadow-lg">
                    <div class="modal-header border-0">
                        <h4 class="fw-bold text-dark m-0">{{ $isEditModalOpen ? 'Edit User Profile' : 'Create New Member' }}</h4>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted ps-2">Full Name</label>
                            <input type="text" class="form-control rounded-input" wire:model="name" placeholder="Name">
                            @error('name') <small class="text-danger ps-2">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted ps-2">Email Address</label>
                            <input type="email" class="form-control rounded-input" wire:model="email" placeholder="email@example.com">
                            @error('email') <small class="text-danger ps-2">{{ $message }}</small> @enderror
                        </div>

                        @if($isCreateModalOpen)
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted ps-2">Password</label>
                                <input type="password" class="form-control rounded-input" wire:model="password">
                                @error('password') <small class="text-danger ps-2">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted ps-2">Initial Role</label>
                                <select class="form-select rounded-input" wire:model="role">
                                    <option value="">Select Role...</option>
                                    <option value="admin">Admin</option>
                                    <option value="shop_manager">Shop Manager</option>
                                    <option value="account_manager">Account Manager</option>
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" wire:click="closeModal">Cancel</button>
                        <button type="button" class="btn btn-trend px-4" wire:click="{{ $isEditModalOpen ? 'updateUser' : 'storeUser' }}">
                            {{ $isEditModalOpen ? 'Update User' : 'Confirm & Create' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>