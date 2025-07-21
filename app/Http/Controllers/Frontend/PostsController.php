<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       
        $currentCategory = $request->input('category', 'Lựa chọn tất cả');
        $currentPage = $request->input('page', 1);


        $list_service = Service::all();

  
        $posts = Post::query();

        if ($currentCategory !== 'Lựa chọn tất cả') {
            $posts->where('category', $currentCategory);
        }


        $posts = $posts->orderBy('date', 'desc')->paginate(10, ['*'], 'page', $currentPage);

  
        return view('frontend.posts.index', compact('posts', 'list_service', 'currentCategory', 'currentPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
     
        $currentCategory = $request->input('category', 'Lựa chọn tất cả');
        $currentPage = $request->input('page', 1);

       
        $list_service = Service::all();
        
     
        $posts = Post::query();

      
        if ($currentCategory !== 'Lựa chọn tất cả') {
            $posts->where('category', $currentCategory);
        }

   
        $posts = $posts
                   ->paginate(10, ['*'], 'page', $currentPage);
        return view('frontend.posts.create', compact('posts', 'list_service', 'currentCategory', 'currentPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('posts.store')) {
            $notification = [
                'message' => 'Bạn không có quyền thêm bài viết!',
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            // 'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'category' => 'required',
            'service_description' => 'required',
            'content' => 'required',
        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'category.unique' => 'Nhóm tin không được để trống',
            'service_description.unique' => 'Nội dung ngắn gọn không được để trống',
            'content.unique' => 'Nội dung chi tiết không được để trống',


        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại các trường bắt buộc.');
        }



        $data = [
            'title' => $request->input('title'),
            'author' => Auth::user()->name,
            'category' => $request->input('category'),
            'service_description' => $request->input('service_description'),
            'content' => $request->input('content'),
            'post_publish' => $request->has('post_publish') ? 1 : 0,
            'date' => Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y'),
        ];


        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('storage/thumbnail'), $filename); // 👈 Lưu trực tiếp vào public

            $data['thumbnail'] = '/storage/thumbnail/' . $filename;
        }





        Post::create($data);


        $notification = [
            'message' => 'Tạo tin tức thành công!',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $post_update = Post::findOrFail($id);
        $list_service = Service::all();

        
        $currentCategory = $request->input('category', 'Lựa chọn tất cả');
        
        $currentPage = $request->input('page', 1);


        $postsQuery = Post::query();
        if ($currentCategory !== 'Lựa chọn tất cả') {
     
            $postsQuery->where('category', $currentCategory);
        }
        $posts = $postsQuery->paginate(10, ['*'], 'page', $currentPage)->appends(['category' => $currentCategory]);;

        return view('frontend.posts.edit', compact('post_update', 'list_service', 'currentCategory', 'currentPage', 'posts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        if (!Auth::user()->can('posts.update')) {
            $notification = [
                'message' => 'Bạn không có quyền sửa bài viết!',
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'service_description' => 'nullable|string',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'category.unique' => 'Nhóm tin không được để trống',
            'service_description.unique' => 'Nội dung ngắn gọn không được để trống',
            'content.unique' => 'Nội dung chi tiết không được để trống',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại các trường bắt buộc.');
        }


        $post = Post::findOrFail($id);



        $data = [
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            'service_description' => $request->input('service_description'),
            'content' => $request->input('content'),
            'post_publish' => $request->has('post_publish') ? 1 : 0,
            'date' => Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y'),

        ];

        // Upload ảnh nếu có file mới
        if ($request->hasFile('thumbnail')) {
            // Xoá ảnh cũ nếu có
            if ($post->thumbnail && file_exists(public_path($post->thumbnail))) {
                unlink(public_path($post->thumbnail));
            }

            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/thumbnails'), $filename);

            $data['thumbnail'] = '/storage/thumbnails/' . $filename;
        }

        // Cập nhật bài viết
        $post->update($data);

        // Notification
        $notification = [
            'message' => 'Bài viết đã được cập nhật thành công!',
            'alert-type' => 'success',
        ];
        return redirect()->route('posts.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    $post = Post::findOrFail($id);

    $post->delete();

     $notification = [
            'message' => 'Bài viết đã được xoá thành công!',
            'alert-type' => 'success',
        ];


    // Redirect với thông báo thành công
    return redirect()->route('posts.index')->with($notification);
    }

    public function filter(Request $request)
    {

        $category = $request->input('category', '');
        $posts = Post::query();

        if ($category && $category !== 'Lựa chọn tất cả') {
            $posts->where('category', $category);
        }

        // Paginate 2 bài mỗi trang
        $posts = $posts->orderBy('date', 'desc')->paginate(10);

        // Nếu request là Ajax, trả lại phần HTML danh sách (partial)
        if ($request->ajax()) {
            return response()->json([
                'posts' => view('frontend.posts.components.posts-list', compact('posts'))->render(),
                'pagination' => view('frontend.posts.components.custom-pagination', compact('posts'))->render(),
            ]);
        }

        // Nếu không phải Ajax, return trang gốc (hiếm khi xảy ra)
        return view('frontend.posts.index', compact('posts'));
    }
}
