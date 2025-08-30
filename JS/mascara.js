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


function data1(v) {
    v = v.replace(/\D/g, "");
    v = v.replace(/^(\d\d)(\d)/g, "$1/ $2");
    v = v.replace(/(\d{2})(\d)/, "$1/$2");
    return v;
}


// mascara do max ----------------------------------------------------------------------------//

// Máscara para nome: só permite letras e espaços e evita espaços duplos
function maskNome(v) {
    return (v || '')
        .replace(/[^a-zA-ZÀ-ÿ\s]/g, '') // remove números e símbolos
        .replace(/\s{2,}/g, ' ')        // evita espaços duplos
        .replace(/^\s+|\s+$/g, '');     // remove espaços no início e no fim
}

// Função para aplicar a máscara
function applyMask(el) {
    const type = el.getAttribute('data-mask');
    if (!type) return;

    let masked = el.value;
    if (type === "nome") masked = maskNome(el.value);

    if (el.value !== masked) el.value = masked;
}


document.querySelectorAll('[data-mask="nome"]').forEach(input => {
    input.addEventListener('input', () => applyMask(input));
});

  

  function attachMasks() {
    const inputs = document.querySelectorAll('input[data-mask]');
    inputs.forEach((input) => {
      applyMask(input);
  
      input.addEventListener('input', () => applyMask(input));
      input.addEventListener('blur', () => applyMask(input));
      input.addEventListener('paste', () => setTimeout(() => applyMask(input), 0));
    });
  }
  
  document.addEventListener('DOMContentLoaded', attachMasks);
  



 
function formatarValor(input) {
    let valor = input.value.replace(/\D/g, ''); 
    valor = (valor/100).toFixed(2).replace('.', ','); 
    valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, "."); 
    input.value = valor;
}


function formatarQuantidade(input) {
    input.value = input.value.replace(/\D/g, ''); 
}


window.addEventListener('DOMContentLoaded', () => {
    const qtdeInput = document.getElementById('qtde');
    const valorInput = document.getElementById('preco');

    if (qtdeInput) qtdeInput.addEventListener('keyup', () => formatarQuantidade(qtdeInput));
    if (valorInput) valorInput.addEventListener('keyup', () => formatarValor(valorInput));

   
    if (qtdeInput && qtdeInput.value) formatarQuantidade(qtdeInput);
    if (valorInput && valorInput.value) formatarValor(valorInput);
});






// Removed duplicate definitions of formatarValor and formatarQuantidade


// mascara da data de validade do prduto//


// mascaras.js
var data1 = function(campo, e) {
    var tecla = e.key;

    // Permite apenas números
    if (!/[0-9]/.test(tecla)) {
        e.preventDefault();
        return;
    }

    // Remove tudo que não é número
    campo.value = campo.value.replace(/\D/g, "");

    // Formata como dd/mm/aaaa
    if (campo.value.length > 2 && campo.value.length <= 4) {
        campo.value = campo.value.replace(/(\d{2})(\d+)/, "$1/$2");
    } else if (campo.value.length > 4) {
        campo.value = campo.value.replace(/(\d{2})(\d{2})(\d+)/, "$1/$2/$3");
    }

    // Limita a 10 caracteres
    if (campo.value.length > 10) campo.value = campo.value.slice(0, 10);
};




function cpf1(variavel){
    variavel=variavel.replace(/\D/g,"") //remove caracteres não numericos
    variavel=variavel.replace(/(\d{3})(\d)/,"$1.$2") //adiciona ponto entre o terceiro e quarto digitos
    variavel=variavel.replace(/(\d{3})(\d)/,"$1.$2") //adiciona ponto entre o sexto e setimo digitos
    variavel=variavel.replace(/(\d{3})(\d)/,"$1-$2") //adiciona hífen entre o nono e décimo digitos
    return variavel
}

