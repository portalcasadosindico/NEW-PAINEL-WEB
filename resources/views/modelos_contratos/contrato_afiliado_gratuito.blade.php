<div style="width:100%; text-align: center;">
    <img src="<?php echo $config->logo; ?>" />
    <br>
    <h2 style="text-align: center; margin-bottom: 3px;">CONTRATO DE PRESTAÇÃO DE SERVIÇO.</h2>
    <h4 style="margin:0px;">Filiação gratuita de empresa.</h4>
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
    
    <p class=MsoNormal><span style='font-family:"Verdana","sans-serif"'>&nbsp;</span></p>
    
    <p class=MsoNormal style='line-height:150%'><b style='mso-bidi-font-weight:
normal'><span style='font-family:"Verdana",sans-serif'>Cláusula primeira – DO
OBJETO.</span></b><span style='font-family:"Verdana",sans-serif'></span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>A
<b style='mso-bidi-font-weight:normal'>CONTRATADA </b>tem por força de contrato
o objetivo de:</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>1º
- Divulgar a empresa da <b style='mso-bidi-font-weight:normal'>CONTRATANTE</b>
em site específico da <b style='mso-bidi-font-weight:normal'>CONTRATRADA</b>,
voltados para os mais variados tipos de serviços em condomínios seja comercial,
residencial e/ou shoppings.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>2º
- Auxiliar na fiscalização, para melhoria da qualidade dos serviços, assim como
a segurança de funcionários e terceiros nos postos por nós indicados.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>3º
- Aplicar cursos, palestras e reuniões periódicas para melhorar o funcionamento
do grupo, aplicar ajustes de mudança de mercado, apresentação da evolução e
andamento dos negócios aplicados.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>4º
- Buscar parceiros no mercado para redução de custos em aquisições de materiais
e serviços especializados nos mais diversos seguimentos.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>5º
- Disponibilizar carteira de possíveis funcionários, para futuras contratações,
estes habilitados nas mais variadas áreas de prestação de serviços.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>6º
- Disponibilizar serviços jurídicos e técnicos, nas mais variadas áreas com descontos
diferenciados do mercado.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>7º
- Participar de reuniões condominiais a pedido da <b style='mso-bidi-font-weight:
normal'>CONTRATADA</b> para apresentação da empresa e dar o aval de empresa
idônea.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>8º
- Fornecer periodicamente a <b style='mso-bidi-font-weight:normal'>CONTRATADA,</b>
relatórios e tabelas da mais variadas como controle de orçamentos, gráfico de
aprovação de obras por condominio, relatório de orçamentos recebidos através do
site, whats app ou aplicativos.</span></p>

<p class=MsoNormal><span style='font-family:"Verdana",sans-serif'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal style='line-height:150%'><b style='mso-bidi-font-weight:
normal'><span style='font-family:"Verdana",sans-serif'>Cláusula segunda – DO
PRAZO</span></b></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>1º
- O presente contrato inicia-se em <?=formatarDataExtenso($planoAssinatura->data_contrato)?>, tendo como <span
style='mso-bidi-font-weight:bold'>vigência</span><b style='mso-bidi-font-weight:
normal'> </b><span style='mso-bidi-font-weight:bold'>de prazo</span><b
style='mso-bidi-font-weight:normal'> indeterminado</b>, podendo ser rescindido a
qualquer momento por qualquer das partes, sem motivo procedente, com aviso
prévio de 30 dias sem que haja multas para ambos os lados desde que não houve prejuízo
<span style='color:black;mso-color-alt:windowtext;background:white'>predecessores</span>.</span></p>

<p class=MsoNormal><b style='mso-bidi-font-weight:normal'><span
style='font-family:"Verdana",sans-serif'><o:p>&nbsp;</o:p></span></b></p>

<p class=MsoNormal style='line-height:150%'><b style='mso-bidi-font-weight:
normal'><span style='font-family:"Verdana",sans-serif'>Cláusula terceira – DO
VALOR E FORMA DE PAGAMENTO.</span></b><span style='font-family:"Verdana",sans-serif'></span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>1º
- O valor contratual será sem custo algum ao <b>CONTRATANTE.</b></span></p>

<p class=MsoNormal><span style='font-family:"Verdana",sans-serif'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal style='line-height:150%'><b style='mso-bidi-font-weight:
normal'><span style='font-family:"Verdana",sans-serif'>Cláusula quarta - DO
REAJUSTE</span></b><span style='font-family:"Verdana",sans-serif'></span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>1º
- Os reajustes financeiros não serão contabilizados por se tratar de prestação
de serviço sem custos.</span></p>

<p class=MsoNormal><b style='mso-bidi-font-weight:normal'><span
style='font-family:"Verdana",sans-serif'><o:p>&nbsp;</o:p></span></b></p>

<p class=MsoNormal style='line-height:150%'><b style='mso-bidi-font-weight:
normal'><span style='font-family:"Verdana",sans-serif'>Cláusula quinta – DAS OBRIGAÇÕES
DA CONTRATANTE</span></b><span style='font-family:"Verdana",sans-serif'></span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>A
<b style='mso-bidi-font-weight:normal'>CONTRATANTE</b> ao prestar serviços para
os clientes com a anuência da <b style='mso-bidi-font-weight:normal'>CONTRATADA</b>
obriga-se a:</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>1º
- Observar que os profissionais compareçam ao local de trabalho devidamente
uniformizados, equipados e munidos de identidade funcional.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>2º.
- Dotar seus empregados da devida proteção referente às medidas de segurança,
como uso de <span class=SpellE>EPI´s</span> e <span class=SpellE>NRs</span> de
acordo com o serviço prestado e higiene do trabalho.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>3º.
- Pagar todos os encargos decorrentes na prestação dos serviços no período
vigente do contrato ao qual a <b style='mso-bidi-font-weight:normal'>CONTRATADA,
</b>se fez anuente, ou seja; fiscais, trabalhistas, previdenciários de acidente
de trabalho, licenças ou indenização de qualquer natureza devida aos próprios
empregados ou terceiros. Enviar copia da documentação e comprovantes para <b
style='mso-bidi-font-weight:normal'>CONTRATADA</b> mensalmente.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>4º
- A <b style='mso-bidi-font-weight:normal'>CONTRATANTE </b>deve ser a empresa
da atividade fim, e deve constar em seu CNAE, as atividades que se propõe a
fazer, fica expressamente proibida a terceirização dos serviços, salvo por
complemento de atividades especificas.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>5º
- A <b style='mso-bidi-font-weight:normal'>CONTRATANTE</b> somente poderá
utilizar funcionários devidamente registrados na forma da lei, e se
responsabiliza pelo recolhimento, nos respectivos vencimentos, de todos os
tributos, <span class=GramE>ai</span> compreendidos os impostos, taxas e
contribuições, devido a União, ao Estado e ao Município, que incidam ou venha a
incidir sobre sua atividade, desde que não haja retenções nas notas fiscais.<span
style='mso-spacerun:yes'>  </span>Outrossim, a mesma assume total e exclusiva
responsabilidade pelo pagamento dos salários e consectários de todas as pessoas,
por ela contratada, e que prestarem serviços à clientela, inclusive emissão de
notas fiscais, uniforme e demais despesas, e danos que decorram de acidente do
trabalho, obrigando-se a pagar também todos os encargos sociais e observar a
legislação laboral, as convenções, acordos e dissídios trabalhistas.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>6º
- Para todos os efeitos de direito, a <b style='mso-bidi-font-weight:normal'>CONTRATANTE</b>
é, e será a única empregadora de todos os obreiros que, por ela contatada, vier
a trabalhar em favos de clientes com aval da <b style='mso-bidi-font-weight:
normal'>CONTRATADA</b>, como decorrência do presente ajuste, respondendo assim
por todas as demandas judiciais proposta contra a primeira, e por eventuais
danos financeiros que estas lhe venham a acarretar. </span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>7º
- A <b style='mso-bidi-font-weight:normal'>CONTRATANTE</b> assumira toda a
responsabilidade ônus e encargos oriundos de sua relação com subcontratados, e
a responsabilidade civil perante terceiros por danos decorrentes de ação ou
omissão de empregados, serviçais ou prepostos.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>8º
- Na hipótese de alguma responsabilidade, em casos tais, vir a ser imputada a <b
style='mso-bidi-font-weight:normal'>CONTRATADA</b>, à <b style='mso-bidi-font-weight:
normal'>CONTRATANTE</b> assumirá a obrigação tão logo tenha conhecimentos do
fato liberando a primeira. </span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>9º
- A <b style='mso-bidi-font-weight:normal'>CONTRATANTE, </b>se responsabilizará
pela elaboração de orçamento e fechamento da venda, assim como os recebimentos
que será feito diretamente do cliente final.<b style='mso-bidi-font-weight:
normal'></b></span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>10º
- Autorizar a <b style='mso-bidi-font-weight:normal'>CONTRATADA, </b>a qualquer
momento a visitar nos locais dos serviços prestados, para averiguação dos
serviços em andamento.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>11º
- A</span><span style='font-family:"Verdana",sans-serif;mso-bidi-font-family:
Arial'>utoriza a <b style='mso-bidi-font-weight:normal'>CONTRATADA</b> o
direito de imagem</span><span style='font-family:"Verdana",sans-serif'>.</span></p>

<p class=MsoNormal align=left style='text-align:left;line-height:150%'><span
style='font-family:"Verdana",sans-serif;mso-bidi-font-family:Arial'>12º - Manter
dados cadastrais sempre atualizados perante a <b style='mso-bidi-font-weight:
normal'>CONTRATADA, </b>como endereço, telefones, e-mails ou qualquer alteração
no contrato social.</span></p>

<p class=MsoNormal align=left style='text-align:left;line-height:150%'><span
style='font-family:"Verdana",sans-serif;mso-bidi-font-family:Arial'>13º - </span><span
style='font-family:"Verdana",sans-serif'>Honrar todo contrato de prestação com
o cliente final cumprindo rigorosamente todas as clausulas contratuais.</span></p>

<p class=MsoNormal align=left style='text-align:left;line-height:150%'><b
style='mso-bidi-font-weight:normal'><i style='mso-bidi-font-style:normal'><u><span
style='font-family:"Verdana",sans-serif'>Parágrafo único</span></u></i></b><b
style='mso-bidi-font-weight:normal'><span style='font-family:"Verdana",sans-serif'>;
</span></b><span style='font-family:"Verdana",sans-serif;mso-bidi-font-weight:
bold'>a</span><b style='mso-bidi-font-weight:normal'><span style='font-family:
"Verdana",sans-serif'> CONTRATANTE </span></b><span style='font-family:"Verdana",sans-serif'>obriga
se a quitar dos os valores, referente a despesas oriundas da prestação de
serviço a qual se fez responsável, com pena de ser cobrado judicialmente.</span></p>

<p class=MsoNormal><b style='mso-bidi-font-weight:normal'><span
style='font-family:"Verdana",sans-serif'><o:p>&nbsp;</o:p></span></b></p>

