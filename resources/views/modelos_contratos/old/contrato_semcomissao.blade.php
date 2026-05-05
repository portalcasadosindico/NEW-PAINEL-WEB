<div style="width:100%; text-align: center;">
    <img src="<?php echo $config->logo; ?>" />
    <br>
    <h2 style="text-align: center;">Contrato de Prestação de Serviços</h2>
</div>
<p style="text-align:justify">
    Contrato de prestação de serviços que entre si fazem a empresa <strong style="text-transform:uppercase"><?=$afiliado->razao_social?></strong>, comercialmente conhecido como <strong style="text-transform:uppercase"><?=$afiliado->nome_fantasia?></strong>, sitio a Rua <span style="text-transform:capitalize"><?=$afiliado->rua?> N°&nbsp;<?=$afiliado->numero?>&nbsp;<?php if(!is_null($afiliado->complemento)):?>-&nbsp;<?=trim($afiliado->complemento)?><?php endif;?>, <?=$afiliado->bairro?> - CEP: <?=formatarCEP($afiliado->cep)?> - <?=$afiliado->cidade?>/<span style="text-transform: uppercase"><?=$afiliado->estado?></span></span>, inscrito no CNPJ sob o N° <strong><?=formatarCNPJ($afiliado->cnpj)?></strong>.
    <br>
    Neste ato representado pelo seu administrador, inscrito no CPF N° <strong><?=formatarCPF($afiliado->responsavelAfiliado->CPF)?></strong>, denominado simplesmente <strong>CONTRATANTE</strong>,
    e a <strong><?php echo $config->nome_empresa; ?></strong>, pessoa jurídica de direito privado, inscrita no CNPJ sob o N° <strong><?php echo formatarCNPJ($config->cnpj); ?></strong>, sediada na <?php echo $config->endereco ?>, doravente denominada simplesmente <strong>CONTRATADA</strong>, de acordo com as cláusulas e condições que segue:
    <br><br>

    <strong>Cláusula Primeira – DO OBJETO.</strong>
    <br>
    A <strong>CONTRATADA</strong> tem por força de contrato o objetivo de:
    <br>
    1º - Divulgar a empresa da <strong>CONTRATANTE</strong> em site especifico da <strong>CONTRATADA</strong>, voltados para os variados tipos de serviços em condomínios comerciais ou residenciaise/ou shoppings.
    <br>
    2º - Acompanhar os serviços ao qual a se fez anuente no contrato de prestação de serviço entre a contratante e contratado, com objetivo de garantir ao Cliente final a entrega das obras ou serviços contratados de acordo com o contrato firmado das obras e serviços entre as partes.
    <br>
    3º - auxiliar na fiscalização para melhoria da qualidade dos serviços assim como a segurança de funcionários e terceiros.
    <br>
    4º - Aplicar cursos, palestras e reuniões periódicas para melhorar o funcionamento do grupo, aplicar ajustes de mudança de mercado, apresentação da evolução e andamento dos negócios aplicados.
    <br>
    5º - Buscar parceiros no mercado para redução de custos em aquisições de materiais e serviços especializados nos mais diversos seguimentos.
    <br>
    6º - Elaborar contrato de prestação de serviço entre o contratante e o e o contratado.
    <br>
    7º- Administrar o plano de fidelidade para aumentar fluxo de vendas da <strong>CONTRATANTE, (CONDS)</strong>.
    <br>
    8º - Disponibilizar carteira de possíveis funcionários para futuras contratações, habilitados nas mais variadas áreas de prestação de serviços.
    <br>
    9º - Disponibilizar serviços jurídicos e técnicos nas mais variadas áreas com descontos diferenciados do mercado.
    <br>
    10º - Participar de reuniões condominiais a pedido da <strong>CONTRATADA</strong> para apresentação da empresa e dar o aval de empresa idônea.
    <br>
    11º - fornecer periodicamente a <strong>CONTRATADA</strong> relatórios e tabelas da mais variadas como controle de orçamentos enviados por empresa, gráfico de aprovação de obras por condominio, relatório de orçamentos recebidos através do site, whats app ou aplicativos, relatório de recebimentos do plano de Fidelidade <strong>(CONDS)</strong>, modelos de planilhas para orçamentos, modelos de plano de obra, modelo de check list para medição predial, modelo de aviso de inicio de obras ou serviços, relatório de fechamento individual das despesas mensais da <strong>CONTRATADA</strong>.
    <br>
    12º - Assessoria para verificação de orçamento através de simulados.
    <br>
    13º - fornecer requerimento para pedido de elaboração de contratos ao cliente final.
    <br><br>

    <strong>Cláusula Segunda – DO PRAZO</strong>
    <br>
    O presente contrato inicia-se em <?=formatarDataExtenso($planoAssinatura->data_contrato)?>, tendo vigência de três meses após esse período o contrato será por prazo indeterminado, podendo ser rescindido a qualquer momento por qualquer das partes, sem motivo procedente, com aviso prévio de 30 dias sem que haja multas para ambos os lados desde que não houve prejuízo predecessores.
    <br>
    <strong><i><u>Parágrafo Único;</u></i></strong> Na hipótese do <strong>CONTRATANTE</strong>, solicitar o desligamento da filiação com obras e serviços em andamento, esse será retomado pela <strong>CONTRATADA</strong> e repassado para outro filiado devidamente registrado.
    <br><br>

    <strong>Cláusula Terceira – DO VALOR E FORMA DE PAGAMENTO</strong>
    <br>
    1º - O valor contratual será de <?=formatarValor($planoAssinatura->valor)?> (<?=$planoAssinatura->valor_extenso?>) mensais, sem qualquer comissão referente a serviços prestados.
    <br>
    2º - A <strong>CONTRATANTE</strong> pagara mensalmente através de boletos bancários em nome da <strong>CONTRATADA</strong> o valor acima estipulado com vencimento todo o dia 12 de cada mês subsequente aos serviços prestados e enquanto da vigência do contrato, caso a data de pagamento incidir com finais de semana ou feriado o prazo se estendera até o próximo dia útil.
    <br>
    3º - No caso de atraso do pagamento será cobrado <?php $config->juros; ?>% de juros e <?php $config->multa; ?>% de multa ao dia, após <?php $config->dias_inadimplencia_bloqueio; ?> dias de atraso, será retirado o link da <strong>CONTRATANTE</strong> do site e deixara de receber as solicitações de orçamentos, e após 30 dias de inadimplência a <strong>CONTRATANTE</strong> será incluída no cadastro de proteção ao credito até que sejam regularizados os pagamentos ao setor financeiro da <strong>CONTRATADA</strong>.
    <br>
    <strong><i><u>Parágrafo primeiro;</u></i></strong> Caso a <strong>CONTRATANTE</strong>, fique bloqueada dos recebimentos dos orçamentos no período da vigência obrigatória não lhe da o direito de descontos ou mesmo do cancelamento das faturas dos meses seguintes até que se finde o prazo de vigência obrigatório.
    <br>
    <strong><i><u>Parágrafo segundo;</u></i></strong> As comissões dos serviços executados e porcentagens referentes ao plano de fidelidade <strong>(CONDS)</strong> serão cobradas no vigésimo quinto dia do mês subsequente da prestação de serviço de forma integral ou parcelado dependendo da forma de pagamento ao qual estipulado no contrato com o cliente final.
    <br>
    <strong><i><u>Parágrafo terceiro;</u></i></strong> Fica a cargo da <strong>CONTRATANTE</strong>, o repasse de 2% do valor correspondente a mão de obra dos serviços prestados através dos contratos assinados para o fundo do plano de fidelidade (CONDS). Essa porcentagem não se refere a comissão e sim programa de captação e fidelização de clientes, e não pode ser devolvida a <strong>CONTRATANTE</strong>, pois uma vez paga pertence ao cliente final.
    <br>
    <strong><i><u>Parágrafo quarto;</u></i></strong> Na hipótese de inadimplência da <strong>CONTRATANTE</strong>, das mensalidades ou plano de fidelidade não poderá iniciar qualquer obra ou serviço até que seja regularizada a situação perante o setor financeiro da <strong>CONTRATADA</strong>.
    <br>
    <strong><i><u>Parágrafo quinto;</u></i></strong> Na hipótese de inadimplência da <strong>CONTRATANTE</strong>, das mensalidades ou plano de fidelidade, e que tiver obras e serviços em andamento a <strong>CONTRATADA</strong> se reserva o direito de receber o valor devido diretamente do cliente final, até que seja regularizada a situação perante o setor financeiro da <strong>CONTRATADA</strong> ou que se findem os serviços ora contratados.

    <br><br>

    <strong>Cláusula Quarta - DO REAJUSTE</strong>
    <br>
    Os reajustes financeiros serão atualizados todo dia 01 de janeiro de cada ano, caso a <strong>CONTRATADA</strong> achar necessário, respeitando os índices do salário minimino decretado pelo governo federal, e também podendo ter outros reajustes conforme implantação de novas tecnologias ou investimentos que agregue benefícios ao <strong>CONTRATANTE</strong>.
    <br>
    <strong><i><u>Parágrafo único;</u></i></strong> Os reajustes acima mencionados não poderão ser aplicados às empresas que tiverem no prazo de vigência obrigatória de três meses.

    <br><br>

    <strong>Cláusula Quinta – DAS OBRIGAÇÕES DA CONTRATANTE</strong>
    <br>
    A <strong>CONTRATANTE</strong> ao prestar serviços para os clientes com a anuência da <strong>CONTRATADA</strong> obriga-se a:
    <br><br>
    1º - cumprir com as normas de pagamentos descritos na clausula terceira, e todos seus parágrafos.
    <br>
    2º - Observar que os profissionais compareçam ao local de trabalho devidamente uniformizado, equipados e munidos de identidade funcional.
    <br>
    3º. - Dotar seus empregados da devida proteção referente às medidas de segurança, uso de EPI´s NRs de acordo com o serviço prestado e higiene do trabalho.
    <br>
    4º. – fazer plano de seguro de vida a todos os funcionários, cuja função ofereça risco.
    <br>
    5º.– enviar pelo menos 1/3 dos funcionários para os cursos e/ou palestras profissionalizantes ministrados pela <strong>CONTRATADA</strong> a cada evento.
    <br>
    6º. - Pagar todos os encargos decorrentes na prestação dos serviços no período vigente do contrato ao qual a <strong>CONTRATADA</strong>, se fez anuente, ou seja; fiscais, trabalhistas, previdenciários de acidente de trabalho, licenças ou indenização de qualquer natureza devida aos próprios empregados ou terceiros. Enviar copia da documentação e comprovantes para <strong>CONTRATADA</strong> mensalmente.
    <br>
    7º - cumprir rigorosamente prazos de entrega dos serviços, o não cumprimento de prazos devido a culpa da <strong>CONTRATANTE</strong>, será passível de advertência por escrito, podendo sofrer sanções descritas em cláusulas do contrato de prestação de serviço ao cliente final.
    <br>
    8º - a <strong>CONTRATANTE</strong> deverá apresentar um cronograma simples de prestação de serviço para todas as modalidades, com detalhes de pessoal, prazo, materiais aplicados e serviços a serem executados.
    <br>
    9º - A <strong>CONTRATANTE</strong>, somente poderá iniciar os serviços após a elaboração do contrato com o cliente final, assinado por todas as partes e com a anuência da <strong>CONTRATADA</strong>.
    <br>
    10º - A <strong>CONTRATANTE</strong> deve ser a empresa da atividade fim, e deve constar em seu CNAE, as atividades que se propõe a fazer, ficam expressamente proibida a terceirização dos serviços, salvo por complemento de atividades especificas.
    <br>
    11º - A <strong>CONTRATANTE</strong> somente poderá utilizar funcionários devidamente registrados na forma da lei, e se responsabiliza pelo recolhimento, nos respectivos vencimentos, de todos os tributos, ai compreendidos os impostos, taxas e contribuições, devido a União, ao Estado e ao Município, que incidam ou venha a incidir sobre sua atividade, desde que não haja retenções nas notas fiscais.  Outrossim, a mesma assume total e exclusiva responsabilidade pelo pagamento dos salários e consectários de todas as pessoas, por ela <strong>CONTRATADA</strong>, e que prestarem serviços à clientela, inclusive emissão de notas fiscais, uniforme e demais despesas, e danos que decorram de acidente do trabalho, obrigando-se a pagar também todos os encargos sociais e observar a legislação laboral, as convenções, acordos e dissídios trabalhistas.
    <br>
    12º Para todos os efeitos de direito, a <strong>CONTRATANTE</strong> é, e será a única empregadora de todos os obreiros que, por ela contatada, vier a trabalhar em favos de clientes com aval da <strong>CONTRATADA</strong>, como decorrência do presente ajuste, respondendo assim por todas as demandas judiciais proposta contra a primeira, e por eventuais danos financeiros que estas lhe venham a acarretar.
    <br>
    13º A <strong>CONTRATANTE</strong> assumira toda a responsabilidade ônus e encargos oriundos de sua relação com subcontratados, e a responsabilidade civil perante terceiros por danos decorrentes de ação ou omissão de empregados, serviçais ou prepostos.
    <br>
    14º Na hipótese de alguma responsabilidade, em casos tais, vir a ser imputada a <strong>CONTRATADA</strong>, à <strong>CONTRATANTE</strong> assumirá a obrigação tão logo tenha conhecimentos do fato liberando a primeira.
    <br>
    15º - A <strong>CONTRATANTE</strong>, se obrigará a apresentar os comprovantes de pagamentos de encargos e funcionários locados, referente ao mês anterior de trabalhado, até que se findem as obras. Somente após a apresentação da documentação será liberado o pagamento da medição ou parcela.
    <br>
    16º -A <strong>CONTRATANTE</strong>, se responsabilizará pela elaboração de orçamento e fechamento da venda, assim como os recebimentos que será feito diretamente do cliente final, através da anuência da <strong>CONTRATADA</strong>.
    <br>
    17º - Autorizar a <strong>CONTRATADA</strong>, a qualquer momento a visitar nos locais dos serviços prestados, para averiguação dos serviços em andamento.
    <br>
    18º - autoriza a <strong>CONTRATADA</strong> o direito de imagem.
    <br>
    19º - Manter dados cadastrais sempre atualizados perante a <strong>CONTRATADA</strong>, como endereço, telefones, e-mails ou qualquer alteração no contrato social.
    <br>
    20º - Honrar todo contrato de prestação com o cliente final cumprindo rigorosamente todas as clausulas contratuais.
    <br>
    21º - Fornecer requerimento devidamente preenchido para elaboração de contrato com o cliente final.
    <br>
    <strong><i><u>Parágrafo primeiro;</u></i></strong> Fica a <strong>CONTRATANTE</strong>, ciente que a posse dos serviços pertence a <strong>CONTRATADA</strong>, que também se faz anuente no contrato firmado com o cliente final, e terá pleno direito de desagregar a  <strong>CONTRATANTE</strong>, das funções ora <strong>CONTRATADA</strong>s, assim como bloqueio dos pagamentos restantes, assumindo a retomadas das obras e serviços, absorvendo assim os pagamentos restantes que fazem parte da garantia do cliente final. Esse caso em especifico só pode ser aplicado caso a <strong>CONTRATANTE</strong>, tenha infringido as normas explicitas neste instrumento de contrato, e ter sido notificada pela <strong>CONTRATADA</strong> expressamente por três vezes e tendo prazo razoável para sanar a deficiência.
    <br>
    <strong><i><u>Parágrafo segundo;</u></i></strong> A <strong>CONTRATANTE</strong> obriga se a quitar dos os valores, referente a despesas oriundas da prestação de serviço a qual a <strong>CONTRATADA</strong> se faze anuente, com pena de ser cobrado judicialmente.
    <br><br>

    <strong>Cláusula Sexta – DAS OBRIGAÇÕES DA <strong>CONTRATADA</strong>.</strong>
    <br>
    1º - Manter disponível á <strong>CONTRATANTE</strong> acesso para comunicação em horário comercial, através de e-mail ou telefones.
    <br>
    2º - Notificar por escrito a <strong>CONTRATANTE</strong> de deficiências e irregularidades encontradas na execução dos serviços, fixando prazo para a sua correção.
    <br>
    3º - ministrar cursos e/ou palestras profissionalizantes aos funcionários da <strong>CONTRATANTE</strong>, com valores reduzidos.
    <br>
    4º Disponibilizar acesso no site da <strong>CONTRATADA</strong>, para pesquisa.
    <br>
    5º - Manter sigilo referente orçamentos e qualquer outro tipo de documentos.
    <br>
    6º - Indicar a <strong>CONTRATANTE</strong>, através de lista os parceiros que promovem descontos especiais.
    <br>
    7º - Indicar a <strong>CONTRATANTE</strong>, os convênios, médicos, odontológicos, oficinas mecânicas, escolas de NRs, farmácias, seguradoras tudo com descontos especiais para a <strong>CONTRATANTE</strong> através de convênios.
    <br>
    8º - Orientar a <strong>CONTRATANTE</strong>, quanto a liberação de documentos necessários para o cumprimento de obras e serviços.
    <br>
    9º - Divulgar a programação de cursos e eventos para que a <strong>CONTRATANTE</strong> possa se matricular.
    <br>
    10º - Manter a orientação da <strong>CONTRATANTE</strong>, através de profissionais das áreas de direito, engenharia civil, contabilidade, economista, administrador e segurança do trabalho.
    <br>
    11º - Manter nas dependências da <strong>CONTRATADA</strong>, arquivo de histórico de serviço prestado no período de 24 meses.
    <br>
    12º - Fornecer carta de referência da <strong>CONTRATANTE</strong> ao cliente final.<br>
    13º - Manter igualdade entre as empresas não praticando favorecimento qualquer pra uma ou pra outra.
    <br><br>

    <strong>Cláusula Sétima -  Da rescisão de contrato</strong>
    <br>
    1° - A rescisão de contrato pode ser dada por ambas as partes, com  carta de aviso prévio de trinta dias, após a vigência do contrato.<br>
    2° - Não haverá custos para ambas as partes na rescisão de contrato, salvo haja motivos de inadimplência ou quebra de contratos com clientes da <strong>CONTRATANTE</strong>.<br>
    3° - Para a concretização da rescisão desse instrumento de contrato, é necessário que a <strong>CONTRATANTE</strong>, esteja rigorosamente em dia com a entrega das documentações exigidas em suas obrigações e e que não esteja com qualquer obra ou serviço em andamento com a anuência da <strong>CONTRATADA</strong>.
    <br><br>

    <strong>Cláusula Oitava – DAS DISPOSIÇÕES GERAIS</strong>
    <br>
    1° - É vedado a <strong>CONTRATANTE</strong> delegar ou transferir a terceiros no todo ou em parte, os serviços objeto do Contrato, salvo por consentimento expresso da <strong>CONTRATADA</strong>, após e amplamente justificado.<br>
    2° - Fica acordado entre as partes que, se por ventura a <strong>CONTRATANTE</strong> não conseguir cumprir o contrato onde a <strong>CONTRATADA</strong> se faz anuente, esta segunda assumira os direitos e deveres do contrato anuído.<br>
    3° - A <strong>CONTRATADA</strong>, tem por obrigação, cobrar judicialmente a <strong>CONTRATANTE</strong>, o pleno cumprimento do contrato de prestação de serviço ao cliente final, cuja foi anuente.<br>
    4° - Fica eleito o FORO DA COMARCA DE <?php echo $config->foro; ?>, como o competente para dirimir as questões decorrentes da execução deste contrato, renunciando outro mais privilegiado que sejam como também os casos omissos e não regulado pelo presente instrumento.<br>
    <br>

    E, por estarem justos e contratados assinam o presente instrumento juntamente com as testemunhas abaixo assinadas.
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