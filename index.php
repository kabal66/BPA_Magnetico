<?php

class LayoutBpaMagnetico {

    private $linha;

    ########################################## Cabeçalho #######################################################    
    private $cbc_mvm; #Ano e mês de Processamento da produção
    private $cbc_lin; #Número de linhas do BPA gravadas. 
    private $cbc_flh; #Quantidades de folhas de BPA gravadas.
    private $cbc_smt_vrf; #Campo de controle.DOMÍNIO [1111..2221]
    private $cbc_rsp; #Nome do órgão de origem responsável pela informação.
    private $cbc_sgl; #Sigla do órgão de origem responsável pela digitação. 
    private $cbc_cgccpf; #CGC/CPF do prestador ou do órgão público responsável pela informação, conforme cadastro na Receita Federal.
    private $cbc_dst; #Nome do órgão de saúde destino do arquivo.
    private $cbc_dst_in = "E"; #Indicador do órgão destino:
    private $cbc_versao; #Versão do sistema, informação livre, pode conter qualquer letra e numero.
    private $cbc_fim; #Correspondente aos caracteres CR - CHR(13) + LF - CHR(10), do padrão ASCII (.TXT), indicando fim do cabeçalho.
    ##############################################################################################################


    ########################################## Consolidado ######################################################
    private $prd_cnes; #Código do CNES. A última posição à direita é o dígito verificador.
    private $prd_cmp; #Competência de realização do procedimento. 
    private $prd_cbo; #Código do CBO do profissional 
    private $prd_flh; #Número da folha do BPA. Domínio [001..999] 
    private $prd_seq; #Número sequencial da linha dentro da folha do BPA. Domínio [01..20] 
    private $prd_pa; #Código do procedimento ambulatorial. A última posição à direita é o dígito verificador.
    private $prd_ldade; # Idade (0 a 130 anos)
    private $prd_qt; #Quantidade de procedimentos produzidos.
    private $prd_org; #Origem das informações:
   // private $prd_fim; #Corresponde aos caracteres CR + CHR(13) + LF - CHR(10), do código ASCII(TXT)
    ##############################################################################################################

     
    ########################################## Individualizado ######################################################    
    private $prd_ident;
   # private $prd_cnes; #Código do CNES. A última posição à direita é o dígito verificador.
   # private $prd_cmp; #Competência de realização do procedimento.
    private $prd_cnsmed;  #Número do CNS do Profissional com dígito verificador válido
   # private $prd_cbo; #Código do CBO do profissional 
    private $prd_dtaten; #Data de atendimento 
   # private $prd_flh; #Número da folha do BPA. Domínio [001..999]
   # private $prd_seq; #Número sequencial da linha dentro da folha do BPA. Domínio [01..20]
   # private $prd_pa; #Código do procedimento ambulatorial. A última posição à direita é o dígito verificador.
    private $prd_cnspa; #CNS do paciente com dígito verificador válido
    private $prd_sexo; #Sexo do paciente 
    private $prd_ibge; #Código IBGE do município de residência
    private $prd_cid; # CID-10
   # private $prd_ldade; # Idade (0 a 130 anos)
   # private $prd_qt; #Quantidade de procedimentos produzidos.
    private $prd_caten; #Caracter de atendimento 
    private $prd_naut; #Numero da Autorização do estabelecimento 
   # private $prd_org; #Origem das informações:
    private $prd_nmpac; # Nome completo do paciente. Sequência de caracteres alfanuméricos
    private $prd_dtnasc; #Data de nascimento do paciente 
    private $prd_raca; #Raça/Cor do paciente
    private $prd_etnia; #Etnia do paciente
    private $prd_nac; #Nacionalidade do paciente
    private $prd_srv; #Código do Serviço
    private $prd_clf; #Código da Classificação
    private $prd_equipe_seq; #Código da Sequencia da Equipe
    private $prd_equipe_area; #Código da Area da Equipe 
    private $prd_cnpj; #Código do CNPJ, conforme cadastro na Receita Federal da empresa que realizou a manutenção ou adaptação da OPM 
    private $prd_cep_pcnte; # Código CEP paciente
    private $prd_lograd_pcnte; #Código logradouro paciente
    private $prd_end_pcnte; #Endereço do paciente 
    private $prd_compl_pcnte; #Complemento do endereço do paciente
    private $prd_num_pcnte; #Número do endereço do paciente
    private $prd_bairro_pcnte; #Bairro do endereço do paciente
    private $prd_ddtel_pcnte; #Telefone do paciente
    private $prd_email_pcnte; #E-mail do paciente
    private $prd_ine; #Indentificação nacional de equipes 
    ##############################################################################################################
    

