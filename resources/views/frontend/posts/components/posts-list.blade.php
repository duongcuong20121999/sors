@foreach ($posts as $post)
    @include('frontend.posts.components.posts-item', [
        'id' => $post->id,
        'thumbnail' => asset($post->thumbnail ?? 'frontend/assets/images/photo.png'),
        'title' => $post->title,
        'date' => $post->date,
        'author' => $post->author,
        'post_publish' => $post->post_publish,
        'category' => $post->category,
    ])
@endforeach