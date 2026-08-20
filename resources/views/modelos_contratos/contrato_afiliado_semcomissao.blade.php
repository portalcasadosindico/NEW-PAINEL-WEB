<div style="width:100%; text-align: center;">
    <img src="<?php echo $config->logo; ?>" />
    <br>
    <h2 style="text-align: center; margin-bottom: 3px;">CONTRATO DE PRESTAÇÃO DE SERVIÇO.</h2>
    <h4 style="margin:0px;">Filiado de empresa não comissionada.</h4>
</div>
<p style="text-align:justify">
    <?php if($franqueado->id>=1){ ?>
        Contrato
        de prestação de serviços que entre si fazem a empresa <strong style="text-transform:uppercase"><?=$afiliado->razao_social?></strong>,
        sito a Rua. <strong style="text-transform:uppercase"><?=$afiliado->rua?></strong> n° <strong style="text-transform:uppercase"><?=$afiliado->numero?></strong> Bairro <strong style="text-transform:uppercase"><?=$afiliado->bairro?></strong>, cidade, <strong style="text-transform:uppercase"><?=$afiliado->cidade?></strong>./<strong style="text-transform:uppercase"><?=$afiliado->estado?></strong>, inscrito
        no <b>CNPJ sob o n°. <strong><?=formatarCNPJ($afiliado->cnpj)?></strong></b>, neste ato representado pelo Sr(a). <b><strong style="text-transform:uppercase"><?=$afiliado->responsavelAfiliado->nome?></strong>,
        </b>inscrito no <b>CPF <strong><?=formatarCPF($afiliado->responsavelAfiliado->CPF)?></strong>,</b> denominado simplesmente <b>CONTRATANTE</b>,
        e a <b><span style='color:black;  text-transform:uppercase;'><?php echo $franqueado->razao_social; ?></span></b><span
        style='color:black'>, pessoa jurídica de direito privado, inscrita no <b>CNPJ
        sob nº </b></span><strong><?php echo $franqueado->cnpj; ?></strong><span style='color:black'>, </span>neste
        ato representado pelo <b style="text-transform:uppercase;">Sr. <?php echo $franqueado->nome_responsavel; ?></b>, inscrito no <b>CPF
        <?php echo $franqueado->cpf_responsavel; ?>, </b><span style='color:black'>sediada na </span></span><span
        style='font-family:"Verdana","sans-serif";color:black;  text-transform:uppercase;'><?php echo $franqueado->rua; ?>, <?php echo $franqueado->numero; ?>. <?php echo $franqueado->bairro; ?>, <?php echo $franqueado->cidade; ?>/<?php echo $franqueado->estado; ?> doravante
        denominada simplesmente</span><span style='font-family:"Verdana","sans-serif"'>
        <b>CONTRATADA</b>, de acordo com as cláusulas e condições que segue:</span>
    <?php } else { ?>
        Contrato
        de prestação de serviços que entre si fazem a empresa <strong style="text-transform:uppercase"><?=$afiliado->razao_social?></strong>,
        sito a Rua. <strong style="text-transform:uppercase"><?=$afiliado->rua?></strong> n° <strong style="text-transform:uppercase"><?=$afiliado->numero?></strong> Bairro <strong style="text-transform:uppercase"><?=$afiliado->bairro?></strong>, cidade, <strong style="text-transform:uppercase"><?=$afiliado->cidade?></strong>./<strong style="text-transform:uppercase"><?=$afiliado->estado?></strong>, inscrito
        no <b>CNPJ sob o n°. <strong><?=formatarCNPJ($afiliado->cnpj)?></strong></b>, neste ato representado pelo Sr(a). <b><strong style="text-transform:uppercase"><?=$afiliado->responsavelAfiliado->nome?></strong>,
        </b>inscrito no <b>CPF <strong><?=formatarCPF($afiliado->responsavelAfiliado->CPF)?></strong>,</b> denominado simplesmente <b>CONTRATANTE</b>,
        e a empresa <b><span style='color:black; text-transform:uppercase;'><?php echo $franqueado->razao_social; ?></span></b>
        <span style='color:black'>, inscrita no <b>CNPJ
        sob nº </b></span><strong><?php echo $franqueado->cnpj; ?></strong><span style='color:black'>, 
            <span style='color:black'>com sede na </span></span><span
        style='font-family:"Verdana","sans-serif";color:black;  text-transform:uppercase;'><?php echo $franqueado->rua; ?>, <?php echo $franqueado->numero; ?>. <?php echo $franqueado->bairro; ?>, <?php echo $franqueado->cidade; ?>/<?php echo $franqueado->estado; ?>
        </span>neste
        ato representado pelo seu representante <b>Sr(a). <strong style="text-transform:uppercase"><?php echo $franqueado->nome_responsavel; ?></strong></b>, inscrito no <b>CPF
        <?php echo $franqueado->cpf_responsavel; ?>, </b>

        doravante
        denominada simplesmente</span><span style='font-family:"Verdana","sans-serif"'>
        <b>CONTRATADA</b>,
        este instrumento de contrato preconiza que a <b>CONTRATADA</b> é franqueada da <b>CASA DO SÍNDICO</b>, 
        pessoa jurídica de direito privado, inscrita no CNPJ sob <b>nº 22.912.131/0001-88</b>, neste ato representado pelo Sr. <b>GILSON RIDHER RATIER QUEIROZ</b>, inscrito no CPF <b>465.759.011-15</b>, sediada na <b>Rua Anita Garibaldi, nº 77, sala 801. 88.010-500</b> doravante denominada simplesmente <b>CONTRATADA</b>, de acordo com as cláusulas e condições que segue:</span>
    <?php } ?>

