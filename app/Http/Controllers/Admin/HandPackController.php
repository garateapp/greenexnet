<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyHandPackRequest;
use App\Http\Requests\StoreHandPackRequest;
use App\Http\Requests\UpdateHandPackRequest;
use App\Models\HandPack;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class HandPackController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('packing'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = HandPack::query()->select(sprintf('%s.*', (new HandPack)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'hand_pack_show';
                $editGate      = 'hand_pack_edit';
                $deleteGate    = 'hand_pack_delete';
                $crudRoutePart = 'hand-packs';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('rut', function ($row) {
                return $row->rut ? $row->rut : '';
            });

            $table->editColumn('embalaje', function ($row) {
                return $row->embalaje ? HandPack::EMBALAJE_SELECT[$row->embalaje] : '';
            });
            $table->editColumn('guuid', function ($row) {
                return $row->guuid ? $row->guuid : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.handPacks.index');
    }
    public function lector(){
        return view("admin.handPacks.lector");
    }
    public function lectorQr(Request $request){
        $qr = $request->get('qr');
        
        $data=explode("]",$qr);
        
        $handPack = HandPack::where('guuid', $data[2])->count();
        
        if($handPack==0){
        $handPack=new HandPack();
        $fecha=date('Y-m-d H:i:s');
        $rut=str_replace("'","-",$data[0]);
        $embalaje=$data[1];
        $handPack->rut=$rut;
        $handPack->embalaje=$embalaje;
        $handPack->fecha=$fecha;
        $handPack->guuid=$data[2];
        $res=$handPack->save();
        return response()->json(['success' => 'success', 'data' => $handPack]);
        }
        return response()->json(['error' => 'error', 'data' => $handPack]);
        // if($handPack){
        //     return response()->json(['status' => 'success', 'data'

    }
    public function create()
    {
        abort_if(Gate::denies('packing'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.handPacks.create');
    }

    public function store(StoreHandPackRequest $request)
    {
        $handPack = HandPack::create($request->all());

        return redirect()->route('admin.hand-packs.index');
    }

    public function edit(HandPack $handPack)
    {
        abort_if(Gate::denies('packing'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.handPacks.edit', compact('handPack'));
    }

    public function update(UpdateHandPackRequest $request, HandPack $handPack)
    {
        $handPack->update($request->all());

        return redirect()->route('admin.hand-packs.index');
    }

    public function show(HandPack $handPack)
    {
        abort_if(Gate::denies('packing'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.handPacks.show', compact('handPack'));
    }

    public function destroy(HandPack $handPack)
    {
        abort_if(Gate::denies('packing'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $handPack->delete();

        return back();
    }

    public function massDestroy(MassDestroyHandPackRequest $request)
    {
        $handPacks = HandPack::find(request('ids'));

        foreach ($handPacks as $handPack) {
            $handPack->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
