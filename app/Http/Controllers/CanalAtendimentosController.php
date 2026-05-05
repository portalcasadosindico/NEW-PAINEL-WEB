<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CanalAtendimento;
use Exception;
use Illuminate\Http\Request;

class CanalAtendimentosController extends Controller
{

    /**
     * Display a listing of the canal atendimentos.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $canalAtendimentos = CanalAtendimento::all();

        return view('canal_atendimentos.index', compact('canalAtendimentos'));
    }

    /**
     * Show the form for creating a new canal atendimento.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {

        return view('canal_atendimentos.create');
    }

    /**
     * Store a new canal atendimento in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            CanalAtendimento::create($data);

            return redirect()->route('canal_atendimentos.index')
                ->with('success_message', 'Canal Atendimento was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified canal atendimento.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $canalAtendimento = CanalAtendimento::findOrFail($id);

        return view('canal_atendimentos.show', compact('canalAtendimento'));
    }

    /**
     * Show the form for editing the specified canal atendimento.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $canalAtendimento = CanalAtendimento::findOrFail($id);

        return view('canal_atendimentos.edit', compact('canalAtendimento'));
    }

    /**
     * Update the specified canal atendimento in the storage.
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

            $canalAtendimento = CanalAtendimento::findOrFail($id);
            $canalAtendimento->update($data);

            return redirect()->route('canal_atendimentos.index')
                ->with('success_message', 'Canal Atendimento was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Remove the specified canal atendimento from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $canalAtendimento = CanalAtendimento::findOrFail($id);
            $canalAtendimento->delete();

            return redirect()->route('canal_atendimentos.index')
                ->with('success_message', 'Canal Atendimento was successfully deleted.');
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
            'email' => 'nullable|string|min:1|max:255',
            'telefone' => 'nullable|string|min:1|max:255',
        ];

        $data = $request->validate($rules);

        return $data;
    }

}
