<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Tugas;
use Illuminate\Auth\Access\HandlesAuthorization;

class TugasPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Tugas');
    }

    public function view(AuthUser $authUser, Tugas $tugas): bool
    {
        return $authUser->can('View:Tugas');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Tugas');
    }

    public function update(AuthUser $authUser, Tugas $tugas): bool
    {
        return $authUser->can('Update:Tugas');
    }

    public function delete(AuthUser $authUser, Tugas $tugas): bool
    {
        return $authUser->can('Delete:Tugas');
    }

    public function restore(AuthUser $authUser, Tugas $tugas): bool
    {
        return $authUser->can('Restore:Tugas');
    }

    public function forceDelete(AuthUser $authUser, Tugas $tugas): bool
    {
        return $authUser->can('ForceDelete:Tugas');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Tugas');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Tugas');
    }

    public function replicate(AuthUser $authUser, Tugas $tugas): bool
    {
        return $authUser->can('Replicate:Tugas');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Tugas');
    }

}