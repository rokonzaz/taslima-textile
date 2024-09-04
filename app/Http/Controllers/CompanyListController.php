<?php

namespace App\Http\Controllers;
use App\Models\Organization;

use Illuminate\Http\Request;

class CompanyListController extends Controller
{
    public function create(){
        return view("settings.organization-list.create");
    }
    public function index(){
        $organizations = Organization::all();
        return view("settings.organization-list.index", compact('organizations'));

    }
    public function store(Request $request){
        // return $request;
        $organization = new Organization;
        $organization->name = $request->name;
        $organization->is_active = $request->is_active;
        $organization->save();
        return redirect()->route('organization-list.index')->with('success', 'organizations created successfully');
    }

    public function edit($id){
        $organization = Organization::find($id);
        return view('settings.organization-list.edit', compact('organization'));
    }

    public function update(Request $request, $id){
        $organization = Organization::find($id);
        $organization->name = $request->name;
        $organization->is_active = $request->is_active;
        $organization->save();
        // session()->flash('key', 'value');
        return redirect()->route('organization-list.index')->with('success', 'organizations updated successfully');
    }
    public function delete($id){
        $organization = Organization::findOrFail($id);
        $organization->delete();

        return redirect()->route('organization-list.index')->with('success', 'Organization deleted successfully');
    }

}
