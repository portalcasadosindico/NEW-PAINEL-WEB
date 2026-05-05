<?php

namespace App\Exports;

use App\Models\Afiliado;
use App\Uteis\StatusOrcamento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AfiliadosExport implements FromCollection, WithHeadings, WithMapping
{
    public function headings(): array
    {
        return [
            'Razão Social',
            'CNPJ',
            'Email',
            'Regiões',
            'Status do Contrato',
            'Plano',
            'Possui assinatura',
            'Status Financeiro',
        ];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Afiliado::get();
    }
    /**
     * @var Afiliado $afiliado
     */
    public function map($afiliado): array
    {

        $razao = $afiliado->razao_social;
        $cnpj = $afiliado->cnpj;
        $email = $afiliado->usuarioApp->email;
        $regioes = "";
        $contrato =  "";
        $plano =  "";
        $hasAssinatura = "";
        $financeiro = "";
        foreach ($afiliado->regioes as $index => $afiliadoRegiao) {
            if ($index !== 0) {
                $regioes .= ", ";
                $contrato .= ", ";
                $plano .= ", ";
                $hasAssinatura .= ", ";
                $financeiro .= ", ";
            }
            $regioes .= $afiliadoRegiao->regiao->nome ?? '--';
            $contrato .=  $afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->status_afiliado ?? '--';
            $plano .=  $afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome ?? '--';
            $hasAssinatura .=  ($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano == 1 ? 'Sim' : 'Não');
            $financeiro .=  StatusOrcamento::getLabel($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano);
        }
        return [
            "Razão Social" => $razao,
            "CNPJ" => $cnpj,
            "Email" => $email,
            "Regiões" => $regioes,
            "Status do contrato" => $contrato,
            "Plano" => $plano,
            "Possui assinatura" => $hasAssinatura,
            "Status Financeiro" => $financeiro
        ];
    }
}
