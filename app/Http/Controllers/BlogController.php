<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $rules = array(
        'title'         => 'required|min:6|max:255',
        'description'   => 'required|min:6|max:5000',
        'image'         => 'file|required|mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:2048'
    );

    public function __construct()
    {
        $this->middleware('auth')->only('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('blog.index');
    }

    public function store(Request $request)
    {

        $user                       = Auth::user();
        $userId                     = $user->id;
        $title                      = $request->input('title');
        $description                = $request->input('description');
        $imageNameWithExtension     =   null;

        if (empty($userId))
        {
            $result['success'] = true;
            $result['message'] = 'Seems like you have been logged Out, Please Refresh Your Page';
            return $result;
        }

        $validators = Validator::make($request->all(), $this->rules);

        if ($validators->fails())
        {
            $result['success'] = false;
            $result['message'] = $validators->errors()->first();
            return $result;
        }

        if($request->hasFile('image'))
        {
            $image = $request->file('image')->getClientOriginalName();
            $imageName = pathinfo($image, PATHINFO_FILENAME);
            $imageExtension = $request->file('image')->getClientOriginalExtension();
            $imageNameWithExtension = str_replace(' ', '', $imageName) . time() . '.' . $imageExtension;
            if (env('APP_ENV') == 'local')
            {
                $request->file('image')->move(public_path('images'),$imageNameWithExtension);
            }
            else
            {
                $request->file('image')->storeAs('public/blog-images',$imageNameWithExtension);
            }
        }

        $values = [
            'title'         => $title,
            'slug'          => Str::slug($title),
            'description'   => $description,
            'image'         => $imageNameWithExtension,
            'created_by'    => $userId
        ];

        $createQuery        = Blog::insert($values);

        if (!$createQuery)
        {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong while Inserting into Database';
            return $result;
        }
        else
        {
            $result['success'] = true;
            $result['message'] = 'Post add successfully';
            return $result;
        }
    }

    public function show(Request $request)
    {
        $noOfRecords        = 2;
        $queryData          = null;
        $filterApplied      = null;
        $createdAt          = $request->input('created_at');
        $title              = $request->input('title');
        $query              = Blog::select('*')->where('deleted',0);

        $blog =             new Blog();

        if (!empty($title))
        {
            $query = $query->where('title', 'like', '%' . $title . '%');
            $filterMessageTitle = strlen($title) > 10 ? substr($title,0,50)."..." : $title;
            $filterApplied['title'] = $filterMessageTitle;
        }

        if (!empty($createdAt))
        {
            $query = $query->where('created_at', 'like', '%' . $createdAt . '%');
            $filterApplied['created_at'] = 'Created On';
        }

        $query = $query->orderBy('blogs.id', 'desc')->paginate($noOfRecords);


        foreach ($query as $data)
        {
            $queryData[] = [
                'id' => $data->id,
                'title' => $data->title,
                'slug' => $data->slug,
                'description' => $data->description,
                'image' => ( asset($blog->getImage($data->image) )),
                'created_by' => $data->created_by,
                'created_at' => date("F d, Y", strtotime($data->created_at)),
            ];
        }

        $response['success']        = 'true';
        $response['filterApplied']  = $filterApplied;
        $response['totalRecords']   = $query->total();
        $response['data']           = $queryData;
        $response['webPagination']  = str_replace("\r\n", "", $query->links('layouts.pagination'));

        return $response;
    }

    public function edit(Request $request)
    {
        $id         = $request->input('id');
        $user       = Auth::user();
        $userId     = $user->id;
        $data       = null;

        $blog       = new Blog();

        $query      = Blog::where('id', $id)->first();

        if ($query->count() == 0)
        {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong Will Fetching data, Please Refresh Your Page';
            return $result;
        }

        $data[] = [
            'id' => $query->id,
            'title' => $query->title,
            'description' => $query->description,
            'image' => ( asset($blog->getImage($query->image) )),
        ];

        $result['success'] = true;
        $result['data'] = $data;
        return $result;

    }

    public function update(Request $request)
    {
        $user                       = Auth::user();
        $userId                     = $user->id;
        $id                         = $request->input('id');
        $title                      = $request->input('title');
        $description                = $request->input('description');
        $imageNameWithExtension     = null;

        $query                      = Blog::select('*')->where('id', $id);

        if (empty($userId))
        {
            $result['success'] = true;
            $result['message'] = 'Seems like you have been logged Out, Please Refresh Your Page';
            return $result;
        }

        if ($query->count() == 0)
        {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong Will Updating, Please Refresh Your Page';
            return $result;
        }

        $validators = Validator::make($request->all(), $this->rules);

        if ($validators->fails())
        {
            $result['success'] = false;
            $result['message'] = $validators->errors()->first();
            return $result;
        }

        if($request->hasFile('image'))
        {
            $image = $request->file('image')->getClientOriginalName();
            $imageName = pathinfo($image, PATHINFO_FILENAME);
            $imageExtension = $request->file('image')->getClientOriginalExtension();
            $imageNameWithExtension = str_replace(' ', '', $imageName) . time() . '.' . $imageExtension;
            if (env('APP_ENV') == 'local')
            {
                $request->file('image')->move(public_path('images'),$imageNameWithExtension);
            }
            else
            {
                $request->file('image')->storeAs('public/blog-images',$imageNameWithExtension);
            }
        }
        else
        {
            $fetchOldImg            = Blog::select('image')->where('id', $id);
            $imageNameWithExtension = $fetchOldImg;
        }

        $updateValues = [
            'title'         => $title,
            'slug'          => Str::slug($title),
            'description'   => $description,
            'image'         => $imageNameWithExtension,
            'updated_by'    => $userId
        ];

        $query = Blog::where('id', $id)->update($updateValues);

        if (!$query)
        {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong while Updating into Database';
            return $result;
        }
        else
        {
            $result['success'] = true;
            $result['message'] = 'Blog updated successfully';
            return $result;
        }
    }

    public function destroy(Request $request)
    {
        $user   = Auth::user();
        $userId = $user->id;
        $id     = $request->input('id');

        $query  = Blog::where('id', $id);


        if (empty($userId))
        {
            $result['success'] = true;
            $result['message'] = 'Seems like you have been logged Out, Please Refresh Your Page';
            return $result;
        }

        if ($query->count() == 0)
        {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong Will Deleting, Please Refresh Your Page';
            return $result;
        }

        $query = Post::where('id', $id)->update(['deleted'=>1]);

        if (!$query)
        {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong while Deleted into Database';
            return $result;
        }
        else
        {
            $result['success'] = true;
            $result['message'] = 'Blog Deleted successfully';
            return $result;
        }
    }



}
