<?php

namespace App\Http\Controllers;

use App\Models\Pair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CountThePairController extends Controller
{
    protected $rules = array(
        'array'         => 'required|min:2|max:255',
        'left_range'    => 'required|numeric|min:1|max:10',
        'right_range'   => 'required|numeric|min:1|max:100',
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
        return view('count_the_pair.index');
    }

    public function store(Request $request)
    {
        $user           = Auth::user();
        $userId         = $user->id;
        $array          = $request->input('array');
        $left_range     = $request->input('left_range');
        $right_range    = $request->input('right_range');

        $pair           = new Pair();

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

        $sizeof_array       = sizeof($string_to_array);

        $pair_count         = $pair->countThePair($string_to_array,$sizeof_array,$left_range,$right_range);

        $values = [
            'array'         => json_encode($string_to_array),
            'pairs'         => json_encode($pair_count['final_pair']),
            'left_range'    => $left_range,
            'right_range'   => $right_range,
            'total_pair'    => $pair_count['final_count'],
            'created_by'    => $userId
        ];

        $createQuery = Pair::insert($values);

        if (!$createQuery)
        {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong while Inserting into Database';
            return $result;
        }
        else
        {
            $result['success'] = true;
            $result['message'] = 'Count pair created successfully';
            return $result;
        }
    }

    public function show(Request $request)
    {
        $noOfRecords        = 3;
        $queryData          = null;
        $filterApplied      = null;
        $query              = Pair::select('*');

        $query              = $query->orderBy('id', 'desc')->paginate($noOfRecords);

        foreach ($query as $data)
        {
            $queryData[] = [
                'id' => $data->id,
                'array' => $data->array,
                'pairs' => $data->pairs,
                'left_range' => $data->left_range,
                'right_range' => $data->right_range,
                'total_pair' => $data->total_pair,
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
}
