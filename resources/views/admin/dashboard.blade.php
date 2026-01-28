@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</section>

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
            <div class="col-lg-2 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_users'] }}</h3>
                        <p>Total Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('admin.users') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['total_campaigns'] }}</h3>
                        <p>Campaigns</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <a href="{{ route('admin.campaigns') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['active_campaigns'] }}</h3>
                        <p>Active</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <a href="{{ route('admin.campaigns') }}?status=active" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['flagged_campaigns'] }}</h3>
                        <p>Flagged</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-flag"></i>
                    </div>
                    <a href="{{ route('admin.fraud') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $stats['total_donations'] }}</h3>
                        <p>Donations</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="small-box bg-gradient-success">
                    <div class="inner">
                        <h3>₨{{ number_format($stats['total_raised'] / 1000, 1) }}K</h3>
                        <p>Total Raised</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        NPR {{ number_format($stats['total_raised']) }}
                    </a>
                </div>
            </div>
        </div>

        {{-- recent campaigns + flagged --}}
        <div class="row">
            <div class="col-lg-7">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bullhorn mr-1"></i>
                            Recent Campaigns
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.campaigns') }}" class="btn btn-tool btn-sm">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Owner</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentCampaigns as $campaign)
                                        <tr>
                                            <td>
                                                <strong>{{ Str::limit($campaign->title, 40) }}</strong>
                                            </td>
                                            <td>{{ optional($campaign->user)->name ?? 'Unknown' }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-{{ $campaign->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($campaign->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                No campaigns yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($recentCampaigns->count() > 0)
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.campaigns') }}" class="btn btn-sm btn-primary">
                            View All Campaigns <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Flagged Campaigns
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.fraud') }}" class="btn btn-tool btn-sm">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Owner</th>
                                        <th class="text-center">Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($flaggedCampaigns as $campaign)
                                        <tr>
                                            <td>
                                                <strong>{{ Str::limit($campaign->title, 30) }}</strong>
                                            </td>
                                            <td>{{ optional($campaign->user)->name ?? 'Unknown' }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-fire mr-1"></i>{{ number_format($campaign->fraud_score, 1) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                <i class="fas fa-shield-alt fa-2x mb-2 d-block text-success"></i>
                                                No flagged campaigns.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($flaggedCampaigns->count() > 0)
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.fraud') }}" class="btn btn-sm btn-danger">
                            Review All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
