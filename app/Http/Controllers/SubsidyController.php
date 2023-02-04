<?php

namespace App\Http\Controllers;

use App\Models\Subsidy;
use App\Http\Requests\StoreSubsidyRequest;
use App\Http\Requests\UpdateSubsidyRequest;
use Illuminate\Http\Request;

class SubsidyController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $subsidy = Subsidy::orderBy('created_at', 'desc')->paginate($per_page);
        return $subsidy;
    }

    public function store(StoreSubsidyRequest $request)
    {
        $val  = calculate_funding_amount($request);
        $subsidy = Subsidy::where('lsg', $request->lsg)
            ->where('kat', $request->kat)
            ->where('nw', $request->nw)
            ->where('hb', $request->hb)
            ->where('ev', $request->ev)->first();
        if ($subsidy) {
            return  $subsidy;
        } else {
            $subsidy              = new Subsidy();
            $subsidy->title       = $request->title;
            $subsidy->description = $request->description;
            $subsidy->lsg         = $request->lsg;     // pv value
            $subsidy->ibm         = $request->ibm;     //date
            $subsidy->kat         = $request->kat;     //type
            $subsidy->nw          = $request->nw;      //Neigungswinkel ≥ 75 Grad
            $subsidy->hb          = $request->hb;      //Höhenbonus ab 1500m
            $subsidy->ev          = $request->ev;      //kein Eigenverbrauch
            $subsidy->pronovo_id  = $val->id;          //id from response 
            if (!$subsidy->save())
                return response('Subsidy not added', 500);
            $cal_val = get_result($subsidy->pronovo_id);
            $subsidy->vgb  =  $cal_val->vgb;
            $subsidy->nwbt =  $cal_val->nwbt;
            $subsidy->hbbt =  $cal_val->hbbt;
            $subsidy->grb  = $cal_val->output[0]->grb;
            $subsidy->lsb1 = $cal_val->output[0]->lsb1;
            $subsidy->lsb2 = $cal_val->output[0]->lsb2;
            $subsidy->lsb3 = $cal_val->output[0]->lsb3;
            if ($subsidy->save())
                return $subsidy;
            return response('Subsidy not added', 500);
        }
    }

    public function show(Subsidy $subsidy)
    {
        //
    }

    public function update(UpdateSubsidyRequest $request, Subsidy $subsidy)
    {
        //
    }

    public function destroy(Subsidy $subsidy)
    {
        //
    }
}
