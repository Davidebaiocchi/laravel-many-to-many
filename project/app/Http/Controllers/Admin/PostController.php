<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Post;
use App\Tag;
use App\Category;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validazione dei dati
        $request->validate([
            'title' => 'required|max:60',
            'content' => 'required'
        ]);

        //prendere i dati
        $data = $request->all();

        //creare la nuova istanza con i dati ottenuti dalla request
        $new_post = new Post();

        $slug = Str::slug($data['title'],'-');

        //******* se c'è un duplicato */
        $slug_base = $slug;

        $slug_presente = Post::where('slug', $slug)->first();

        $contatore = 1;
        while($slug_presente){
            $slug = $slug_base . '-' .$contatore;

            $slug_presente = Post::where('slug', $slug)->first();

            $contatore++;
        }

        //***** end se c'è un duplicato */


        $new_post->slug =  $slug;

        $new_post->fill($data);

        //salvare i dati
        $new_post->save();

        return redirect()->route('admin.posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     //collegamento con lo slug (utilizzato nel front-office)
     public function show($slug)
    {
        var_dump($slug);
        $post = Post::where('slug',$slug)->first();
        return view('admin.posts.show', compact('post'));
        
    }
    //collegamento con id
    // public function show(Post $post)
    // {
    //     return view('admin.posts.show', compact('post'));
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:60',
            'content' => 'required'
        ]);
        $data = $request->all();
        if($data['title'] != $post->title){

            $slug = Str::slug($data['title'],'-'); //titolo-di-esempio
            
            //se lo slug è uguale ad uno già presente
            $slug_base = $slug; //titolo-di-esempio
            $slug_presente = Post::where('slug', $slug)->first();

            $contatore = 1;
            while($slug_presente){
                //aggiungiamo al post di prima -contatore
                $slug = $slug_base . '-' . $contatore; //titolo-di-esempio-1
                //controlliamo se il post esiste ancora
                $slug_presente = Post::where('slug', $slug)->first();
                //incrementiamo il contatore
                $contatore++;
            }

            //in ogni caso assegniamo allo slug il valore ottenuto
            $data['slug'] = $slug;
        } 
        
        

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('updated', 'Hai modificato con successo l\'elemento' .$post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index');
    }
}