</p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>&nbsp;</span></p>

<p class=MsoNormal style='line-height:150%'><b><span style='font-family:"Verdana","sans-serif"'>Cláusula
primeira – DO OBJETO.</span></b></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>A
<b>CONTRATADA </b>tem por força de contrato o objetivo de:</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>1º
- Divulgar a empresa da <b>CONTRATANTE</b> em site específico da <b>CONTRATRADA</b>,
voltados para os mais variados tipos de serviços em condomínios seja comercial,
residencial e/ou shoppings.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>2º
- Acompanhar os serviços ao qual a se fez anuente no contrato de prestação de
serviço entre a contratante e contratado, com objetivo de garantir ao Cliente
final a entrega das obras ou serviços, de acordo com o contrato firmado entre
as partes.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>3º
- Auxiliar na fiscalização, para melhoria da qualidade dos serviços, assim como
a segurança de funcionários e terceiros.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>4º
- Aplicar cursos, palestras e reuniões periódicas para melhorar o funcionamento
do grupo, aplicar ajustes de mudança de mercado, apresentação da evolução e
andamento dos negócios aplicados.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>5º
- Buscar parceiros no mercado para redução de custos em aquisições de materiais
e serviços especializados nos mais diversos seguimentos.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>6º
- Elaborar contrato de prestação de serviço entre o contratante e o e o
contratado.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>7º
- Administrar o plano de fidelidade, para aumentar fluxo de
vendas da <b>CONTRATANTE, (CONDS).</b></span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'> 8º
- Disponibilizar carteira de possíveis funcionários, para futuras contratações,
estes habilitados nas mais variadas áreas de prestação de serviços.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>9º
- Disponibilizar serviços jurídicos e técnicos, nas mais variadas áreas com descontos
diferenciados do mercado.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>10º
- Participar de reuniões condominiais a pedido da <b>CONTRATADA</b> para
apresentação da empresa e dar o aval de empresa idônea.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>11º
- Fornecer periodicamente a <b>CONTRATADA,</b> relatórios e tabelas da mais
variadas como controle de orçamentos, gráfico de aprovação de obras por
condominio, relatório de orçamentos recebidos através do site, whats app ou aplicativos,
relatório de recebimentos do plano de Fidelidade <b>(CONDS)</b>, modelos de
planilhas para orçamentos, modelos de plano de obra, modelo de checklist para
medição predial, modelo de aviso de inicio de obras ou serviços, relatório de
fechamento individual das despesas mensais da <b>CONTRATADA</b>.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>12º
- Assessoria para verificação de orçamento através de simulados.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>13º
- Fornecer requerimento para pedido de elaboração de contratos ao cliente
final.</span></p>

<p class=MsoNormal><span style='font-family:"Verdana","sans-serif"'>&nbsp;</span></p>

<p class=MsoNormal style='line-height:150%'><b><span style='font-family:"Verdana","sans-serif"'>Cláusula
segunda – DO PRAZO</span></b></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>1º
- O presente contrato inicia-se em <?=formatarDataExtenso($planoAssinatura->data_contrato)?>, tendo como <b>vigência o
prazo de três meses</b>, após esse período o contrato será por prazo
indeterminado, podendo ser rescindido a qualquer momento por qualquer das
partes, sem motivo procedente, com aviso prévio de 30 dias sem que haja multas
para ambos os lados desde que não houve prejuízo <span style='background:white'>predecessores</span>.</span></p>

<p class=MsoNormal style='line-height:150%'><b><i><u><span
style='font-family:"Verdana","sans-serif"'>Parágrafo primeiro;</span></u></i></b><b><i><span
style='font-family:"Verdana","sans-serif"'> </span></i></b><span
style='font-family:"Verdana","sans-serif"'>na hipótese do <b>CONTRATANTE</b>,
solicitar o desligamento da filiação com obras e serviços em andamento, esse
será retomado pela <b>CONTRATADA</b> e repassado para outro filiado devidamente
registrado.</span></p>

<p class=MsoNormal style='line-height:150%'><b><i><u><span style='font-family:
"Verdana","sans-serif"'>Parágrafo segundo</span></u></i></b><b><i><span
style='font-family:"Verdana","sans-serif"'>; </span></i></b><span
style='font-family:"Verdana","sans-serif"'>As partes, tem por obrigação
respeitar o prazo de vigência do contrato, na quebra desta clausula, a parte
que pedir o desligamento ou abandono das funções, terá que indenizar a outra
parte com as prestações faltantes, mais uma multa de 30% do valor total do
contrato, e poderá ser cobradas judicialmente somadas a inclusão da parte
infratora nos órgãos de defesa do consumidor, (SERASA, SCP).</span></p>

<p class=MsoNormal><b><span style='font-family:"Verdana","sans-serif"'>&nbsp;</span></b></p>

