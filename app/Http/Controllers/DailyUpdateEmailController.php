<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\SendDailyUpdateEmailJob;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DailyUpdateEmailController extends Controller
{
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
        return view('daily_update.index');
    }

    public function sendEmail()
    {
        $user               = Auth::user();
        $userId             = $user->id;
        $query              = User::select('*')->get();

        $queryData          = null;

        if (empty($userId))
        {
            $outputMsg['type']      = 'danger';
            $outputMsg['message']   = 'Seems like you have been logged Out, Please Refresh Your Page.';
            return view('daily_update.index', compact('outputMsg'));
        }

        if(!empty($query))
        {
            foreach ($query as $data)
            {
                $queryData = [
                    'id'            => $data->id,
                    'name'          => $data->name,
                    'email'         => $data->email,
                ];

                $prepareEmailDelay = ( new SendDailyUpdateEmailJob($queryData))->delay(Carbon::now()->addSeconds(3));

                dispatch( $prepareEmailDelay );
            }

            $outputMsg['type']      = 'success';
            $outputMsg['message']   = 'Email sent successfully.';
            return view('daily_update.index', compact('outputMsg'));
        }
        else
        {
            $outputMsg['type']      = 'danger';
            $outputMsg['message']   = 'Something went wrong.';
            return view('daily_update.index', compact('outputMsg'));
        }

    }
}
