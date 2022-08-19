<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Http\Requests\PostRequest; // useする

class PostController extends Controller
{
    public function index(Post $post)
    {
        
        $client = new \GuzzleHttp\Client();

        // GET通信するURL
        $url = 'https://teratail.com/api/v1/questions';

        // リクエスト送信と返却データの取得
        // Bearerトークンにアクセストークンを指定して認証を行う
        $response = $client->request(
            'GET',
            $url,
            ['Bearer' => config('services.teratail.token')]
        );
        
        // API通信で取得したデータはjson形式なので
        // PHPファイルに対応した連想配列にデコードする
        $questions = json_decode($response->getBody(), true);
        
        // index bladeに取得したデータを渡す
        return view('index')->with([
            'posts' => $post->getPaginate(),
            'questions' => $questions['questions'],
        ]);
        
        
        
    }

    public function show(Post $post)
    {
        return view('show')->with(['post' => $post]);
    }

    public function create(Category $category)
    {
         return view('create')->with(['categories' => $category->get()]);;
    }

    public function store(Post $post, PostRequest $request) // 引数をRequest->PostRequestにする
    {
        $input = $request['post'];
        $post->fill($input)->save();
        return redirect('/posts/' . $post->id);
    }
    
    public function edit(Post $post)
    {
        return view('edit')->with(['post' => $post]);
    }
    
    public function update(PostRequest $request, Post $post)
    {
         $input_post = $request['post'];
         $post->fill($input_post)->save();

         return redirect('/posts/' . $post->id);
     }
     
    public function delete(Post $post)
    {
        $post->delete();
        return redirect('/');
    }
}