<p class=MsoNormal style='line-height:150%'><b><span style='font-family:"Verdana","sans-serif"'>Cláusula
terceira – DO VALOR E FORMA DE PAGAMENTO.</span></b></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>1º
- O valor contratual será de R$ <?php echo number_format($planoAssinatura->valor, 2, ",", "."); ?> (<?php echo GExtenso::moeda( number_format($planoAssinatura->valor, 2, "", "") ); ?>) mensais, com desconto de <?php echo $planoAssinatura->desconto; ?>% com o pagamento até a data do
vencimento, sem qualquer comissão referente a serviços prestados.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>2º
- A <b>CONTRATANTE</b> pagará mensalmente, através de boletos bancários no
valor acima estipulado com vencimento todo o dia 12 de cada mês subsequente aos
serviços prestados, e enquanto da vigência do contrato, caso a data de
pagamento incidir com finais de semana ou feriado, o prazo se estenderá até o
próximo dia útil.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>3º
- <span style='color:black'>No caso de atraso do pagamento será cobrado 2% de
juros e 0,33% de multa ao dia, após cinco dias de atraso, será retirado o link
da </span><b>CONTRATANTE</b><span style='color:red'> </span>do site e também do
APP que deixará de receber as solicitações de orçamentos, persistindo o atraso após
30 dias de inadimplência a <b>CONTRATANTE </b>será incluída no cadastro de
proteção ao credito até que sejam regularizados os pagamentos ao setor financeiro
da <b>CONTRATADA</b>.</span></p>

<p class=MsoNormal style='line-height:150%'><b><i><u><span style='font-family:
"Verdana","sans-serif"'>Parágrafo primeiro;</span></u></i></b><span
style='font-family:"Verdana","sans-serif"'> Caso a <b>CONTRATANTE</b>, fique
bloqueada dos recebimentos dos orçamentos no período da vigência obrigatória,
não lhe da o direito de descontos ou mesmo do cancelamento das faturas dos
meses seguintes, até que se finde o prazo de vigência obrigatório.</span></p>

<p class=MsoNormal style='line-height:150%'><b><i><u><span style='font-family:
"Verdana","sans-serif"'>Parágrafo segundo;</span></u></i></b><b><i><span
style='font-family:"Verdana","sans-serif"'> </span></i></b><span
style='font-family:"Verdana","sans-serif"'>a</span><span style='font-family:
"Verdana","sans-serif"'>s porcentagens referentes ao plano de fidelidade (<b>CONDS</b>)
serão cobradas no vigésimo quinto dia do mês subsequente da prestação de
serviço, de forma integral ou parcelado, dependendo da forma de pagamento ao
qual estipulado no contrato com o cliente final, o fechamento para apuração
desses valores se dá todo dia quinze de cada mês.</span></p>

<p class=MsoNormal style='line-height:150%'><b><i><u><span style='font-family:
"Verdana","sans-serif"'>Parágrafo terceiro;</span></u></i></b><span
style='font-family:"Verdana","sans-serif"'> fica a cargo da <b>CONTRATANTE</b>,
o repasse de 2% do valor correspondente a mão de obra dos serviços prestados através
dos contratos assinados para o fundo do plano de fidelidade (<b>CONDS</b>).
Essa porcentagem não se refere a comissão, e sim programa de captação e
fidelização de clientes, e não pode ser devolvida a <b>CONTRATANTE</b>, pois
uma vez paga pertence ao cliente final.</span></p>

<p class=MsoNormal style='line-height:150%'><b><i><u><span style='font-family:
"Verdana","sans-serif"'>Parágrafo quarto;</span></u></i></b><b><i><span
style='font-family:"Verdana","sans-serif"'> </span></i></b><span
style='font-family:"Verdana","sans-serif"'>na hipótese de inadimplência da <b>CONTRATANTE,
</b>das mensalidades ou plano de fidelidade não poderá iniciar qualquer obra ou
serviço até que seja regularizada a situação perante o setor financeiro da <b>CONTRATADA</b>.</span></p>

<p class=MsoNormal style='line-height:150%'><b><i><u><span style='font-family:
"Verdana","sans-serif"'>Parágrafo quinto;</span></u></i></b><b><i><span
style='font-family:"Verdana","sans-serif"'> </span></i></b><span
style='font-family:"Verdana","sans-serif"'>Na hipótese de inadimplência da <b>CONTRATANTE</b>,
das mensalidades ou plano de fidelidade, e que tiver obras e serviços em andamento,
a <b>CONTRATADA</b> se reservará o direito de receber o valor devido
diretamente do cliente final, até que seja regularizada a situação perante o
setor financeiro da <b>CONTRATADA</b> ou que se findem os serviços ora
contratados.</span></p>

<p class=MsoNormal style='line-height:150%'><b><i><u><span style='font-family:
"Verdana","sans-serif"'>Parágrafo sexto; </span></u></i></b><span
style='font-family:"Verdana","sans-serif"'>os pagamentos das mensalidades,
comissões ou CONDS, deverão ser pagos diretamente á </span><b><span
style='font-family:"Verdana","sans-serif"'>CONTRATADA</span></b><span
style='font-family:"Verdana","sans-serif"'>, ou a sua ordem. </span></p>

<p class=MsoNormal><span style='font-family:"Verdana","sans-serif"'>&nbsp;</span></p>

<p class=MsoNormal style='line-height:150%'><b><span style='font-family:"Verdana","sans-serif"'>Cláusula
quarta - DO REAJUSTE</span></b></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>1º
- Os reajustes financeiros serão atualizados todo dia 01 de janeiro de cada
ano, caso a <b>CONTRATADA</b> achar necessário, respeitando os índices do salário
minimino decretado pelo governo federal, e podendo ter outros reajustes
conforme implantação de novas tecnologias ou investimentos que agregue
benefícios ao <b>CONTRATANTE</b>.</span></p>

<p class=MsoNormal style='line-height:150%'><b><i><u><span style='font-family:
"Verdana","sans-serif"'>Parágrafo único;</span></u></i></b><span
style='font-family:"Verdana","sans-serif"'> os reajustes acima mencionados não
poderão ser aplicados às empresas que tiverem no prazo de vigência obrigatória
de três meses.</span></p>

