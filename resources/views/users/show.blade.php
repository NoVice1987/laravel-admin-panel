@extends('admin-panel::layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Admin User Details: {{ $user->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">ID</th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>
                                        @if($user->role === 'super_admin')
                                            <span class="badge badge-danger">Super Admin</span>
                                        @else
                                            <span class="badge badge-info">Admin</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $user->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                @if($user->deleted_at)
                                    <tr>
                                        <th>Deleted At</th>
                                        <td class="text-danger">{{ $user->deleted_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>News Articles ({{ $user->news->count() }})</h6>
                            @if($user->news->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Views</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->news as $article)
                                                <tr class="{{ $article->trashed() ? 'table-warning' : '' }}">
                                                    <td>
                                                        @if(!$article->trashed())
                                                            <a href="{{ route('admin.news.edit', $article) }}">{{ $article->title }}</a>
                                                        @else
                                                            {{ $article->title }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-{{ $article->status === 'published' ? 'success' : ($article->status === 'draft' ? 'warning' : 'secondary') }}">
                                                            {{ $article->status }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $article->views_count }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No news articles found.</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit User
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                        @if($user->id !== auth()->guard('admin')->id())
                            @if($user->trashed())
                                <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to restore this user?')">
                                        <i class="fas fa-undo"></i> Restore User
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.force-delete', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to permanently delete this user? This action cannot be undone.')">
                                        <i class="fas fa-trash-alt"></i> Permanently Delete
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash"></i> Delete User
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
