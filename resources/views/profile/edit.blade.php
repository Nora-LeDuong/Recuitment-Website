@extends('layouts.app-main')

@section('title', 'Tìm việc làm - JobPortal')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="sidebar">
                <h5 class="mb-4"><i class="bi bi-funnel me-2"></i>Bộ lọc</h5>
                
                <form action="{{ route('jobs.index') }}" method="GET">
                    <!-- Keyword -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Từ khóa</label>
                        <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="Tìm kiếm...">
                    </div>
                    
                    <!-- Category -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Ngành nghề</label>
                        <select name="category" class="form-select">
                            <option value="">Tất cả ngành nghề</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Location -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Địa điểm</label>
                        <select name="location" class="form-select">
                            <option value="">Tất cả địa điểm</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Job Type -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Loại việc làm</label>
                        <select name="job_type" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="full-time" {{ request('job_type') == 'full-time' ? 'selected' : '' }}>Toàn thời gian</option>
                            <option value="part-time" {{ request('job_type') == 'part-time' ? 'selected' : '' }}>Bán thời gian</option>
                            <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Hợp đồng</option>
                            <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Thực tập</option>
                            <option value="remote" {{ request('job_type') == 'remote' ? 'selected' : '' }}>Làm việc từ xa</option>
                        </select>
                    </div>
                    
                    <!-- Experience -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Kinh nghiệm (năm)</label>
                        <select name="experience" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="0" {{ request('experience') === '0' ? 'selected' : '' }}>Không yêu cầu</option>
                            <option value="1" {{ request('experience') == '1' ? 'selected' : '' }}>≤ 1 năm</option>
                            <option value="2" {{ request('experience') == '2' ? 'selected' : '' }}>≤ 2 năm</option>
                            <option value="3" {{ request('experience') == '3' ? 'selected' : '' }}>≤ 3 năm</option>
                            <option value="5" {{ request('experience') == '5' ? 'selected' : '' }}>≤ 5 năm</option>
                        </select>
                    </div>
                    
                    <!-- Sort -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Sắp xếp</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="salary_high" {{ request('sort') == 'salary_high' ? 'selected' : '' }}>Lương cao nhất</option>
                            <option value="salary_low" {{ request('sort') == 'salary_low' ? 'selected' : '' }}>Lương thấp nhất</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary-gradient w-100">
                        <i class="bi bi-search me-2"></i>Tìm kiếm
                    </button>
                    
                    @if(request()->hasAny(['keyword', 'category', 'location', 'job_type', 'experience']))
                        <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-x-circle me-2"></i>Xóa bộ lọc
                        </a>
                    @endif
                </form>
            </div>
        </div>
        
        <!-- Job Listings -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="bi bi-briefcase me-2"></i>
                    {{ $jobs->total() }} việc làm được tìm thấy
                </h4>
            </div>
            
            @if($jobs->count() > 0)
                @foreach($jobs as $job)
                <div class="job-card">
                    <div class="d-flex align-items-start">
                        <div class="company-logo me-3">
                            {{ strtoupper(substr($job->company->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="job-title">
                                        <a href="{{ route('jobs.show', $job) }}">{{ $job->title }}</a>
                                    </h5>
                                    <p class="company-name">{{ $job->company->name }}</p>
                                </div>
                                @if($job->is_featured)
                                    <span class="job-tag featured"><i class="bi bi-star-fill me-1"></i>Nổi bật</span>
                                @endif
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="job-tag location"><i class="bi bi-geo-alt me-1"></i>{{ $job->location->name }}</span>
                                <span class="job-tag salary"><i class="bi bi-cash me-1"></i>{{ $job->salary_range }}</span>
                                <span class="job-tag type">{{ $job->job_type_display }}</span>
                                @if($job->experience_required > 0)
                                    <span class="job-tag" style="background: #fff5f5; color: #c53030;">
                                        <i class="bi bi-clock-history me-1"></i>{{ $job->experience_required }}+ năm KN
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-muted mb-3">{{ Str::limit(strip_tags($job->description), 150) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>{{ $job->created_at->diffForHumans() }}
                                    @if($job->deadline)
                                        <span class="ms-3"><i class="bi bi-calendar me-1"></i>Hạn: {{ $job->deadline->format('d/m/Y') }}</span>
                                    @endif
                                </small>
                                <a href="{{ route('jobs.show', $job) }}" class="btn btn-sm btn-primary-gradient rounded-pill">
                                    Xem chi tiết <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $jobs->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h4 class="mt-4">Không tìm thấy việc làm</h4>
                    <p class="text-muted">Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                    <a href="{{ route('jobs.index') }}" class="btn btn-primary-gradient">Xem tất cả việc làm</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
