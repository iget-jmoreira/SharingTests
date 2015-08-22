<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\RestController;
use App\Models\Material;

class MaterialController extends RestController
{
    /**
     * The model class name used by the controller.
     *
     * @var string
     */
    public $model = "App\Models\Material";

    /**
     * The resource name used in routes
     *
     * @var string
     */
    public $resource = "material";
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        $materials = Material::all();
        $return = [];
        foreach ($materials as $key => $material) {
            if($material->subject->id == $id && $material->filtered){
                $return[] = [
                    "id" => $material->id,
                    "name" => $material->name
                ];
            }
        }
        return view('material.index')->with(['materials' => json_encode($return), 'id'=>$id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('material.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('material.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function filter()
    {
        return view('material.filter');
    }

    /**
     * Search in materials.
     *
     * @param  string  $search
     * @return Response
     */
    public function search($id, $search)
    {
        $courses = Material::whereRaw("name LIKE '%".$search."%' OR description LIKE '%".$search."%'")->where('subject_id', $id)->take(6)->get();
        $return = [];
        foreach($courses AS $key=>$course){
            if($course->filtered){
                $return[] = ['id'=>$course->id, 'name'=>$course->name, 'description' => $course->description, 'link_url'=>$course->link_url, 'tag' => $course->tag->name];
            }
        };
        return response()->json($courses);
    }
}