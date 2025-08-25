<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BiReportController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('bi_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $biReports = auth()->user()->biReports;

        return view('admin.bi_reports.index', compact('biReports'));
    }

    public function create()
    {
        abort_if(Gate::denies('manage_bi_reports'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all();

        return view('admin.bi_reports.create', compact('users'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('manage_bi_reports'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|url',
            'users' => 'array',
        ]);

        $biReport = BiReport::create($request->only(['name', 'link', 'icon_class']));
        $biReport->users()->sync($request->input('users', []));

        return redirect()->route('admin.bi-reports.index')->with('success', 'Reporte de BI creado exitosamente.');
    }

    public function edit(BiReport $biReport)
    {
        abort_if(Gate::denies('manage_bi_reports'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all();

        return view('admin.bi_reports.edit', compact('biReport', 'users'));
    }

    public function update(Request $request, BiReport $biReport)
    {
        abort_if(Gate::denies('manage_bi_reports'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|url',
            'users' => 'array',
        ]);

        $biReport->update($request->only(['name', 'link', 'icon_class']));
        $biReport->users()->sync($request->input('users', []));

        return redirect()->route('admin.bi-reports.index')->with('success', 'Reporte de BI actualizado exitosamente.');
    }

    public function destroy(BiReport $biReport)
    {
        abort_if(Gate::denies('manage_bi_reports'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $biReport->delete();

        return redirect()->route('admin.bi-reports.index')->with('success', 'Reporte de BI eliminado exitosamente.');
    }

    public function viewExternal(BiReport $biReport)
    {
        $reportLink = $biReport->link;
        $reportTitle = $biReport->name;

        return view('admin.bi_reports.show_iframe', compact('reportLink', 'reportTitle'));
    }
}
