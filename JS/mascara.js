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