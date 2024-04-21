<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginatRequest;
use App\Models\Blog;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(PaginatRequest $request)
    {
        if(isset($request->search)) {
            $paginate = Blog::where('title','like', '%' . $request->search . '%')->paginate(10);
            $nextPageUrl = $paginate->nextPageUrl();
            $data = $paginate->map(function ($blog) {
                return [
                    'id'            => $blog->id,
                    'title'         => $blog->title,
                    'description'   => $blog->description,
                    'image'         => '/uploads/Blogs/'.$blog->image,
                    'contact'       => $blog->contact,
                ];
            });
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data,
                'next_page_url' => $nextPageUrl,
                'total' => $paginate->count(),
                'currentPage' => $paginate->currentPage(),
                'lastPage' => $paginate->lastPage(),
                'perPage' => $paginate->perPage(),
            ]);
        }
        if($request->paginate) {
            $paginate = Blog::paginate($request->paginate);
            $nextPageUrl = $paginate->nextPageUrl();
            $data = $paginate->map(function ($blog) {
                return [
                    'id'            => $blog->id,
                    'title'         => $blog->title,
                    'description'   => $blog->description,
                    'image'         => '/uploads/Blogs/'.$blog->image,
                    'contact'       => $blog->contact,
                ];
            });
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data,
                'next_page_url' => $nextPageUrl,
                'total' => $paginate->count(),
                'currentPage' => $paginate->currentPage(),
                'lastPage' => $paginate->lastPage(),
                'perPage' => $paginate->perPage(),
            ]);
        } else {
            $paginate = Blog::paginate(10);
            $nextPageUrl = $paginate->nextPageUrl();
            $data = $paginate->map(function ($blog) {
                return [
                    'id'            => $blog->id,
                    'title'         => $blog->title,
                    'description'   => $blog->description,
                    'image'         => '/uploads/Blogs/'.$blog->image,
                    'contact'       => $blog->contact,
                ];
            });
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data,
                'next_page_url' => $nextPageUrl,
                'total' => $paginate->count(),
                'currentPage' => $paginate->currentPage(),
                'lastPage' => $paginate->lastPage(),
                'perPage' => $paginate->perPage(),
            ]);
        }
    }
    public function show($id)
    {
        $data = [];
        $tag = [];
        $record = Blog::find($id);
        $tags = BlogTag::whereHas('blog')->where('blog_id',$id)->get();
        foreach($tags as $row)
        {
            $tag[] = [
                'id'   => $row->id,  
                'name' => $row->name,
            ];
        }
        $data [] = [
            'id' => $record->id,
            'title'       => $record->title,
            'description' => $record->description,
            'contact'     => $record->contact,
            'image'       => 'uploads/Blogs/'.$record->image,
            'tags'        => $tag
        ];
        return response()->json([
            'success' => true,
            'message' => 'Blog found',
            'data'    => $data
        ]);
    }
}