    // Atenção, esse diretorio tem que ter permissão www-data 
    private $pathArquivoSaida = "/var/www/bpa_magnetico/file_out/";
    
    public function getLinha() {
        return $this->linha;
    }

    public function cabecalho() {
        // implementação do cabeçalho do layout        

        $this->$linha = $this->adicionarEspacoZero(01, 2, 'num'); //Indicador de linha do Header 

        $this->$linha .= $this->adicionarEspacoZero("#BPA#", 5, ''); //Indicador de início do cabeçalho

        $this->$linha .= $this->adicionarEspacoZero($this->cbc_mvm, 6, 'num'); //O campo deverá ser preenchido apenas com números. Formato AAAAMM

        $this->$linha .= $this->adicionarEspacoZero($this->cbc_lin, 6, 'num'); //O campo deverá ser preenchido apenas com números. Adicionar zeros à esquerda.

        $this->$linha .= $this->adicionarEspacoZero($this->cbc_flh, 6, 'num'); //O campo deverá ser preenchido apenas com números. Adicionar zeros à esquerda.


        //$this->cbc_smt_vrf = ((($this->$prd_pa + $this->prd_qt) % 1111)+1111);
        $this->$linha .= $this->adicionarEspacoZero($this->cbc_smt_vrf, 4, 'num'); //Veja observação no final deste arquivo.

        $this->$linha .= $this->adicionarEspacoZero($this->cbc_rsp, 30, ''); //Adicionar espaço em branco a direita até completar total caracteres.

        $this->$linha .= $this->adicionarEspacoZero($this->cbc_sgl, 6, ''); //Adicionar espaço em branco a direita até completar total caracteres.
        
        $this->$linha .= $this->adicionarEspacoZero($this->cbc_cgccpf, 14, 'num'); //O campo deverá ser preenchido apenas com números. Adicionar zeros à esquerda.

        $this->$linha .= $this->adicionarEspacoZero($this->cbc_dst, 40, ''); //Adicionar espaço em branco a direita até completar total caracteres.

        $this->$linha .= $this->adicionarEspacoZero($this->cbc_dst_in, 1, ''); // E - Estadual M - Municipal

        $this->$linha .= $this->adicionarEspacoZero($this->cbc_versao, 10, ''); //Adicionar espaço em branco a direita até completar total caracteres.

        $this->$linha .= $this->adicionarEspacoZero("\r\n", 2, ''); 
      
        return $this->$linha;
    }

    
  
    
    public function bpaIndividualizado() {
        // implementação do corpo do layout        

        $this->$linha = $this->adicionarEspacoZero($this->prd_ident, 2, 'num'); //O campo deverá ser preenchido apenas com números.        
        
        $this->$linha = $this->adicionarEspacoZero($this->prd_cnes, 7, 'num'); //O campo deverá ser preenchido apenas com números. Adicionar zeros à esquerda.
        
        $this->$linha = $this->adicionarEspacoZero($this->prd_cmp, 6, 'num'); //O campo deverá ser preenchido apenas com números. Formato AAAAMM

        $this->$linha = $this->adicionarEspacoZero($this->prd_cnsmed, 15, 'num'); //O campo deverá ser preenchido apenas com números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_cbo, 6, ''); //Código conforme a Classificação Brasileira de Ocupações (CBO)

        $this->$linha = $this->adicionarEspacoZero($this->prd_dtaten, 8, 'num'); //O campo deverá ser preenchido apenas com números. Formato AAAAMMDD

        $this->$linha = $this->adicionarEspacoZero($this->prd_flh, 3, 'num'); //Adicionar zeros à esquerda de um inteiro.

        $this->$linha = $this->adicionarEspacoZero($this->prd_seq, 2, 'num'); //Adicionar zeros à esquerda de um inteiro.

        $this->$linha = $this->adicionarEspacoZero($this->prd_pa, 10, 'num'); // Adicionar zeros à esquerda.

        $this->$linha = $this->adicionarEspacoZero($this->prd_cnspac, 15, 'num'); // Este campo é obrigatório quando o procedimento informado exigir e deverá ser preenchido apenas com números

        $this->$linha = $this->adicionarEspacoZero($this->prd_sexo, 1, ''); // M - Masculino ou F - Feminino

        $this->$linha = $this->adicionarEspacoZero($this->prd_ibge, 6, 'num'); //Quando preenchido, deverá ser apenas com números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_cid, 4, ''); //CID-10

        $this->$linha = $this->adicionarEspacoZero($this->prd_idade , 3, 'num'); //O campo deverá ser preenchido apenas com números. Adicionar zeros à esquerda

        $this->$linha = $this->adicionarEspacoZero($this->prd_qt, 6, 'num'); //Adicionar zeros à esquerda de um inteiro.

        $this->$linha = $this->adicionarEspacoZero($this->prd_caten, 2, 'num'); //Quando preenchido, deverá ser apenas com números. Adicionar zeros à esquerda

        $this->$linha = $this->adicionarEspacoZero($this->prd_naut, 13, 'num'); //Quando preenchido, deverá ser apenas com números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_org, 3, ''); // "BPA" -SIA/SUS
                                                                           // "PNI" -PROG. NAC. DE IMUNIZAÇÕES
                                                                           // "SIE" –SIGAE
                                                                           // "SIB" –SIGAB
                                                                           // "MIN" -MATERNO INFANTIL
                                                                           // "PAC"-PROGRAMA AÇÃO COMUNITÁRIA
                                                                           // "SCL"-SISCOLO
                                                                           //"EXT"-OUTROS SISTEMAS

        $this->$linha = $this->adicionarEspacoZero($this->prd_nmpac, 30, ''); //Adicionar espaço em branco a direita até completar total caracteres

        $this->$linha = $this->adicionarEspacoZero($this->prd_dtnasc, 8, 'num'); // Formato AAAAMMDD

        $this->$linha = $this->adicionarEspacoZero($this->prd_raca, 2, 'num'); // 01 Branca
                                                                               // 02 Preta
                                                                               // 03 Parda
                                                                               // 04 Amarela
                                                                               // 05 Indígena
                                                                               // 99 Sem informação
                                                                               // Quando preenchido, deverá conter apenas números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_etnia, 4, 'num'); // Definido na PORTARIA SAS Nº 508, DE 28 DE
                                                                                // SETEMBRO DE 2010. Anexo I.
                                                                                // Preencher somente se o campo raça/cor for 05 -Indígena.
                                                                                // A partir da competência Out/2010. preencher com
                                                                                // brancos caso a raça/cor for diferente de 05 ou
                                                                                // competência anterior a Out/2010.

        $this->$linha = $this->adicionarEspacoZero($this->prd_nac, 3, 'num'); // Quando preenchido, deverá conter apenas números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_srv, 3, 'num'); // Este campo é obrigatório quando o procedimento informado exigir e deverá 
                                                                              // ser preenchido apenas com números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_clf, 3, 'num'); // Este campo é obrigatório quando o procedimento informado exigir e deverá
                                                                              // ser preenchido apenas com números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_equipe_seq, 8, 'num'); // Quando preenchido, deverá conter apenas números.                                                                                     

        $this->$linha = $this->adicionarEspacoZero($this->prd_equipe_area, 4, 'num'); // Quando preenchido, deverá conter apenas números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_cnpj, 14, 'num'); // Quando preenchido, deverá conter apenas números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_cep_pcnte, 8, 'num'); // Quando preenchido, deverá conter apenas números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_lograd_pcnte, 3, 'num'); // Quando preenchido, deverá conter apenas números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_end_pcnte, 30, ''); // adicionar espaço em branco a direita até completar total caracteres.

        $this->$linha = $this->adicionarEspacoZero($this->prd_compl_pcnte, 10, ''); // adicionar espaço em branco a direita até completar total caracteres.

        $this->$linha = $this->adicionarEspacoZero($this->prd_num_pcnte, 5, ''); // adicionar espaço em branco a direita até completar total caracteres.

        $this->$linha = $this->adicionarEspacoZero($this->prd_bairro_pcnte, 30, ''); // adicionar espaço em branco a direita até completar total caracteres.

        $this->$linha = $this->adicionarEspacoZero($this->prd_ddtel_pcnte, 11, 'num'); // Quando preenchido, deverá conter apenas números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_email_pcnte, 40, ''); // Quando preenchido, deverá conter apenas números.

        $this->$linha = $this->adicionarEspacoZero($this->prd_ine, 10, 'num'); // Quando preenchido, deverá ser apenas com números.
                                                                               // Adicionar zeros à esquerda.
                                                                               // Apartir da competência 08/2015

       
        $this->$linha = $this->adicionarEspacoZero("\r\n", 2, ''); // Corresponde aos caracteres CR + CHR(13) + LF - CHR(10), do código ASCII(TXT)                                                                               
    }
    

    
    public function bpaConsolidado() {
        // implementação do rodapé do layout
        echo "<footer>Este é o rodapé do layout BPA magnético</footer>";

        $this->$linha = $this->adicionarEspacoZero($this->prd_cnes, 7, 'num'); //O campo deverá ser preenchido apenas com números. Adicionar zeros à esquerda.

        $this->$linha .= $this->adicionarEspacoZero($this->prd_cmp, 6, 'num'); //O campo deverá ser preenchido apenas com números. Formato AAAAMM

        $this->$linha .= $this->adicionarEspacoZero($this->prd_cbo, 6, ''); //Código conforme a Classificação Brasileira de Ocupações (CBO)

        $this->$linha .= $this->adicionarEspacoZero($this->prd_flh, 3, 'num'); //Adicionar zeros à esquerda de um inteiro.

        $this->$linha .= $this->adicionarEspacoZero($this->prd_seq, 2, 'num'); //Adicionar zeros à esquerda de um inteiro.

        $this->$linha .= $this->adicionarEspacoZero($this->prd_pa, 10, 'num'); //O campo deverá ser preenchido apenas com números. Adicionar zeros à esquerda.

        $this->$linha .= $this->adicionarEspacoZero($this->prd_ldade, 3, 'num');  //O campo deverá ser preenchido apenas com números. Adicionar zeros à esquerda.

        $this->$linha .= $this->adicionarEspacoZero($this->prd_qt, 6, ''); //O campo deverá ser preenchido apenas com números. Adicionar zeros à esquerda de um inteiro.
        
        $this->$linha .= $this->adicionarEspacoZero($this->prd_org, 14, ''); //"BPA" -SIA/SUS   
                                                                             //"PNI" -PROG. NAC. DE IMUNIZAÇÕES   
                                                                             //"SIE" –SIGAE  
                                                                             //"SIB" –SIGAB  
                                                                             //"MIN" -MATERNO INFANTIL  
                                                                             //"PAC"-PROGRAMA AÇÃO COMUNITÁRIA  
                                                                             //"SCL"-SISCOLO 
                                                                             //"EXT"-OUTROS SISTEMAS

        $this->$linha .= $this->adicionarEspacoZero("\r\n", 2, ''); //Adicionar espaço em branco a direita até completar total caracteres. 
      
        return $this->$linha;
    }

