$(document).ready(function () {
    let currentCategory = ''; // Lưu nhóm tin đã chọn

    // Bắt sự kiện khi người dùng chọn nhóm tin
    $('#dropdown-list-footer a').on('click', function (e) {
        e.preventDefault();
        currentCategory = $(this).data('category');
        $('#footer-selected-news').text(currentCategory);
        fetchFilteredPosts(1); // Load trang đầu
    });

    // Bắt sự kiện khi bấm phân trang (delegation vì phân trang là động)
    $(document).on('click', '.pagination .page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            fetchFilteredPosts(page);
        }
    });

    // Hàm gọi Ajax với category & page
    function fetchFilteredPosts(page) {
        $.ajax({
            url: window.filterPostUrl ?? '', // dùng biến global gán từ view
            method: 'GET',
            data: {
                category: currentCategory,
                page: page
            },
            success: function (response) {
                $('#list-news-container').html(response);
            },
            error: function () {
                alert('Có lỗi xảy ra trong quá trình lọc bài viết!');
            }
        });
    }
});
