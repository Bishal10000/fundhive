@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <p class="text-muted mb-1">Protect the community</p>
                <h1 class="mb-0">Users</h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-home mr-1"></i>Dashboard
            </a>
        </div>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Verified</th>
                            <th>Blocked</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge badge-{{ $user->is_verified ? 'success' : 'warning' }}">
                                        {{ $user->is_verified ? '✓ Verified' : '⚠ Pending' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $user->is_blocked ? 'danger' : 'success' }}">
                                        {{ $user->is_blocked ? 'Blocked' : 'Active' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    @if(!$user->is_verified)
                                        <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Verify user">
                                                <i class="fas fa-check-circle"></i> Verify
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.unverify', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Remove verification">
                                                <i class="fas fa-times-circle"></i> Unverify
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.users.toggleBlock', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="{{ $user->is_blocked ? 'Unblock user' : 'Block user' }}">
                                            <i class="fas fa-user-slash"></i> {{ $user->is_blocked ? 'Unblock' : 'Block' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted p-3">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
