// Executar Mascara
function mascara(o, funcao) {
    setTimeout(function () {
        o.value = funcao(o.value);
    }, 1);
}

function telefone1(v) {
    v = v.replace(/\D/g, "");
    v = v.replace(/^(\d\d)(\d)/g, "($1) $2");
    v = v.replace(/(\d{5})(\d)/, "$1-$2");
    return v;
}


function nome1(v) {
    return v.replace(/\d/g, "");
}


function cnpj1(variavel){
    variavel=variavel.replace(/\D/g,"") //remove caracteres não numericos
    variavel=variavel.replace(/(\d{2})(\d)/,"$1.$2") //adiciona ponto entre o segundo e terceiro digitos
    variavel=variavel.replace(/(\d{3})(\d)/,"$1.$2") //adiciona ponto entre o sexto e setimo digitos
    variavel=variavel.replace(/(\d{3})(\d)/,"$1/$2") //adiciona ponto entre o sexto e setimo digitos
    variavel=variavel.replace(/(\d{4})(\d)/,"$1-$2") //adiciona ponto entre o sexto e setimo digitos
    return variavel
}
// Função genérica para aplicar máscaras em tempo real
function mascara(o, f) {
    setTimeout(function () {
        o.value = f(o.value);
    }, 1);
}

// Apenas letras (para nomes, cargos, etc.)
function nome1(v) {
    return v.replace(/[^a-zA-ZÀ-ÿ\s]/g, ""); // remove números e caracteres especiais
}

// CPF -> 000.000.000-00
function mascaraCPF(v) {
    v = v.replace(/\D/g, "");                 // remove tudo que não for dígito
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    return v;
}

// Telefone -> (00) 00000-0000 ou (00) 0000-0000
function mascaraTelefone(v) {
    v = v.replace(/\D/g, "");
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
    v = v.replace(/(\d)(\d{4})$/, "$1-$2");
    return v;
}

// CEP -> 00000-000
function mascaraCEP(v) {
    v = v.replace(/\D/g, "");
    v = v.replace(/^(\d{5})(\d)/, "$1-$2");
    return v;
}

// Data -> 00/00/0000
function mascaraData(v) {
    v = v.replace(/\D/g, "");
    v = v.replace(/(\d{2})(\d)/, "$1/$2");
    v = v.replace(/(\d{2})(\d)/, "$1/$2");
    v = v.replace(/(\d{4})(\d)/, "$1");
    return v;
}

// Salário (formato com vírgula e ponto)
function mascaraSalario(v) {
    v = v.replace(/\D/g, ""); 
    v = (v/100).toFixed(2) + ""; // divide por 100 para ter casas decimais
    v = v.replace(".", ","); // substitui ponto por vírgula
    v = v.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // separador de milhar
    return v;
}
