<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    use HttpResponses;

    public function allStats(){
        $users_with_posts_count = User::with("posts")->count();
        $users_count = User::all()->count();
        $posts_count = Post::all()->count();
        $users_with_no_posts_count = $users_count - $users_with_posts_count;
        return $this->success([
            "users"=> $users_count,
            "posts"=> $posts_count,
            "users_with_no_posts"=>$users_with_no_posts_count
        ]);
    }
}
