<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyItemEmbalajeRequest;
use App\Http\Requests\StoreItemEmbalajeRequest;
use App\Http\Requests\UpdateItemEmbalajeRequest;
use App\Models\ItemEmbalaje;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ItemEmbalajeController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('item_embalaje_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ItemEmbalaje::query()->select(sprintf('%s.*', (new ItemEmbalaje)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'item_embalaje_show';
                $editGate      = 'item_embalaje_edit';
                $deleteGate    = 'item_embalaje_delete';
                $crudRoutePart = 'item-embalajes';

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
            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.itemEmbalajes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('item_embalaje_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.itemEmbalajes.create');
    }

    public function store(StoreItemEmbalajeRequest $request)
    {
        $itemEmbalaje = ItemEmbalaje::create($request->all());

        return redirect()->route('admin.item-embalajes.index');
    }

    public function edit(ItemEmbalaje $itemEmbalaje)
    {
        abort_if(Gate::denies('item_embalaje_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.itemEmbalajes.edit', compact('itemEmbalaje'));
    }

    public function update(UpdateItemEmbalajeRequest $request, ItemEmbalaje $itemEmbalaje)
    {
        $itemEmbalaje->update($request->all());

        return redirect()->route('admin.item-embalajes.index');
    }

    public function show(ItemEmbalaje $itemEmbalaje)
    {
        abort_if(Gate::denies('item_embalaje_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.itemEmbalajes.show', compact('itemEmbalaje'));
    }

    public function destroy(ItemEmbalaje $itemEmbalaje)
    {
        abort_if(Gate::denies('item_embalaje_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $itemEmbalaje->delete();

        return back();
    }

    public function massDestroy(MassDestroyItemEmbalajeRequest $request)
    {
        $itemEmbalajes = ItemEmbalaje::find(request('ids'));

        foreach ($itemEmbalajes as $itemEmbalaje) {
            $itemEmbalaje->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