<p class=MsoNormal><b><span style='font-family:"Verdana","sans-serif"'>&nbsp;</span></b></p>

<p class=MsoNormal style='line-height:150%'><b><span style='font-family:"Verdana","sans-serif"'>Cláusula
quinta – DAS OBRIGAÇÕES DA CONTRATANTE</span></b></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>A
<b>CONTRATANTE</b> ao prestar serviços para os clientes com a anuência da <b>CONTRATADA</b>
obriga-se a:</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>1º
- Cumprir com as normas de pagamentos descritos na clausula terceira, e todos
seus parágrafos.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>2º
- Observar que os profissionais compareçam ao local de trabalho devidamente
uniformizados, equipados e munidos de identidade funcional.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>3º.
- Dotar seus empregados da devida proteção referente às medidas de segurança,
como uso de EPI´s e NRs de acordo com o serviço prestado e higiene do trabalho.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>4º.
– Fazer plano de seguro de vida a todos os funcionários, cuja função ofereça
risco.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>5º.–
Enviar pelo menos 1/3 dos funcionários para os cursos e/ou palestras profissionalizantes
ministrados pela <b>CONTRATADA </b>a cada evento.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>6º.
- Pagar todos os encargos decorrentes na prestação dos serviços no período
vigente do contrato ao qual a <b>CONTRATADA, </b>se fez anuente, ou seja;
fiscais, trabalhistas, previdenciários de acidente de trabalho, licenças ou
indenização de qualquer natureza devida aos próprios empregados ou terceiros.
Enviar copia da documentação e comprovantes para <b>CONTRATADA</b> mensalmente.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>7º
- Cumprir rigorosamente prazos de entrega dos serviços, o não cumprimento de
prazos devido a culpa da <b>CONTRATANTE, </b>será passível de advertência por
escrito, podendo sofrer sanções descritas em cláusulas do contrato de prestação
de serviço ao cliente final.   </span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>8º
- A <b>CONTRATANTE </b>deverá apresentar um cronograma simples de prestação de
serviço para todas as modalidades, com detalhes de pessoal, prazo, materiais
aplicados e serviços a serem executados.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>9º
- A <b>CONTRATANTE, </b>somente poderá iniciar os serviços após a elaboração do
contrato com o cliente final, assinado por todas as partes e com a anuência da<b>
CONTRATADA</b>.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>10º
- A <b>CONTRATANTE </b>deve ser a empresa da atividade fim, e deve constar em
seu CNAE, as atividades que se propõe a fazer, fica expressamente proibida a
terceirização dos serviços, salvo por complemento de atividades especificas.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>11º
- A <b>CONTRATANTE</b> somente poderá utilizar funcionários devidamente
registrados na forma da lei, e se responsabiliza pelo recolhimento, nos
respectivos vencimentos, de todos os tributos, ai compreendidos os impostos,
taxas e contribuições, devido a União, ao Estado e ao Município, que incidam ou
venha a incidir sobre sua atividade, desde que não haja retenções nas notas
fiscais.  Outrossim, a mesma assume total e exclusiva responsabilidade pelo
pagamento dos salários e consectários de todas as pessoas, por ela contratada,
e que prestarem serviços à clientela, inclusive emissão de notas fiscais, uniforme
e demais despesas, e danos que decorram de acidente do trabalho, obrigando-se a
pagar também todos os encargos sociais e observar a legislação laboral, as
convenções, acordos e dissídios trabalhistas.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>12º
- Para todos os efeitos de direito, a <b>CONTRATANTE</b> é, e será a única
empregadora de todos os obreiros que, por ela contatada, vier a trabalhar em
favos de clientes com aval da <b>CONTRATADA</b>, como decorrência do presente
ajuste, respondendo assim por todas as demandas judiciais proposta contra a
primeira, e por eventuais danos financeiros que estas lhe venham a acarretar. </span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>13º
- A <b>CONTRATANTE</b> assumira toda a responsabilidade ônus e encargos
oriundos de sua relação com subcontratados, e a responsabilidade civil perante
terceiros por danos decorrentes de ação ou omissão de empregados, serviçais ou
prepostos.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>14º
- Na hipótese de alguma responsabilidade, em casos tais, vir a ser imputada a <b>CONTRATADA</b>,
à <b>CONTRATANTE</b> assumirá a obrigação tão logo tenha conhecimentos do fato
liberando a primeira. </span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>15º
- A <b>CONTRATANTE</b>, se obrigará a apresentar os comprovantes de pagamentos de
encargos e funcionários locados, referente ao mês anterior de trabalhado, até
que se findem as obras. Somente após a apresentação da documentação será liberado
o pagamento da medição ou parcela.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>16º
- A <b>CONTRATANTE, </b>se responsabilizará pela elaboração de orçamento e
fechamento da venda, assim como os recebimentos que será feito diretamente do
cliente final, através da anuência da <b>CONTRATADA.</b></span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>17º
- Autorizar a <b>CONTRATADA, </b>a qualquer momento a visitar nos locais dos
serviços prestados, para averiguação dos serviços em andamento.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>18º
- A</span><span style='font-family:"Verdana","sans-serif"'>utoriza a <b>CONTRATADA</b>
o direito de imagem</span><span style='font-family:"Verdana","sans-serif"'>.</span></p>

<p class=MsoNormal align=left style='text-align:left;line-height:150%'><span
style='font-family:"Verdana","sans-serif"'>19º - Manter dados cadastrais sempre
atualizados perante a <b>CONTRATADA, </b>como endereço, telefones, e-mails ou
qualquer alteração no contrato social.</span></p>

