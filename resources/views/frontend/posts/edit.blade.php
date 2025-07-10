@extends('layouts.master')

@section('title', 'Dashboard Admin')

@section('content')


    <div class="container-news-management show">
        <div class="header-news d-flex justify-content-between align-items-center">
            <p class="mb-0">Quản lý tin tức</p>
            <a href="{{ route('posts.create') }}" class="btn-add">Thêm mới</a>
        </div>

        <div class="container">
            <div class="row mt-4">
                <!-- list -->
                <div class="list-news col-md-4 position-relative px-3 d-flex flex-column">
                    <!-- news -->

                    <div id="list-news-container">
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
                    </div>

                    <div class="footer-news mt-auto mb-1 justify-content-between flex-lg-wrap">
                        <!-- filter the news -->

                        <div class="footer-choose-news mt-1 mb-1 mx-1" data-bs-toggle="dropdown" aria-expanded="false">
                            <p id="footer-selected-news" class="mb-0">
                                {{ $currentCategory }}
                            </p>
                            <div class="dropdown-footer ms-auto dropup">
                                <a aria-label="Chọn nhóm tin" class="btn btn-outline-secondary p-2 d-flex align-items-center" href="#"
                                    role="button" id="dropdownMenuButton">
                                    <ion-icon name="chevron-down-outline" id="dropdown-icon"></ion-icon>
                                </a>
                                <ul class="dropdown-menu" id="dropdown-list-footer" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a class="dropdown-item {{ $currentCategory == 'Lựa chọn tất cả' ? 'active' : '' }}"
                                            href="{{ route('posts.index', ['category' => 'Lựa chọn tất cả', 'page' => $currentPage]) }}"
                                            data-category="Lựa chọn tất cả">Lựa chọn tất cả
                                        </a>
                                    </li>
                                    @foreach ($list_service as $item)
                                        <li><a aria-label="Tất cả" class="dropdown-item" href="#"
                                                data-category="{{ $item->name }}">{{ $item->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- pagination -->
                        @include('frontend.posts.components.custom-pagination', ['posts' => $posts])
                    </div>

                    <div class="divider-line"></div>
                </div>


                <!-- Display content -->
                <div class="col-md-8 display-news px-4">
                    <form action="{{ route('posts.update', $post_update->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id_service" value="{{ $post_update->id ?? '' }}">



                        <div class="select-image d-flex align-items-center">
                            <div class="image-wrapper" style="border: 1px solid #28CD56;"  id="imageWrapper">
                                <img id="selected-image-post"
                                    class="photo-post {{ $post_update->thumbnail ? 'full-image' : '' }}"
                                    src="{{ old('thumbnail', asset($post_update->thumbnail ?? 'frontend/assets/images/photo.png')) }}"
                                    alt="logo">
                            </div>
                            <input type="file" name="thumbnail" id="file-input-post" style="display: none;">
                            <a class="choose-image-btn-post" id="choose-image-btn-post">Chọn ảnh bài viết</a>
                        </div>

                        <div class="news-name mt-3">
                            <label for="post1" class="mb-2">Tiêu đề:</label>
                            <input id="post1" type="text" name="title" class="form-control"
                                value="{{ old('title', $post_update->title ?? '') }}">
                        </div>
                        <div class="mt-3">
                            <label for="choose-news" class="mb-2">Chọn nhóm tin:</label>
                            <div class="choose-news" data-bs-toggle="dropdown" aria-expanded="false">
                                <p id="selected-news" class="mb-0">{{ old('category', $post_update->category ?? '') }}
                                </p>
                                <div class="dropdown ms-auto">
                                    <a aria-label="Chọn nhóm tin" class="btn btn-outline-secondary  p-2 d-flex align-items-center" href="#"
                                        role="button" id="dropdownMenuButton">
                                        <ion-icon name="chevron-down-outline" id="dropdown-icon"></ion-icon>
                                    </a>
                                    <ul class="dropdown-menu" id="dropdown-list" aria-labelledby="dropdownMenuButton">
                                        @foreach ($list_service as $item)
                                            <li><a aria-label="Tất cả" class="dropdown-item" href="#"
                                                    data-category="{{ $item->name }}">{{ $item->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="category" value="{{ old('category', $post_update->category ?? '') }}"
                            id="selected-category">

                        <div class="service-news mt-3">
                            {{-- <p class="mb-2">Nội dung ngắn gọn:</p> --}}
                            <label class="mb-2" for="post2">Nội dung ngắn gọn:</label>
                            <textarea class="form-control" name="service_description" id="post2">{{ old('service_description', $post_update->service_description ?? '') }}</textarea>
                        </div>
                        <div class="mt-3">
                            <label for="editor" class="form-label mb-2">Nội dung chi tiết:</label>
                            <div id="editor" class="quill-editor"></div>
                        </div>

                        <input type="hidden" name="content" id="quill-content">
                        <div class="publish-articles d-flex align-items-center mt-3">
                            <input id="post_publish" type="checkbox" name="post_publish" class="ms-2"
                                {{ old('post_publish', $post_update->post_publish) ? 'checked' : '' }}>
                            <label style="color: #4F4F4F;" for="post_publish" class="ms-3">Xuất bản bài viết</label>
                        </div>
                        <div class="button-news d-flex justify-content-end">
                            <a href="{{ url()->current() }}" class=" cancel-news me-2">Hủy</a>
                            <button type="submit" class=" save-news">Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline"],
                        [{
                            size: ['8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18']
                        }],
                        [{
                                align: ""
                            },
                            {
                                align: "center"
                            },
                            {
                                align: "right"
                            },
                            {
                                align: "justify"
                            },
                        ],
                        [{
                            list: "ordered"
                        }, {
                            list: "bullet"
                        }],
                        [{
                            background: []
                        }],
                        ["image"],
                    ],
                },
            });
            const toolbarButtons = document.querySelectorAll('.ql-toolbar button');
            toolbarButtons.forEach(button => {
            if (button.classList.contains('ql-bold')) button.setAttribute('aria-label', 'In đậm');
            else if (button.classList.contains('ql-italic')) button.setAttribute('aria-label', 'In nghiêng');
            else if (button.classList.contains('ql-underline')) button.setAttribute('aria-label', 'Gạch chân');
            else if (button.classList.contains('ql-image')) button.setAttribute('aria-label', 'Chèn ảnh');
            else if (button.classList.contains('ql-list')) button.setAttribute('aria-label', 'Danh sách');
            else if (button.classList.contains('ql-align')) button.setAttribute('aria-label', 'Căn chỉnh');
            });
            const toolbar = quill.getModule('toolbar');
            toolbar.addHandler('size', function(value) {
                if (value) {
                    const size = value + 'px';
                    quill.format('size', size);
                }
            });

            window.addEventListener('load', function() {
            
                var oldContent = `{!! old('content') !!}`;
                if (oldContent) {
                    quill.root.innerHTML = oldContent;
                }

          
                const oldCategory = "{{ old('category') }}";
                if (oldCategory) {
                    document.getElementById('selected-news').textContent = oldCategory;
                    document.getElementById('selected-category').value = oldCategory;
                }
            });

        
            const oldContent = {!! json_encode(old('content', $post_update->content ?? '')) !!};
            quill.clipboard.dangerouslyPasteHTML(oldContent);

        
            document.querySelector('.save-news').addEventListener('click', function() {
          
                document.querySelector("#quill-content").value = quill.root.innerHTML;

  
                const selectedCategoryText = document.querySelector("#selected-news").innerText;
                document.querySelector("#selected-category").value = selectedCategoryText;

             
                document.querySelector("#service-form").submit();
            });

        
            document.querySelectorAll("#dropdown-list .dropdown-item").forEach(item => {
                item.addEventListener("click", function(e) {
                    e.preventDefault();
                    document.querySelector("#selected-news").innerText = this.innerText;
                });
            });

          
            
            var currentCategory = "{{ $currentCategory }}"; // Lưu nhóm tin đang chọn

            
            $('#dropdown-list-footer a').on('click', function(e) {
                e.preventDefault();
                currentCategory = $(this).data('category');
                $('#footer-selected-news').text(currentCategory);
                fetchFilteredPosts(1);
            });

            
            $(document).on('click', '.pagination .page-link', function(e) {
                e.preventDefault();

                const page = $(this).data('page');
                if (page) {

                    const newUrl = updateQueryStringParameter(window.location.href, 'page', page);
                    window.history.pushState({
                        path: newUrl
                    }, '', newUrl);


                    fetchFilteredPosts(page);

                    $('.pagination .page-item').removeClass('active');
                    $(this).parent().addClass('active');

                    // updateActivePage(page);
                }
            });


            function updateQueryStringParameter(uri, key, value) {
                var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
                var separator = uri.indexOf('?') !== -1 ? "&" : "?";
                if (uri.match(re)) {
                    return uri.replace(re, '$1' + key + "=" + value + '$2');
                } else {
                    return uri + separator + key + "=" + value;
                }
            }


            function fetchFilteredPosts(page) {
                $.ajax({
                    url: "{{ route('posts.filter') }}",
                    method: 'GET',
                    data: {
                        category: currentCategory,
                        page: page
                    },
                    
                    success: function(data) {
                        $('#list-news-container').html(data.posts);
                        if (data.pagination.trim() !== '') {
                            $('#pagination').html(data.pagination).show();
                        } else {
                            $('#pagination').empty().hide();
                        }

                    },
                    error: function() {
                        alert('Có lỗi xảy ra trong quá trình lọc bài viết!');
                    }
                });
                console.log(currentCategory);

            }
        });

        function updateDividerLine() {
            setTimeout(() => {
                const container = document.getElementById("list-news-container");
           
                const divider = document.querySelector(".divider-line");

                if (container && divider) {
                    const isOverflowing = container.scrollHeight > container.clientHeight;
                    divider.style.display = isOverflowing ? "none" : "block";
                }
            }, 50);

        }

        updateDividerLine();

        

        
    </script>





@endsection