    public static function adicionarEspacoZero($texto, $tamanho, $tipo) {
        if (is_numeric($texto) && $tipo == "num") {
            $texto = str_pad($texto, $tamanho, "0", STR_PAD_LEFT); // adiciona zero à esquerda
        } else {
            $texto = str_pad($texto, $tamanho, " ", STR_PAD_RIGHT); // adiciona espaço à direita
        }
        return $texto;
    }

    public function gravarNoArquivo($nomeArquivo, $conteudo) {

        // abre o arquivo para escrita, sobrescrevendo o conteúdo anterior, ou cria um arquivo novo caso não exista        
        $arquivo = fopen($this->pathArquivoSaida.$nomeArquivo, "a+");     

        // verifica se o arquivo foi aberto com sucesso
        if ($arquivo === false) {
            echo "<br>Erro ao abrir o arquivo!";
        } else {
            // escreve o conteúdo no arquivo
            fwrite($arquivo, $conteudo);
            // fecha o arquivo
            fclose($arquivo);
            echo "Conteúdo gravado com sucesso no arquivo $nomeArquivo!";
        }
    }
}


## Exemplo 
$layout = new LayoutBpaMagnetico();

$layout->gravarNoArquivo("bpa" . "_" . date("Y-m-d") . ".txt",  $layout->cabecalho());
$layout->gravarNoArquivo("bpa" . "_" . date("Y-m-d") . ".txt",  $layout->bpaConsolidado());
$layout->gravarNoArquivo("bpa" . "_" . date("Y-m-d") . ".txt",  $layout->bpaIndividualizado());
##############