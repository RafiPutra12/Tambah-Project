<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'userid'       => 'required|integer',
			'descriptions' => 'required|string|max:255',
            'budget'       => 'required|integer',
            'type'         => 'required|in:private,public',
            'projectname'  => 'required|string|max:255',
            'status'       => 'required|in:open,close',
		]);

		if($validator->fails()){
			return response()->json([
				'status'	=> 0,
				'message'	=> $validator->errors()->toJson()
			]);
        }
        
        $data = new Project();
        $data->userid            = $request->input('userid');
        $data->descriptions      = $request->input('descriptions');
        $data->budget            = $request->input('budget');
        $data->type              = $request->input('type');
        $data->projectname       = $request->input('projectname');
        $data->status            = $request->input('status');
        $data->save();

        return response()->json($data);
    }

    public function update($id, Request $request)
	{
		$data = Project::where('id', $id)->first();
		$data->descriptions = $request->descriptions;
        $data->budget       = $request->budget;       
        $data->type         = $request->type;   
        $data->projectname  = $request->projectname;   
        $data->status       = $request->status;   
		$data->updated_at   = now()->timestamp;
		$data->save();

		return response()->json([
			'status'  =>  '1',
			'message' =>  'Update Data Project Berhasil'
		]);
    }

    public function getAll($limit = 10, $offset = 0)
    {
        $data["count"] = Project::count();
        $project = array();

        foreach (Project::take($limit)->skip($offset)->get() as $p){
            $item = [
                "id"                => $p->id,
                "userid"            => $p->projects->id,
                "descriptions"      => $p->descriptions,
                "budget"            => $p->budget,
                "type"              => $p->type,
                "projectname"       => $p->projectname,
                "status"            => $p->status,
            ];

            array_push($project, $item);
        }
        $data["projects"] = $project;
        $data["status"] = 1;
        return response($data);
    }

    public function destroy($id)
	{
		$data = Project::where('id', $id)->first();
		$data->delete();

		return response()->json([
			'status'  =>  '1',
			'message' =>  'Delete Data Project Berhasil'
		]);
	}
}
