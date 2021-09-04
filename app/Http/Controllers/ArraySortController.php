<?php

namespace App\Http\Controllers;

use App\Models\Pair;
use App\Models\Sort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArraySortController extends Controller
{
    protected $rules = array(
        'array'         => 'required|min:2|max:255',
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
        return view('array_sort.index');
    }

    public function store(Request $request)
    {
        $user           = Auth::user();
        $userId         = $user->id;
        $array          = $request->input('array');

        $sort           = new Sort();

        $string_to_array    = explode (",", $array);;

        if ( empty($userId) )
        {
            $result['success'] = false;
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

        $sorted_array           = $sort->sortAnArray($string_to_array);

        $values = [
            'unsorted_array'    => json_encode($string_to_array),
            'sorted_array'      => json_encode($sorted_array),
            'created_by'        => $userId
        ];

        $createQuery            = Sort::insert($values);

        if (!$createQuery)
        {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong while Inserting into Database';
            return $result;
        }
        else
        {
            $result['success'] = true;
            $result['message'] = 'Given array sorted successfully';
            return $result;
        }
    }

    public function show(Request $request)
    {

        $noOfRecords        = 3;
        $queryData          = null;
        $filterApplied      = null;
        $query              = Sort::select('*');

        $query = $query->orderBy('id', 'desc')->paginate($noOfRecords);

        foreach ($query as $data) {
            $queryData[] = [
                'id'                => $data->id,
                'unsorted_array'    => $data->unsorted_array,
                'sorted_array'      => $data->sorted_array,
                'created_by'        => $data->created_by,
                'created_at'        => date("F d, Y", strtotime($data->created_at)),
            ];

        }
        $response['success'] = 'true';
        $response['filterApplied'] = $filterApplied;
        $response['totalRecords'] = $query->total();
        $response['data'] = $queryData;
        $response['webPagination'] = str_replace("\r\n", "", $query->links('layouts.pagination'));

        return $response;

    }


}
