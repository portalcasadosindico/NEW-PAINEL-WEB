<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PoliticaPrivacidade;
use Exception;
use Illuminate\Http\Request;

class PoliticaPrivacidadesController extends Controller
{

    /**
     * Display a listing of the politica privacidades.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $politicaPrivacidades = PoliticaPrivacidade::orderBy('data_cadastro', 'desc')->get();

        return view('politica_privacidades.index', compact('politicaPrivacidades'));
    }

    /**
     * Show the form for creating a new politica privacidade.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {

        return view('politica_privacidades.create');
    }

    /**
     * Store a new politica privacidade in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);
            PoliticaPrivacidade::create($data);

            return redirect()->route('politica_privacidades.index')
                ->with('success_message', 'Politica Privacidade was successfully added.');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified politica privacidade.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $politicaPrivacidade = PoliticaPrivacidade::findOrFail($id);

        return view('politica_privacidades.show', compact('politicaPrivacidade'));
    }

    /**
     * Show the form for editing the specified politica privacidade.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $politicaPrivacidade = PoliticaPrivacidade::findOrFail($id);

        return view('politica_privacidades.edit', compact('politicaPrivacidade'));
    }

    /**
     * Update the specified politica privacidade in the storage.
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

            $politicaPrivacidade = PoliticaPrivacidade::findOrFail($id);
            $politicaPrivacidade->update($data);

            return redirect()->route('politica_privacidades.index')
                ->with('success_message', 'Politica Privacidade was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Remove the specified politica privacidade from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $politicaPrivacidade = PoliticaPrivacidade::findOrFail($id);
            $politicaPrivacidade->delete();

            return redirect()->route('politica_privacidades.index')
                ->with('success_message', 'Politica Privacidade was successfully deleted.');
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
