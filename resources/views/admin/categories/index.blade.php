@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">All Categories</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem;">
            <span class="material-symbols-outlined">add</span> Add Category
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Parent Category</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    {{-- Parent Row --}}
                    <tr>
                        <td style="font-weight: 700;">{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>-</td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge-success">Active</span>
                            @else
                                <span class="badge-error">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: var(--space-sm);">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline" style="color: var(--color-error); border-color: var(--color-error); padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    
                    {{-- Children Rows --}}
                    @foreach($category->children as $child)
                    <tr style="background: rgba(0,0,0,0.01);">
                        <td style="font-weight: 500; padding-left: 2.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <span class="material-symbols-outlined" style="font-size: 1rem; color: var(--color-muted); opacity: 0.5;">subdirectory_arrow_right</span>
                            {{ $child->name }}
                        </td>
                        <td>{{ $child->slug }}</td>
                        <td><span style="font-size: 0.8rem; color: var(--color-muted); background: rgba(0,0,0,0.05); padding: 2px 6px; border-radius: 4px;">{{ $category->name }}</span></td>
                        <td>{{ $child->sort_order }}</td>
                        <td>
                            @if($child->is_active)
                                <span class="badge-success">Active</span>
                            @else
                                <span class="badge-error">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: var(--space-sm);">
                                <a href="{{ route('admin.categories.edit', $child) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $child) }}" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline" style="color: var(--color-error); border-color: var(--color-error); padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--color-muted); padding: var(--space-xl);">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: var(--space-lg);">
        {{ $categories->links() }}
    </div>
</div>
@endsection