<p class=MsoNormal align=left style='text-align:left;line-height:150%'><span
style='font-family:"Verdana","sans-serif"'>20º - </span><span style='font-family:
"Verdana","sans-serif"'>Honrar todo contrato de prestação com o cliente final
cumprindo rigorosamente todas as clausulas contratuais.</span></p>

<p class=MsoNormal align=left style='text-align:left;line-height:150%'><span
style='font-family:"Verdana","sans-serif"'>21º - Fornecer requerimento
devidamente preenchido para elaboração de contrato com o cliente final.</span></p>

<p class=MsoNormal align=left style='text-align:left;line-height:150%'><b><i><u><span
style='font-family:"Verdana","sans-serif"'>Parágrafo primeiro</span></u></i></b><b><span
style='font-family:"Verdana","sans-serif"'>; </span></b><span style='font-family:
"Verdana","sans-serif"'>fi</span><span style='font-family:"Verdana","sans-serif"'>ca
a <b>CONTRATANTE</b>, ciente que a posse dos serviços pertence a <b>CONTRATADA,
</b>que também se faz anuente no contrato firmado com o cliente final, e terá
pleno direito de desagregar a  <b>CONTRATANTE</b>, das funções ora contratadas,
assim como bloqueio dos pagamentos restantes, assumindo a retomadas das obras e
serviços, absorvendo assim os pagamentos restantes que fazem parte da garantia
do cliente final. Esse caso em específico só pode ser aplicado caso a <b>CONTRATANTE</b>,
tenha infringido as normas explicitas neste instrumento de contrato, e ter sido
notificada pela <b>CONTRATADA</b> expressamente por três vezes e tendo prazo
razoável para sanar a deficiência. </span></p>

<p class=MsoNormal style='line-height:150%'><b><i><u><span style='font-family:
"Verdana","sans-serif"'>Parágrafo segundo</span></u></i></b><b><span
style='font-family:"Verdana","sans-serif"'>; </span></b><span style='font-family:
"Verdana","sans-serif"'>A<b> CONTRATANTE </b>obriga se a quitar dos os valores,
referente a despesas oriundas da prestação de serviço a qual a <b>CONTRATADA</b>
se faze anuente, com pena de ser cobrado judicialmente.</span></p>

<p class=MsoNormal><b><span style='font-family:"Verdana","sans-serif"'>&nbsp;</span></b></p>

<p class=MsoNormal style='line-height:150%'><b><span style='font-family:"Verdana","sans-serif"'>Cláusula
sexta – DAS OBRIGAÇÕES DA CONTRATADA.</span></b></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>1º
- Manter disponível á <b>CONTRATANTE </b>acesso para comunicação em horário
comercial, através de e-mail ou telefones. </span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>2º
- Notificar por escrito a <b>CONTRATANTE</b> de deficiências e irregularidades
encontradas na execução dos serviços, fixando prazo para a sua correção.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>3º
- ministrar cursos e/ou palestras profissionalizantes aos funcionários da <b>CONTRATANTE</b>,
com valores reduzidos.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>4º
- Disponibilizar acesso no site da <b>CONTRATADA</b>, para pesquisa.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>5º
- Manter sigilo referente orçamentos e qualquer outro tipo de documentos.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>6º
- Indicar a <b>CONTRATANTE</b>, através de lista os parceiros que promovem
descontos especiais.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>7º
- Indicar a <b>CONTRATANTE</b>, os convênios, médicos, odontológicos, oficinas
mecânicas, escolas de NRs, farmácias, seguradoras tudo com descontos especiais
para a <b>CONTRATANTE </b>através de convênios.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>8º
- Orientar a <b>CONTRATANTE</b>, quanto a liberação de documentos necessários
para o cumprimento de obras e serviços.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>9º
- Divulgar a programação de cursos e eventos para que a <b>CONTRATANTE</b>
possa se matricular.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>10º
- Manter a orientação da <b>CONTRATANTE</b>, através de profissionais das áreas
de direito, engenharia civil, contabilidade, economista, administrador e
segurança do trabalho.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>11º
- Manter nas dependências da <b>CONTRATADA, </b>arquivo de<b> </b>histórico de
serviço prestado no período de 24 meses.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>12º
- Fornecer carta de referência da <b>CONTRATANTE</b> ao cliente final.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>13º
- Manter igualdade entre as empresas não praticando favorecimento qualquer pra
uma ou pra outra.</span></p>

<p class=MsoNormal><span style='font-family:"Verdana","sans-serif"'> </span></p>

<p class=MsoNormal style='line-height:150%'><b><span style='font-family:"Verdana","sans-serif"'>Clausula
sétima - DA RESCISÃO DE CONTRATO</span></b></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>1º
- A rescisão de contrato pode ser dada por ambas as partes, com carta de aviso
prévio de trinta dias, após a vigência do contrato.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>2º
- Não haverá custos para ambas as partes na rescisão de contrato, salvo haja
motivos de inadimplência ou quebra de contratos com cliente final por falta de
cumprimento das clausulas daquele contrato.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>3º
- Para a concretização da rescisão desse instrumento de contrato, é necessário
que a <b>CONTRATANTE, </b>esteja rigorosamente em dia com a entrega das
documentações exigidas em suas obrigações, e que não esteja com qualquer obra
ou serviço em andamento com a anuência da <b>CONTRATADA</b>.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>4º
A <b>CONTRATANTE</b>, obriga-se a dar garantia das obras por ela executadas,
nos moldes antes descritos nos contratos cuja consta a anuência da <b>CONTRATADA</b>,
mesmo que já tenha se desfiliado da CASA DO SINDICO, sob pena de ação judicial
para reparo de danos a <b>CONTRATADA </b>ou mesmo ao cliente final, ou seja, o
condomínio.</span></p>

