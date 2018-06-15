// Inicia quando o DOM estiver pronto
$(function () {

    /* 
    Faz duas requisições ao servidor para recuperar 
    dois arrays de objetos: pessoas e classes.
    */
    $.when(
        $.ajax({
            type: "GET",
            url: "pessoas/"
        }),
        $.ajax({
            type: "GET",
            url: "classes/"
        })
    ).done(function (pessoas, classes) {

        /* 
        Array que irá armazenar as informações necessárias dos 
        objetos Pessoa: "id" e "nome". 
        */
        var alunos = [];
        
        // percorrendo o array de matriculas pa ra popular o array "alunos"
        for (var i = 0; i < pessoas[0].length; i++) {
            // concatenando primeiro_nome e ultimo_nome
            alunos.push({
                id: pessoas[0][i].id,
                nome: pessoas[0][i].primeiro_nome + " " + pessoas[0][i].ultimo_nome
            });
        }

        // inserindo um objeto vazio na primeira posição dos arrays que irão preencher o "select"
        alunos.unshift("");
        classes[0].unshift("");
        
        // Carregando o plugin JSGrid
        $("#jsGrid").jsGrid({

            // Propriedade que contém um vetor com objetos que representam os campos da tabela
            fields: [
                { type: "select", name: "classe_id", title: "Classe", width: 70, filtering: true, items: classes[0], valueField: "id", textField: "nome",validate: "required", align: "center" },
                { type: "select", name: "pessoa_id", title: "Nome", width: 70, filtering: true, items: alunos, valueField: "id", textField: "nome", validate: "required", align: "left" },
                { 
                    type: "checkbox", 
                    name: "esta_cursando", 
                    title: "Está Cursando", 
                    width: 50, 
                    filtering: true,
                    filterTemplate: function() {
                        
                        var controle, optBranco, optSim, optNao;
                        
                        controle = this.filterControl = $("<select>");
                        optBranco = $("<option>").attr("value", "").text("").appendTo(controle);
                        optSim = $("<option>").attr("value", "true").text("Sim").appendTo(controle);
                        optNao = $("<option>").attr("value", "false").text("Não").appendTo(controle);
                        
                        return controle;

                    },
                    filterValue: function() {
                        return this.filterControl.find("option:selected").val();
                    }
                },
                { type: "date", name: "data_entrada", title: "Data Entrada", width: 50},
                { type: "date", name: "data_saida", title: "Data Saída", width: 50},
                { 
                    type: "text", 
                    name: "frequencia", 
                    title: "Frequência", 
                    width: 50, 
                    align: "center", 
                    readOnly: true,
                    cellRenderer: function(value) {
                        if(value >= 70) {
                            return $("<td>").css("color","green").html(value+"%");
                        } else {
                            return $("<td>").css("color","red").html(value+"%");
                        }
                        
                    }
                 },
                { type: "control" }
            ],
            
            width: "100%",
            height: "auto",

            filtering: true,
            sorting: true,
            inserting: true,
            editing: true,
            paging: true,
            pageSize: 10,
            pageButtonCount: 5,

            noDataContent: "Sem Dados",
            deleteConfirm: "Confirmar deletar?",

            autoload: true,
            // propriedade que armazena o objeto controlador do comportamento da tabela
            controller: {
                // Requisição do tipo GET ao servidor, retornando as matriculas cadastradas.
                loadData: function (filter) {
                    return $.ajax({
                        type: "GET",
                        url: "matriculas/",
                        data: filter
                    });
                },
                // Requisição do tipo POST ao servidor, inserindo a matriculas contida no objeto "item".
                insertItem: function (item) {
                    return $.ajax({
                        type: "POST",
                        url: "matriculas/",
                        data: item
                    });
                },
                // Requisição do tipo PUT ao servidor, atualizando informações da pessoa contida no objeto "item".
                updateItem: function (item) {
                    return $.ajax({
                        type: "PUT",
                        url: "matriculas/",
                        data: item
                    });
                },
                // Requisição do tipo DELETE ao servidor, removendo a matriculas contida no objeto "item".
                deleteItem: function (item) {
                    return $.ajax({
                        type: "DELETE",
                        url: "matriculas/",
                        data: item
                    });
                }
            }

        });

    });

})