<?php
class AnalisadorSintaticoDR {
    private $cont = 0;
    public $lexico;

    public function __construct(AnalisadorLexico $lexico) {
        $this->lexico = $lexico;
    }

    public function Programa() {
        if ($this->term('PALAVRARESERVADA', 'program') &&
            $this->term('IDENTIFICADOR') && 
            $this->term('ABRE_PARENTESES') && 
            $this->Lista_var() && 
            $this->term('FECHA_PARENTESES') && 
            $this->term('ABRE_CHAVES') && 
            $this->Lista_comandos() && 
            $this->term('FECHA_CHAVES')) {
            return true;
        }
        return false; 
    }

    public function Lista_var() {
        if ($this->Var()) {
            return $this->Lista_var();
        }
        return $this->vazio(); 
    }

    public function Var() {
        return $this->Tipo() && $this->term('IDENTIFICADOR') && $this->term('PONTO_E_VIRGULA');
    }

    public function Tipo() {
        return $this->term('INT') || $this->term('CHAR') || $this->term('FLOAT') || $this->term('ARRAY');
    }

    public function Lista_comandos() {
        if ($this->Comando()) {
            return $this->Lista_comandos();
        }
        return $this->vazio(); 
    }

    public function Comando() {
        return $this->Atribuicao() || 
               $this->Leitura() || 
               $this->Impressao() || 
               $this->Retorno() || 
               $this->ChamadaFuncao() || 
               $this->If() || 
               $this->For() || 
               $this->While();
    }

    public function Atribuicao() {
        return $this->term('IDENTIFICADOR') && 
               $this->term('ATRIBUICAO') && 
               $this->Expressao() && 
               $this->term('PONTO_E_VIRGULA');
    }

    public function Leitura() {
        return $this->term('READ') && 
               $this->term('ABRE_PARENTESES') && 
               $this->term('IDENTIFICADOR') && 
               $this->term('FECHA_PARENTESES') && 
               $this->term('PONTO_E_VIRGULA');
    }

    public function Impressao() {
        return $this->term('PRINT') && 
               $this->term('ABRE_PARENTESES') && 
               $this->Expressao() && 
               $this->term('FECHA_PARENTESES') && 
               $this->term('PONTO_E_VIRGULA');
    }

    public function Retorno() {
        return $this->term('RETURN') && 
               $this->Expressao() && 
               $this->term('PONTO_E_VIRGULA');
    }

    public function ChamadaFuncao() {
        return $this->term('IDENTIFICADOR') && 
               $this->term('ABRE_PARENTESES') && 
               $this->Expressao() && 
               $this->term('FECHA_PARENTESES') &&
               $this->term('ABRE_CHAVES') &&
               $this->Comando() &&
               $this->term('FECHA_CHAVES');
    }

    public function If() {
        return $this->term('IF') && 
               $this->term('ABRE_PARENTESES') && 
               $this->Expressao() && 
               $this->term('FECHA_PARENTESES') && 
               $this->term('ABRE_CHAVES') && 
               $this->Comando() && 
               $this->term('FECHA_CHAVES');
    }

    public function For() {
        return $this->term('FOR') && 
               $this->term('ABRE_PARENTESES') && 
               $this->Atribuicao() &&  
               $this->Expressao() && 
               $this->term('PONTO_E_VIRGULA') && 
               $this->Atribuicao() && 
               $this->term('FECHA_PARENTESES') && 
               $this->term('ABRE_CHAVES') && 
               $this->Comando() && 
               $this->term('FECHA_CHAVES');
    }

    public function While() {
        return $this->term('WHILE') && 
               $this->term('ABRE_PARENTESES') && 
               $this->Expressao() && 
               $this->term('FECHA_PARENTESES') && 
               $this->term('ABRE_CHAVES') && 
               $this->Comando() && 
               $this->term('FECHA_CHAVES');
    }

    public function Expressao() {
        if ($this->Termo()) {
            while ($this->term('SOMA') || 
                $this->term('SUBTRACAO') || 
                $this->OperadorLogico()) {
                $this->Termo();
            }
            return true;
        }
        return false;
    }

    public function OperadorLogico() {
        return $this->term('IGUAL') || 
            $this->term('DIFERENTE') || 
            $this->term('MENOR_QUE') || 
            $this->term('MAIOR_QUE') || 
            $this->term('MENOR_OU_IGUAL') || 
            $this->term('MAIOR_OU_IGUAL') || 
            $this->term('NEGACAO');
    }

    public function Termo() {
        if ($this->Fator()) {
            while ($this->term('MULTIPLICACAO') || $this->term('DIVISAO')) {
                $this->Fator();
            }
            return true;
        }
        return false;
    }

    public function Fator() {
        return $this->term('IDENTIFICADOR') || 
               $this->term('CONSTANTE') || 
               ($this->term('ABRE_PARENTESES') && $this->Expressao() && $this->term('FECHA_PARENTESES'));
    }

    private $erros = [];

    public function term($tk) {
        if ($this->cont < count($this->lexico->tokens)) {
            $tokenAtual = $this->lexico->tokens[$this->cont];
            if ($tk === $tokenAtual[0]) {
                $this->cont++;
                return true;
            } else {
                $this->erros[] = "Erro: Esperado '$tk', encontrado '{$tokenAtual[1]}'";
                return false;
            }
        } else {
            $this->erros[] = "Erro: Esperado '$tk', mas não há mais tokens.";
            return false;
        }
    }

    public function getErros() {
        return $this->erros;
    }

    public function vazio() {
        return true; 
    }
}
?>