<p class=MsoNormal><span style='font-family:"Verdana","sans-serif"'>&nbsp;</span></p>

<p class=MsoNormal style='line-height:150%'><b><span style='font-family:"Verdana","sans-serif"'>Cláusula
oitava – DAS DISPOSIÇÕES GERAIS</span></b></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>1º
- É vedado a <b>CONTRATANTE</b> delegar ou transferir a terceiros no todo ou em
parte, os serviços objeto do Contrato, salvo por consentimento expresso da <b>CONTRATADA</b>,
após e amplamente justificado.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>2º
- Fica acordado entre as partes que, se porventura a <b>CONTRATANTE</b> não
conseguir cumprir o contrato onde a <b>CONTRATADA</b> se faz anuente, esta
segunda assumira os direitos e deveres do contrato anuído.  </span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>3º
- A <b>CONTRATADA, </b>tem por obrigação, cobrar judicialmente a <b>CONTRATANTE</b>,
o pleno cumprimento do contrato de prestação de serviço ao cliente final, cuja
foi anuente.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>4º
- Fica eleito o <b>FORO DA COMARCA DE FLORIANÓPOLIS/SC</b>, como o competente
para dirimir as questões decorrentes da execução deste contrato, renunciando
outro mais privilegiado que sejam como também os casos omissos e não regulado
pelo presente instrumento.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>&nbsp;</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>E,
por estarem justos e contratados assinam o presente instrumento juntamente com as
testemunhas abaixo assinadas.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana","sans-serif"'>&nbsp;</span></p>

<br><br>
<?php echo $franqueado->cidade; ?>, <?=formatarDataExtenso($planoAssinatura->data_contrato)?>.
<br><br><br>

_____________________________________________________
<br><strong>CONTRATANTE</strong>
<br><br><br><br>
_____________________________________________________
<br><strong>CONTRATADA</strong>

<br><br><br><br>
_____________________________________________________
<br><strong>TESTEMUNHA 1 (<?php echo $email_testemunha1; ?>)</strong>
<br><br><br><br>
_____________________________________________________
<br><strong>TESTEMUNHA 2 (<?php echo $email_testemunha2; ?>)</strong> 


<?php
class GExtenso {

    const NUM_SING = 0;
    const NUM_PLURAL = 1;
    const POS_GENERO = 2;
    const GENERO_MASC = 0;
    const GENERO_FEM = 1;

    const VALOR_MAXIMO = 999999999;

    /* Uma vez que o PHP não suporta constantes de classe na forma de matriz (array),
    a saída encontrada foi declarar as strings numéricas como 'private static'.
    */

    /* As unidades 1 e 2 variam em gênero, pelo que precisamos de dois conjuntos de strings (masculinas e femininas) para as unidades */
    private static $UNIDADES = array(
    self::GENERO_MASC => array(
        1 => 'um',
        2 => 'dois',
        3 => 'três',
        4 => 'quatro',
        5 => 'cinco',
        6 => 'seis',
        7 => 'sete',
        8 => 'oito',
        9 => 'nove'
    ),
    self::GENERO_FEM => array(
        1 => 'uma',
        2 => 'duas',
        3 => 'três',
        4 => 'quatro',
        5 => 'cinco',
        6 => 'seis',
        7 => 'sete',
        8 => 'oito',
        9 => 'nove'
    )
    );

    private static $DE11A19 = array(
    11 => 'onze',
    12 => 'doze',
    13 => 'treze',
    14 => 'quatorze',
    15 => 'quinze',
    16 => 'dezesseis',
    17 => 'dezessete',
    18 => 'dezoito',
    19 => 'dezenove'
    );

    private static $DEZENAS = array(
    10 => 'dez',
    20 => 'vinte',
    30 => 'trinta',
    40 => 'quarenta',
    50 => 'cinquenta',
    60 => 'sessenta',
    70 => 'setenta',
    80 => 'oitenta',
    90 => 'noventa'
    );

    private static $CENTENA_EXATA = 'cem';

    /* As centenas, com exceção de 'cento', também variam em gênero. Aqui também se faz
    necessário dois conjuntos de strings (masculinas e femininas).
    */

    private static $CENTENAS = array(
    self::GENERO_MASC => array(
        100 => 'cento',
        200 => 'duzentos',
        300 => 'trezentos',
        400 => 'quatrocentos',
        500 => 'quinhentos',
        600 => 'seiscentos',
        700 => 'setecentos',
        800 => 'oitocentos',
        900 => 'novecentos'
    ),
    self::GENERO_FEM => array(
        100 => 'cento',
        200 => 'duzentas',
        300 => 'trezentas',
        400 => 'quatrocentas',
        500 => 'quinhentas',
        600 => 'seiscentas',
        700 => 'setecentas',
        800 => 'oitocentas',
        900 => 'novecentas'
    )
    );

    /* 'Mil' é invariável, seja em gênero, seja em número */
    private static $MILHAR = 'mil';

    private static $MILHOES = array(
    self::NUM_SING => 'milhão',
    self::NUM_PLURAL => 'milhões'
    );



