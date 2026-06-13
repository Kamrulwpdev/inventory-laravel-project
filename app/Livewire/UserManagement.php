<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    public $users;
    public $userId, $name, $email, $password, $role;
    public $isEditModalOpen = false;
    public $isCreateModalOpen = false; // New property

    public function render()
    {
        $this->users = User::all();
        return view('livewire.user-management')->layout('admin.dashboard');
    }

    // --- CREATE LOGIC ---
    public function openCreateModal()
    {
        $this->reset(['name', 'email', 'password', 'role']); // Clear fields
        $this->isCreateModalOpen = true;
    }

    public function storeUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        $this->isCreateModalOpen = false;
        session()->flash('message', 'New user created successfully!');
    }

    // --- EDIT LOGIC ---
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->isEditModalOpen = true;
    }

    public function updateUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
        ]);

        User::find($this->userId)->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->isEditModalOpen = false;
        session()->flash('message', 'User updated successfully!');
    }

    public function closeModal()
    {
        $this->isEditModalOpen = false;
        $this->isCreateModalOpen = false;
    }
    
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting yourself (the logged-in admin)
            if ($id == auth()->id()) {
                session()->flash('error', 'You cannot delete your own account!');
                return;
            }

            $user->delete();
            session()->flash('message', 'User removed successfully.');
            
        } catch (\Exception $e) {
            // This captures the ACTUAL error (e.g., database conflict)
            session()->flash('error', 'Could not delete: ' . $e->getMessage());
        }
    }
    // ... (Keep your existing updateRole and deleteUser methods)
}