@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<section class="content">
    <div class="container-fluid">

        {{-- alerts --}}
        @if (session('status'))
            <div class="alert alert-success shadow-sm">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
        @endif

        {{-- stats --}}
        <div class="row">
            <div class="col-md-4 col-lg-2 mb-3">
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Users</span>
                        <span class="info-box-number">{{ $stats['total_users'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2 mb-3">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="fas fa-bullhorn"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Campaigns</span>
                        <span class="info-box-number">{{ $stats['total_campaigns'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2 mb-3">
                <div class="info-box bg-gradient-warning">
                    <span class="info-box-icon"><i class="fas fa-eye"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Active</span>
                        <span class="info-box-number">{{ $stats['active_campaigns'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2 mb-3">
                <div class="info-box bg-gradient-danger">
                    <span class="info-box-icon"><i class="fas fa-flag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Flagged</span>
                        <span class="info-box-number">{{ $stats['flagged_campaigns'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2 mb-3">
                <div class="info-box bg-gradient-primary">
                    <span class="info-box-icon"><i class="fas fa-hand-holding-heart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Donations</span>
                        <span class="info-box-number">{{ $stats['total_donations'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2 mb-3">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><strong>₨</strong></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Raised</span>
                        <span class="info-box-number">
                            NPR {{ number_format($stats['total_raised']) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- recent campaigns + flagged --}}
        <div class="row">
            <div class="col-lg-7">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title mb-0">Recent Campaigns</h3>
                        <a href="{{ route('admin.campaigns') }}" class="btn btn-sm btn-primary">Manage</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Owner</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentCampaigns as $campaign)
                                        <tr>
                                            <td>{{ $campaign->title }}</td>
                                            <td>{{ optional($campaign->user)->name ?? 'Unknown' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $campaign->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($campaign->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No campaigns yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card card-outline card-danger shadow-sm h-100">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title mb-0">Flagged Campaigns</h3>
                        <a href="{{ route('admin.fraud') }}" class="btn btn-sm btn-outline-danger">Review</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Owner</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($flaggedCampaigns as $campaign)
                                        <tr>
                                            <td>{{ $campaign->title }}</td>
                                            <td>{{ optional($campaign->user)->name ?? 'Unknown' }}</td>
                                            <td>
                                                <span class="badge badge-danger">{{ number_format($campaign->fraud_score, 1) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No flagged campaigns.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection