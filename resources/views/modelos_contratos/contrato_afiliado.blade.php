<div style="width:100%; text-align: center;">
    <img src="<?php echo $config->logo; ?>" />
    <br>
    <h2 style="text-align: center;">Contrato de Prestação de Serviços</h2>
</div>
<p style="text-align:justify">
    <strong>CONTRATANTE:</strong>
    <strong style="text-transform:uppercase"><?=$afiliado->razao_social?></strong>, comercialmente conhecido como <strong style="text-transform:uppercase"><?=$afiliado->nome_fantasia?></strong>, localizada no endereço <span style="text-transform:capitalize"><?=$afiliado->rua?> N°&nbsp;<?=$afiliado->numero?>&nbsp;<?php if(!is_null($afiliado->complemento)):?>-&nbsp;<?=trim($afiliado->complemento)?><?php endif;?>, <?=$afiliado->bairro?> - CEP: <?=formatarCEP($afiliado->cep)?> - <?=$afiliado->cidade?>/<span style="text-transform: uppercase"><?=$afiliado->estado?></span></span>, inscrito no CNPJ sob o N° <strong><?=formatarCNPJ($afiliado->cnpj)?></strong>.
    <br>
    Neste ato representado pelo(a) Sr(a). <strong style="text-transform:uppercase"><?=$afiliado->responsavelAfiliado->nome?></strong>, inscrito no CPF N° <strong><?=formatarCPF($afiliado->responsavelAfiliado->CPF)?></strong>, denominado simplesmente <strong>CONTRATANTE</strong>.
    <br><br>
    <strong>CONTRATADO: GILSON RIDHER RATIER QUEIROZ - ME</strong>, comercialmente conhecido como <strong><?php echo $config->nome_empresa; ?></strong>, localizado no endereço <?php echo $config->endereco ?>, inscrito no CNPJ sob o N° <strong><?php echo $config->cnpj; ?></strong>.
    <br>
    Neste ato representado pelo(a) Sr(a). <strong>GILSON RIDHER RATIER QUEIROZ</strong>, inscrito no CPF N° <strong>465.759.011-15</strong>, denominado simplemente <strong>CONTRATADA</strong>.
    <br><br>
    <strong>Cláusulas e Condições</strong>
    <br><br>

    <strong>Cláusula Primeira – DO OBJETO.</strong>
    <br>
    A <strong>CONTRATADA</strong> tem por força de contrato o objetivo de: Divulgar a empresa da <strong>CONTRATANTE</strong> em site especifico da <strong>CONTRATADA</strong>, voltados para os variados tipos de serviços em condomínios comerciais ou residenciais; Acompanhar os serviços ao qual se fez anuente no contrato de prestação de serviço entre a contratante e seu cliente final, com objetivo de melhorar a qualidade e segurança de funcionários e terceiros; Aplicar cursos e/ou palestras no mínimo a cada três meses; Fornecer cartão de desconto especial, para compra em diversos seguimentos; Elaborar contrato de prestação de serviço entre o <strong>CONTRATANTE</strong> e o cliente final; Pré selecionar e disponibilizar listagem de funcionários habilitados nas mais variadas áreas de prestação de serviços; Disponibilizar serviços jurídicos e técnicos nas mais variadas áreas com descontos diferenciados do mercado, sem ônus na consulta inicial; Disponibilizar aos funcionários convênios em vários seguimentos, saúde, beleza, alimentação, entretenimentos.
    <br><br>

    <strong>Cláusula Segunda – DO PRAZO</strong>
    <br>
    O presente contrato inicia-se em <?=formatarDataExtenso($planoAssinatura->data_contrato)?>, tendo vigência de no mínimo três meses após esse período por prazo indeterminado.
    <br><br>

    <strong>Cláusula Terceira – DO VALOR</strong>
    <br>
    <?php if($planoAssinatura->valor_comissao == 0):?>
    O valor contratual será de <?=formatarValor($planoAssinatura->valor)?> (<?=$planoAssinatura->valor_extenso?>) mensais, para empresas de manutenção predial e serviços técnicos.
    <?php else:?>
    O valor contratual será de <?=formatarValor($planoAssinatura->valor)?> (<?=$planoAssinatura->valor_extenso?>) mensais mais <?=formatarPercentual($afiliado->valor_comissao)?> da mão de obra referente aos serviços indicados pela CASA DO SÍNDICO.
    <?php endif;?>
    <br><br>

    <strong>Cláusula Quarta - DO REAJUSTE</strong>
    <br>
    Os reajustes serão anuais de acordo com a variação do salário mínimo pelos mesmos índices e mesma data.
    <br><br>

    <strong>Cláusula Quinta – DO PAGAMENTO</strong>
    <br>
    Fica a <strong>CONTRATANTE</strong>, condicionada ao pagamento das mensalidades, do contrato no quinto dia útil do mês subsequente ao inicio da assinatura deste instrumento. O pagamento devera ser através de boletos bancário em nome <strong>CONTRATADA</strong>, ou a sua ordem. No caso de atraso do pagamento será cobrado <?php $config->juros; ?>% de juros e <?php $config->multa; ?>% de multa ao dia, Após trinta dias de atraso, será retirado o link da <strong>CONTRATANTE</strong> do site até que seja regularizada os pagamentos.
    <br><br>

    <strong>Cláusula Sexta – DAS OBRIGAÇÕES DA CONTRATANTE</strong>
    <br>
    A <strong>CONTRATANTE</strong> ao prestar serviços para os clientes com a anuência da <strong>CONTRATADA</strong> obriga-se a:
    <br><br>
    1° - Honrar todo contrato de prestação de serviço cumprindo rigorosamente todas as clausulas contratuais.<br>
    2° - Observar que os profissionais compareçam ao local de trabalho devidamente uniformizado, equipados e munidos de identidade funcional.<br>
    3° - Dotar seus empregados da devida proteção referente às medidas de segurança, uso de EPI's e higiene do trabalho.<br>
    4° - Fazer plano de seguro de vida a todos os funcionários, cuja a função ofereça risco e mante-los em dia.<br>
    5. - Enviar pelo menos 1/3 dos funcionários para os cursos e/ou palestras profissionalizantes ministrados pela <strong>CONTRATADA</strong> a cada evento.<br>
    6° - Pagar todos os encargos decorrentes na prestação dos serviços no período vigente do contrato ao qual a <strong>CONTRATADA</strong>, se fez anuente, ou seja; fiscais, trabalhistas, previdenciários de acidente de trabalho, licenças ou indenização de qualquer natureza devida aos próprios empregados ou terceiros. Enviar copia da documentação e comprovantes para <strong>CONTRATADA</strong> mensalmente.<br>
    8° - Cumprir rigorosamente prazos de entrega dos serviços, o não cumprimento de prazos devido a culpa da <strong>CONTRATANTE</strong>, será passível de advertência por escrito, podendo sofrer sanções descritas em cláusulas do contrato de prestação de serviço ao cliente final.<br>   
    9° - A <strong>CONTRATANTE</strong> deverá apresentar um cronograma simples de prestação de serviço para todas as modalidades, com detalhes de pessoal, prazo, materiais aplicados e serviços a serem executados.<br>
    10° - A <strong>CONTRATANTE</strong>, somente poderá iniciar os serviços após a assinatura da ordem de serviço, e do contrato com o cliente final com a anuência do <strong>CONTRATADA</strong>.<br>
    11° - Os recebimentos dos valores referente aos serviços prestados ao cliente final, fica a cargo da <strong>CONTRATANTE</strong>, porem serão feito através da liberação da <strong>CONTRATADA</strong>, que pode ser por medição ou por obra pronta.<br>
    <br>
    Parágrafo único.<br>
    A <strong>CONTRATADA</strong>, somente autorizara a liberação dos pagamentos após a comparação do cronograma com o serviço executado e conformidade com o parágrafo 13° da Cláusula Sexta.<br>
    <br>
    12° - A <strong>CONTRATANTE</strong> somente poderá utilizar funcionários devidamente registrados na forma da lei, e se responsabiliza pelo recolhimento, nos respectivos vencimentos, de todos os tributos, ai compreendidos os impostos, taxas e contribuições, devido a União, ao Estado e ao Município, que incidam ou venha a incidir sobre sua atividade, desde que não haja retenções nas notas fiscais.  
    Outrossim, a mesma assume total e exclusiva responsabilidade pelo pagamento dos salários e consectários de todas as pessoas, por ela contratada, e que prestarem serviços à CLIENTELA, inclusive uniforme e demais despesas, e danos que decorram de acidente do trabalho, obrigando-se a pagar também todos os encargos sociais e observar a legislação laboral, as convenções, acordos e dissídios trabalhistas.
    Para todos os efeitos de direito, a <strong>CONTRATANTE</strong> é, e será a única empregadora de todos os obreiros que, por ela contatada, vier a trabalhar em favos de clientes com aval da <strong>CONTRATADA</strong> , como decorrência do presente ajuste, respondendo assim por todas as demandas judiciais proposta contra a primeira, e por eventuais danos financeiros que estas lhe venham a acarretar.  A <strong>CONTRATANTE</strong> assumira toda a responsabilidade ônus e encargos oriundos de sua relação com subcontratados, e a responsabilidade civil perante terceiros por danos decorrentes de ação ou omissão de empregados, serviçais ou prepostos.
    Na hipótese de alguma responsabilidade, em casos tais, vir a ser imputada a <strong>CONTRATADA</strong>, à <strong>CONTRATANTE</strong> assumirá a obrigação tão logo tenha conhecimentos do fato liberando a primeira.<br>
    <br>
    Parágrafo único: fica a critério da <strong>CONTRATANTE</strong> a prestação de serviço a outras empresas ou entidades sem a anuência em contrato da CONTRADA, porem será anula toda responsabilidade e funções a qual a <strong>CONTRATADA</strong> foi designada neste contrato, isso ocorrerá apenas nestes serviços específicos.<br>
    <br>
    13° - A <strong>CONTRATANTE</strong>, se obrigará a apresentar os comprovantes de pagamentos de encargos e funcionários locados, referente ao mês anterior de trabalhado, até que se findem as obras. Somente após a apresentação da documentação será liberado o pagamento da medição ou parcela.<br>
    14° - A <strong>CONTRATANTE</strong>, se responsabilizará pela elaboração de orçamento e fechamento da venda, assim como os recebimentos que será feito diretamente com o cliente final, através da anuência da <strong>CONTRATADA</strong>.<br>
    15° - Autorizar a <strong>CONTRATADA</strong>, a qualquer momento a visitas nos locais dos serviços prestados, para averiguação dos serviços em andamento.<br>
    16° - A apresentação do cartão de sócio personalizado juntamente com RG em qualquer aquisição nas lojas conveniadas para obter descontos.<br>
    17° - A justificar por escrito a qualquer advertência recebida também por escrito no prazo de 48 horas.<br>
    18° - Manter dados cadastrais sempre atualizados perante a CASA DO SINDICO como endereço, telefones, e-mails ou qualquer alteração no contrato social.<br>
    19° - Comunicar por escrito a CASA DO SINDICO, a necessidade de utilização dos serviços dos advogados e engenheiros conveniados, (para que possamos fazer o agendamento).<br>
    20° - Em caso de obras providenciar licenças legais através dos órgãos competentes.<br>
    <br>

    <strong>Cláusula Sétima – DAS OBRIGAÇÕES DA <strong>CONTRATADA</strong>.</strong>
    <br>
    1° - Manter disponível a <strong>CONTRATANTE</strong> acesso para comunicação em horário comercial, através de e-mail ou telefones.<br> 
    2° - Notificar por escrito a <strong>CONTRATANTE</strong> de deficiências e irregularidades encontradas na execução dos serviços, fixando prazo para a sua correção.<br>
    3° - Ministrar cursos e/ou palestras profissionalizantes aos funcionários da <strong>CONTRATANTE</strong>.<br>
    4° - Disponibilizar links no site da <strong>CONTRATADA</strong>, para pesquisa e contato direto do sindico com a <strong>CONTRATANTE</strong>.<br>
    5° - Manter sigilo referente orçamentos e qualquer outro tipo de documentos.<br>
    6° - Indicar a <strong>CONTRATANTE</strong>, através de lista os parceiros que promovem descontos especiais, através da apresentação do cartão de desconto exclusivo.<br>
    7° - Indicar a <strong>CONTRATANTE</strong>, os convênios, médicos, odontológicos, farmacêuticas e seguradoras com descontos especiais para <strong>CONTRATANTE</strong>, através da apresentação do cartão exclusivo.<br>
    8° - Orientar a <strong>CONTRATANTE</strong>, quanto a liberação de documentos necessários para o cumprimento de obras e serviços.<br>
    9° - Divulgar no site a programação de cursos e eventos para que a <strong>CONTRATANTE</strong> possa se matricular.<br>
    10° - Manter a orientação da <strong>CONTRATANTE</strong>, através de    profissionais das áreas de direito, engenharia civil, contabilidade, economista, administrador e segurança do trabalho.<br>
    11° - Disponibilizar no site ao <strong>CONTRATANTE</strong>, lista de prováveis colaboradores, previamente selecionados em suas funções, e pesquisados quanto a referencias anteriores, assim como antecedentes criminais.<br>
    12° - Manter nas dependências da <strong>CONTRATADA</strong>, arquivo de histórico de serviço prestado no período de 24 meses.<br>
    13° - Fornecer carta de referência da <strong>CONTRATANTE</strong> ao cliente final<br>
    <br>

    <strong>Cláusula Oitava -  Da rescisão de contrato</strong>
    <br>
    1° - A rescisão de contrato pode ser dada por ambas as partes, com  carta de aviso prévio de trinta dias, após a vigência do contrato.<br>
    2° - Não haverá custos para ambas as partes na rescisão de contrato, salvo haja motivos de inadimplência ou quebra de contratos com clientes da <strong>CONTRATANTE</strong>.<br>
    3° - Para a concretização da rescisão desse instrumento de contrato, é necessário que a <strong>CONTRATANTE</strong>, esteja rigorosamente em dia com a entrega das documentações exigidas em suas obrigações.<br>
    <br>

    <strong>Cláusula Nona – DAS DISPOSIÇÕES GERAIS</strong>
    <br>
    1° - É vedado a <strong>CONTRATANTE</strong> delegar ou transferir a terceiros no todo ou em parte, os serviços objeto do Contrato, salvo por consentimento expresso da <strong>CONTRATADA</strong>, após e amplamente justificado.<br>
    2° - Fica acordado entre as partes que, se por ventura a <strong>CONTRATANTE</strong> não conseguir cumprir o contrato onde a <strong>CONTRATADA</strong> se faz anuente, esta segunda assumira os direitos e deveres do contrato anuído.<br>  
    3° - A <strong>CONTRATADA</strong>, tem por obrigação, cobrar judicialmente a <strong>CONTRATANTE</strong>, o pleno cumprimento do contrato de prestação de serviço ao cliente final, cuja foi anuente.<br>
    4° - Fica eleito o FORO DA COMARCA DE <?php echo $config->foro; ?>, como o competente para dirimir as questões decorrentes da execução deste contrato, renunciando outro mais privilegiado que sejam como também os casos omissos e não regulado pelo presente instrumento.<br>
    <br>

    E, por estarem justos e contratados assinam o presente em 03 (três) vias de igual teor para o mesmo efeito, na presença das testemunhas abaixo assinadas.  
    <br><br>
    <?php echo $config->foro; ?>, <?=formatarDataExtenso($planoAssinatura->data_contrato)?>.
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
</p>