@extends('layouts.admin')

@section('title', 'Manage Campaigns')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <p class="text-muted mb-1">Visibility, safety, and actions</p>
                <h1 class="mb-0">Campaigns</h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.fraud') }}" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-shield-alt mr-1"></i>Flagged Queue
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-home mr-1"></i>Dashboard
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card card-outline card-primary shadow-sm">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>Flagged</th>
                            <th>Created</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($campaigns as $campaign)
                            <tr>
                                <td>{{ $campaign->id }}</td>
                                <td class="font-weight-bold">
                                    {{ $campaign->title }}
                                    @if ($campaign->is_flagged)
                                        <span class="badge badge-danger ml-1">Flagged</span>
                                    @endif
                                </td>
                                <td>{{ optional($campaign->user)->name ?? 'Unknown' }}</td>
                                <td>
                                    <span class="badge badge-{{ $campaign->status === 'active' ? 'success' : ($campaign->status === 'suspended' ? 'danger' : 'secondary') }}">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                                <td>{{ $campaign->is_flagged ? 'Yes' : 'No' }}</td>
                                <td>{{ optional($campaign->created_at)->format('M d, Y') }}</td>
                                <td class="text-right">
                                    <form action="{{ route('admin.campaigns.toggleVisibility', $campaign) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="{{ $campaign->status === 'suspended' ? 'Show campaign' : 'Hide campaign' }}">
                                            <i class="fas fa-eye-slash"></i> {{ $campaign->status === 'suspended' ? 'Show' : 'Hide' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" class="d-inline ml-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this campaign? This cannot be undone.')" title="Delete campaign">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted p-3">No campaigns found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $campaigns->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
