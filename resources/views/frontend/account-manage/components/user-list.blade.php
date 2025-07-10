@foreach ($users as $user)
    @include('frontend.account-manage.components.user-item', [
        'id' => $user->id,
        'avatar' => asset($user->avatar ?? 'frontend/assets/images/user.avif'),
        'name' => $user->name,
        'date' => $user->created_at->format('d/m/Y'),
        'roles' => $user->roles->pluck('name')->join(', ') ?: 'Chưa có',
        'is_active' => $user->is_active,
    ])
@endforeach