    /**
    * Gera a representação por extenso de um número inteiro, maior que zero e menor ou igual a GExtenso::VALOR_MAXIMO.
    *
    * @param int O valor numérico cujo extenso se deseja gerar
    *
    * @param int (Opcional; valor padrão: GExtenso::GENERO_MASC) O gênero gramatical (GExtenso::GENERO_MASC ou GExtenso::GENERO_FEM)
    * do extenso a ser gerado. Isso possibilita distinguir, por exemplo, entre 'duzentos e dois homens' e 'duzentas e duas mulheres'.
    *
    * @return string O número por extenso
    *
    * @since 0.1 2010-03-02
    */
    public static function numero($valor, $genero = self::GENERO_MASC) {

    /* ----- VALIDAÇÃO DOS PARÂMETROS DE ENTRADA ---- */

    if(!is_numeric($valor))
        throw new Exception("[Exceção em GExtenso::numero] Parâmetro \$valor não é numérico (recebido: '$valor')");

    else if($valor <= 0)
        throw new Exception("[Exceção em GExtenso::numero] Parâmetro \$valor igual a ou menor que zero (recebido: '$valor')");

    else if($valor > self::VALOR_MAXIMO)
        throw new Exception('[Exceção em GExtenso::numero] Parâmetro $valor deve ser um inteiro entre 1 e ' . self::VALOR_MAXIMO . " (recebido: '$valor')");

    else if($genero != self::GENERO_MASC && $genero != self::GENERO_FEM)
        throw new Exception("Exceção em GExtenso: valor incorreto para o parâmetro \$genero (recebido: '$genero').");

    /* ----------------------------------------------- */

    else if($valor >= 1 && $valor <= 9)
        return self::$UNIDADES[$genero][$valor]; // As unidades 'um' e 'dois' variam segundo o gênero

    else if($valor == 10)
        return self::$DEZENAS[$valor];

    else if($valor >= 11 && $valor <= 19)
        return self::$DE11A19[$valor];

    else if($valor >= 20 && $valor <= 99) {
        $dezena = $valor - ($valor % 10);
        $ret = self::$DEZENAS[$dezena];
        /* Chamada recursiva à função para processar $resto se este for maior que zero.
        * O conectivo 'e' é utilizado entre dezenas e unidades.
        */
        if($resto = $valor - $dezena) $ret .= ' e ' . self::numero($resto, $genero);
        return $ret;
    }

    else if($valor == 100) {
        return self::$CENTENA_EXATA;
    }

    else if($valor >= 101 && $valor <= 999) {
        $centena = $valor - ($valor % 100);
        $ret = self::$CENTENAS[$genero][$centena]; // As centenas (exceto 'cento') variam em gênero
        /* Chamada recursiva à função para processar $resto se este for maior que zero.
        * O conectivo 'e' é utilizado entre centenas e dezenas.
        */
        if($resto = $valor - $centena) $ret .= ' e ' . self::numero($resto, $genero);
        return $ret;
    }

    else if($valor >= 1000 && $valor <= 999999) {
        /* A função 'floor' é utilizada para encontrar o inteiro da divisão de $valor por 1000,
        * assim determinando a quantidade de milhares. O resultado é enviado a uma chamada recursiva
        * da função. A palavra 'mil' não se flexiona.
        */
        $milhar = floor($valor / 1000);
        $ret = self::numero($milhar, self::GENERO_MASC) . ' ' . self::$MILHAR; // 'Mil' é do gênero masculino
        $resto = $valor % 1000;
        /* Chamada recursiva à função para processar $resto se este for maior que zero.
        * O conectivo 'e' é utilizado entre milhares e números entre 1 e 99, bem como antes de centenas exatas.
        */
        if($resto && (($resto >= 1 && $resto <= 99) || $resto % 100 == 0))
        $ret .= ' e ' . self::numero($resto, $genero);
        /* Nos demais casos, após o milhar é utilizada a vírgula. */
        else if ($resto)
        $ret .= ', ' . self::numero($resto, $genero);
        return $ret;
    }

    else if($valor >= 100000 && $valor <= self::VALOR_MAXIMO) {
        /* A função 'floor' é utilizada para encontrar o inteiro da divisão de $valor por 1000000,
        * assim determinando a quantidade de milhões. O resultado é enviado a uma chamada recursiva
        * da função. A palavra 'milhão' flexiona-se no plural.
        */
        $milhoes = floor($valor / 1000000);
        $ret = self::numero($milhoes, self::GENERO_MASC) . ' '; // Milhão e milhões são do gênero masculino
        
        /* Se a o número de milhões for maior que 1, deve-se utilizar a forma flexionada no plural */
        $ret .= $milhoes == 1 ? self::$MILHOES[self::NUM_SING] : self::$MILHOES[self::NUM_PLURAL];

        $resto = $valor % 1000000;

        /* Chamada recursiva à função para processar $resto se este for maior que zero.
        * O conectivo 'e' é utilizado entre milhões e números entre 1 e 99, bem como antes de centenas exatas.
        */
        if($resto && (($resto >= 1 && $resto <= 99) || $resto % 100 == 0))
        $ret .= ' e ' . self::numero($resto, $genero);
        /* Nos demais casos, após o milhão é utilizada a vírgula. */
        else if ($resto)
        $ret .= ', ' . self::numero($resto, $genero);
        return $ret;
    }

    }

