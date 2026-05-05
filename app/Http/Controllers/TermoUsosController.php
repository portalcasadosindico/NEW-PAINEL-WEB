<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TermoUso;
use Exception;
use Illuminate\Http\Request;

class TermoUsosController extends Controller
{

    /**
     * Display a listing of the termo usos.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $termoUsos = TermoUso::orderBy('data_cadastro','desc')->get();

        return view('termo_usos.index', compact('termoUsos'));
    }

    /**
     * Show the form for creating a new termo uso.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {

        return view('termo_usos.create');
    }

    /**
     * Store a new termo uso in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            TermoUso::create($data);

            return redirect()->route('termo_usos.index')
                ->with('success_message', 'Termo Uso was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified termo uso.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $termoUso = TermoUso::findOrFail($id);

        return view('termo_usos.show', compact('termoUso'));
    }

    /**
     * Show the form for editing the specified termo uso.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $termoUso = TermoUso::findOrFail($id);

        return view('termo_usos.edit', compact('termoUso'));
    }

    /**
     * Update the specified termo uso in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $termoUso = TermoUso::findOrFail($id);
            $termoUso->update($data);

            return redirect()->route('termo_usos.index')
                ->with('success_message', 'Termo Uso was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Remove the specified termo uso from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $termoUso = TermoUso::findOrFail($id);
            $termoUso->delete();

            return redirect()->route('termo_usos.index')
                ->with('success_message', 'Termo Uso was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'titulo' => 'required|string|min:1|max:45',
            'texto' => 'required',
        ];

        $data = $request->validate($rules);

        return $data;
    }

}
