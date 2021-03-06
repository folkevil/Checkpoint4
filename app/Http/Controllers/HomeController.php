<?php

namespace Techademia\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Techademia\Video;
use Techademia\Category;
use Illuminate\Http\Request;
use Techademia\Http\Requests;
use Techademia\Repositories\VideoRepository;
use Techademia\Http\Controllers\Controller;


class HomeController extends Controller
{

    public function __construct(VideoRepository $video)
    {
        $this->video = $video;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check() !== false) {
            return redirect('/feeds');
        }

       return view('welcome');
    }

    /**
     * Displays videos all users have uploaded.
     * accessible by both guests and registered users
     * @return [type] [description]
     */
    public function feeds()
    {
        $videos = $this->video->paginate(6);
        $format = Carbon::now()->subMonth();
        $latest = $this->video->whereDateFormat('created_at', '>=', $format);

        return view('pages.feed', compact('videos', 'latest'));
    }

    /**
     * display all videos from a particular category
     * @param $id
     * @return \Illuminate\Http\Response
     */

    public function feedsByCategory($id)
    {
        $videos = Video::where('category_id', '=', $id)->get();
        $format = Carbon::now()->subMonth();
        $latest = $this->video->whereDateFormat('created_at', '>=', $format);

        return view('pages.categoryfeeds', compact('videos', 'latest'));
    }
}