    /**
    * Gera a representação por extenso de um valor monetário, maior que zero e menor ou igual a GExtenso::VALOR_MAXIMO.
    *
    * @param int O valor monetário cujo extenso se deseja gerar.
    * ATENÇÃO: PARA EVITAR OS CONHECIDOS PROBLEMAS DE ARREDONDAMENTO COM NÚMEROS DE PONTO FLUTUANTE, O VALOR DEVE SER PASSADO
    * JÁ DEVIDAMENTE MULTIPLICADO POR 10 ELEVADO A $casasDecimais (o que equivale, normalmente, a passar o valor com centavos
    * multiplicado por 100)
    *
    * @param int (Opcional; valor padrão: 2) Número de casas decimais a serem consideradas como parte fracionária (centavos)
    *
    * @param array (Opcional; valor padrão: array('real', 'reais', GExtenso::GENERO_MASC)) Fornece informações sobre a moeda a ser
    * utilizada. O primeiro valor da matriz corresponde ao nome da moeda no singular, o segundo ao nome da moeda no plural e o terceiro
    * ao gênero gramatical do nome da moeda (GExtenso::GENERO_MASC ou GExtenso::GENERO_FEM)
    *
    * @param array (Opcional; valor padrão: array('centavo', 'centavos', self::GENERO_MASC)) Provê informações sobre a parte fracionária
    * da moeda. O primeiro valor da matriz corresponde ao nome da parte fracionária no singular, o segundo ao nome da parte fracionária no plural
    * e o terceiro ao gênero gramatical da parte fracionária (GExtenso::GENERO_MASC ou GExtenso::GENERO_FEM)
    *
    * @return string O valor monetário por extenso
    *
    * @since 0.1 2010-03-02
    */
    public static function moeda(
    $valor,
    $casasDecimais = 2,
    $infoUnidade = array('real', 'reais', self::GENERO_MASC),
    $infoFracao = array('centavo', 'centavos', self::GENERO_MASC)
    ) {

    /* ----- VALIDAÇÃO DOS PARÂMETROS DE ENTRADA ---- */

    if(!is_numeric($valor))
        throw new Exception("[Exceção em GExtenso::moeda] Parâmetro \$valor não é numérico (recebido: '$valor')");

    else if($valor <= 0)
        throw new Exception("[Exceção em GExtenso::moeda] Parâmetro \$valor igual a ou menor que zero (recebido: '$valor')");

    else if(!is_numeric($casasDecimais) || $casasDecimais < 0)
        throw new Exception("[Exceção em GExtenso::moeda] Parâmetro \$casasDecimais não é numérico ou é menor que zero (recebido: '$casasDecimais')");

    else if(!is_array($infoUnidade) || count($infoUnidade) < 3) {
        $infoUnidade = print_r($infoUnidade, true);
        throw new Exception("[Exceção em GExtenso::moeda] Parâmetro \$infoUnidade não é uma matriz com 3 (três) elementos (recebido: '$infoUnidade')");
    }

    else if($infoUnidade[self::POS_GENERO] != self::GENERO_MASC && $infoUnidade[self::POS_GENERO] != self::GENERO_FEM)
        throw new Exception("Exceção em GExtenso: valor incorreto para o parâmetro \$infoUnidade[self::POS_GENERO] (recebido: '{$infoUnidade[self::POS_GENERO]}').");

    else if(!is_array($infoFracao) || count($infoFracao) < 3) {
        $infoFracao = print_r($infoFracao, true);
        throw new Exception("[Exceção em GExtenso::moeda] Parâmetro \$infoFracao não é uma matriz com 3 (três) elementos (recebido: '$infoFracao')");
    }

    else if($infoFracao[self::POS_GENERO] != self::GENERO_MASC && $infoFracao[self::POS_GENERO] != self::GENERO_FEM)
        throw new Exception("Exceção em GExtenso: valor incorreto para o parâmetro \$infoFracao[self::POS_GENERO] (recebido: '{$infoFracao[self::POS_GENERO]}').");

    /* ----------------------------------------------- */

    /* A parte inteira do valor monetário corresponde ao $valor passado dividido por 10 elevado a $casasDecimais, desprezado o resto.
    * Assim, com o padrão de 2 $casasDecimais, o $valor será dividido por 100 (10^2), e o resto é descartado utilizando-se floor().
    */
    $parteInteira = floor($valor / pow(10, $casasDecimais));

    /* A parte fracionária ('centavos'), por seu turno, corresponderá ao resto da divisão do $valor por 10 elevado a $casasDecimais.
    * No cenário comum em que trabalhamos com 2 $casasDecimais, será o resto da divisão do $valor por 100 (10^2).
    */
    $fracao = $valor % pow(10, $casasDecimais);

    /* O extenso para a $parteInteira somente será gerado se esta for maior que zero. Para tanto, utilizamos
    * os préstimos do método GExtenso::numero().
    */
    if($parteInteira) {
        $ret = self::numero($parteInteira, $infoUnidade[self::POS_GENERO]) . ' ';
        $ret .= $parteInteira == 1 ? $infoUnidade[self::NUM_SING] : $infoUnidade[self::NUM_PLURAL];
    }

    /* De forma semelhante, o extenso da $fracao somente será gerado se esta for maior que zero. */
    if($fracao) {
        /* Se a $parteInteira for maior que zero, o extenso para ela já terá sido gerado. Antes de juntar os
        * centavos, precisamos colocar o conectivo 'e'.
        */
        if ($parteInteira) $ret .= ' e ';
        $ret .= self::numero($fracao, $infoFracao[self::POS_GENERO]) . ' ';
        $ret .= $parteInteira == 1 ? $infoFracao[self::NUM_SING] : $infoFracao[self::NUM_PLURAL];
    }

    return $ret;

    }

}

?>