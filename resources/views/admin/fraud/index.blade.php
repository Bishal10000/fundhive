@extends('layouts.admin')

@section('title', 'Flagged Campaigns')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <p class="text-muted mb-1">Review & act on suspicious activity</p>
                <h1 class="mb-0">Flagged Campaigns</h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.campaigns') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-bullhorn mr-1"></i>Campaigns
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-home mr-1"></i>Dashboard
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="alert alert-info shadow-sm">
            Campaigns shown here were flagged by the fraud detection service. Approving will restore visibility; rejecting will suspend the campaign.
        </div>

        <div class="card card-outline card-danger shadow-sm">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Owner</th>
                            <th>Fraud Score</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($campaigns as $campaign)
                            <tr>
                                <td class="font-weight-bold">{{ $campaign->title }}</td>
                                <td>{{ optional($campaign->user)->name ?? 'Unknown' }}</td>
                                <td><span class="badge badge-danger">{{ $campaign->fraud_score }}</span></td>
                                <td>
                                    <span class="badge badge-{{ $campaign->status === 'suspended' ? 'danger' : 'warning' }}">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <form method="POST" action="{{ route('admin.fraud.approve', $campaign) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success">Approve</button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.fraud.reject', $campaign) }}" class="d-inline ml-1">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted p-3">No flagged campaigns right now.</td>
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
