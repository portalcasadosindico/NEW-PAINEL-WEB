<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PoliticaPrivacidade;
use App\Models\ResponsavelPolitica;
use Exception;
use Illuminate\Http\Request;

class ResponsavelPoliticasController extends Controller
{

    /**
     * Display a listing of the responsavel politicas.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $responsavelPoliticas = ResponsavelPolitica::with('politicaprivacidade')->paginate(25);

        return view('responsavel_politicas.index', compact('responsavelPoliticas'));
    }

    /**
     * Show the form for creating a new responsavel politica.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $PoliticaPrivacidades = PoliticaPrivacidade::pluck('titulo', 'id')->all();

        return view('responsavel_politicas.create', compact('PoliticaPrivacidades'));
    }

    /**
     * Store a new responsavel politica in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            ResponsavelPolitica::create($data);

            return redirect()->route('responsavel_politicas.index')
                ->with('success_message', 'Responsavel Politica was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified responsavel politica.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $responsavelPolitica = ResponsavelPolitica::with('politicaprivacidade')->findOrFail($id);

        return view('responsavel_politicas.show', compact('responsavelPolitica'));
    }

    /**
     * Show the form for editing the specified responsavel politica.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $responsavelPolitica = ResponsavelPolitica::findOrFail($id);
        $PoliticaPrivacidades = PoliticaPrivacidade::pluck('titulo', 'id')->all();

        return view('responsavel_politicas.edit', compact('responsavelPolitica', 'PoliticaPrivacidades'));
    }

    /**
     * Update the specified responsavel politica in the storage.
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

            $responsavelPolitica = ResponsavelPolitica::findOrFail($id);
            $responsavelPolitica->update($data);

            return redirect()->route('responsavel_politicas.index')
                ->with('success_message', 'Responsavel Politica was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Remove the specified responsavel politica from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $responsavelPolitica = ResponsavelPolitica::findOrFail($id);
            $responsavelPolitica->delete();

            return redirect()->route('responsavel_politicas.index')
                ->with('success_message', 'Responsavel Politica was successfully deleted.');
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
            'nome' => 'required|string|min:1|max:45',
            'email' => 'required|string|min:1|max:45',
            'telefone' => 'required|string|min:1|max:45',
            'cpf' => 'required|string|min:1|max:45',
            'politica_privacidade_id' => 'required',
        ];

        $data = $request->validate($rules);

        return $data;
    }

}