<p class=MsoNormal style='line-height:150%'><b style='mso-bidi-font-weight:
normal'><span style='font-family:"Verdana",sans-serif'>Cláusula sexta – DAS
OBRIGAÇÕES DA CONTRATADA.</span></b><span style='font-family:"Verdana",sans-serif'></span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>1º
- Manter disponível á <b style='mso-bidi-font-weight:normal'>CONTRATANTE </b>acesso
para comunicação em horário comercial, através de e-mail ou telefones. </span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>2º
- Notificar por escrito a <b style='mso-bidi-font-weight:normal'>CONTRATANTE</b>
de deficiências e irregularidades encontradas na execução dos serviços, fixando
prazo para a sua correção.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>3º
- Ministrar cursos e/ou palestras profissionalizantes aos funcionários da <b
style='mso-bidi-font-weight:normal'>CONTRATANTE</b>, com valores reduzidos.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>4º
- Disponibilizar acesso no site e APP da <b style='mso-bidi-font-weight:normal'>CONTRATADA</b>,
para pesquisa.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>5º
- Manter sigilo referente orçamentos e qualquer outro tipo de documentos.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>6º
- Indicar a <b style='mso-bidi-font-weight:normal'>CONTRATANTE</b>, através de
lista os parceiros que promovem descontos especiais.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>7º
- Indicar a <b style='mso-bidi-font-weight:normal'>CONTRATANTE</b>, os convênios,
médicos, odontológicos, oficinas mecânicas, escolas de <span class=SpellE>NRs</span>,
farmácias, seguradoras tudo com descontos especiais para a <b style='mso-bidi-font-weight:
normal'>CONTRATANTE </b>através de convênios.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>8º
- Orientar a <b style='mso-bidi-font-weight:normal'>CONTRATANTE</b>, quanto a
liberação de documentos necessários para o cumprimento de obras e serviços.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>9º
- Divulgar a programação de cursos e eventos para que a <b style='mso-bidi-font-weight:
normal'>CONTRATANTE</b> possa se matricular.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>10º
- Manter a orientação da <b style='mso-bidi-font-weight:normal'>CONTRATANTE</b>,
através de profissionais das áreas de direito, engenharia civil, contabilidade,
economista, administrador e segurança do trabalho.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>11º
- Manter nas dependências da <b style='mso-bidi-font-weight:normal'>CONTRATADA,
</b>arquivo de<b style='mso-bidi-font-weight:normal'> </b>histórico de serviço
prestado no período de 24 meses.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>12º
- Fornecer carta de referência da <b style='mso-bidi-font-weight:normal'>CONTRATANTE</b>
ao cliente final.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>13º
- Manter igualdade entre as empresas não praticando favorecimento qualquer para
uma ou para outra.</span></p>

<p class=MsoNormal><span style='font-family:"Verdana",sans-serif'><span
style='mso-spacerun:yes'> </span></span></p>

<p class=MsoNormal style='line-height:150%'><b style='mso-bidi-font-weight:
normal'><span style='font-family:"Verdana",sans-serif'>Clausula sétima - DA
RESCISÃO DE CONTRATO</span></b><span style='font-family:"Verdana",sans-serif'></span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>1º
- A rescisão de contrato pode ser dada por ambas as partes, com carta de aviso
prévio de trinta dias.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>2º
- Não haverá custos para ambas as partes na rescisão de contrato.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>4º
A <b>CONTRATANTE</b>, obriga-se a dar garantia das obras por ela executadas,
nos moldes antes descritos nos contratos cuja consta a anuência da <b>CONTRATADA</b>,
mesmo que já tenha se desfiliado da CASA DO SINDICO, sob pena de ação judicial
para reparo de danos a <b>CONTRATADA </b>ou mesmo ao cliente final, ou seja, o
condomínio.</span></p>

<p class=MsoNormal><span style='font-family:"Verdana",sans-serif'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal style='line-height:150%'><b style='mso-bidi-font-weight:
normal'><span style='font-family:"Verdana",sans-serif'>Cláusula oitava – DAS
DISPOSIÇÕES GERAIS</span></b></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>1º
- É vedado a <b style='mso-bidi-font-weight:normal'>CONTRATANTE</b> delegar ou
transferir a terceiros no todo ou em parte, os serviços objeto do Contrato,
salvo por consentimento expresso da <b style='mso-bidi-font-weight:normal'>CONTRATADA</b>,
após e amplamente justificado.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>2º
- A <b style='mso-bidi-font-weight:normal'>CONTRATADA, </b>tem por obrigação,
cobrar judicialmente a <b style='mso-bidi-font-weight:normal'>CONTRATANTE</b>,
o pleno cumprimento do contrato de prestação de serviço ao cliente final.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>4º
- Fica eleito o <b>FORO DA COMARCA DE FLORIANÓPOLIS/SC</b>, como o competente
para dirimir as questões decorrentes da execução deste contrato, renunciando
outro mais privilegiado que sejam como também os casos omissos e não regulado
pelo presente instrumento.</span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal style='line-height:150%'><span style='font-family:"Verdana",sans-serif'>E,
por estarem justos e contratados assinam o presente instrumento juntamente com as
testemunhas abaixo assinadas.</span></p>
    
    
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