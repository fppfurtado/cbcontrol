// Inicia quando o DOM estiver pronto
$(function () {

    /* 
    Faz duas requisições ao servidor para recuperar 
    dois arrays de objetos: pessoas e classes.
    */
    $.when(
        $.ajax({
            type: "GET",
            url: "pessoas/?e_professor=true"
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
        var professores = [];

        // percorrendo o array de aulas pa ra popular o array "professores"
        for (var i = 0; i < pessoas[0].length; i++) {
            // concatenando primeiro_nome e ultimo_nome
            professores.push({
                id: pessoas[0][i].id,
                nome: pessoas[0][i].primeiro_nome + " " + pessoas[0][i].ultimo_nome
            });
        }

        // inserindo um objeto vazio na primeira posição dos arrays que irão preencher o "select"
        professores.unshift("");
        classes[0].unshift("");

        // Carregando o plugin JSGrid
        $("#jsGrid").jsGrid({

            // Propriedade que contém um vetor com objetos que representam os campos da tabela
            fields: [
                { type: "date", name: "data", title: "Data", width: 50 },
                { type: "select", name: "classe.id", title: "Classe", width: 70, filtering: true, items: classes[0], valueField: "id", textField: "nome", validate: "required" },
                { type: "select", name: "professor.id", title: "Professor", width: 70, filtering: true, items: professores, valueField: "id", textField: "nome", validate: "required" },
                { type: "number", name: "num_licao", title: "Número Lição", width: 40, align: "center" },
                { type: "number", name: "estudo_licao", title: "Estudo Lição", width: 40, align: "center" },
                { type: "number", name: "pequeno_grupo", title: "Pequeno Grupo", width: 40, align: "center" },
                { type: "number", name: "estudo_biblico", title: "Estudo Bíblico", width: 40, align: "center" },
                { type: "number", name: "ativ_missionarias", title: "Atividades Missionárias", width: 40, align: "center" },
                { type: "control" }
            ],

            rowClick: function (args) {

                $.ajax({
                    type: "GET",
                    url: "alunos/"
                }).done(function (alunos) {

                    for (var i = 0; i < alunos.length; i++) {
                        alunos[i].nome = alunos[i].pessoa.primeiro_nome + " " + alunos[i].pessoa.ultimo_nome;
                        //alunos[i].mat_id = alunos[i].matricula.id;
                    }
                    //window.alunos = alunos;
                    $("<div>").jsGrid({
                        fields: [
                            { 
                                type: "select", 
                                name: "matricula.id", 
                                title: "Nome", 
                                width: 50, 
                                items: alunos, 
                                textField: "nome", 
                                valueField: "id", 
                                align: "left", 
                                validate: "required",
                                insertTemplate: function() {
                                    
                                    this.items = $.grep(this.items, function(pr) {
                                        return pr.classe.id === args.item.classe.id;
                                    });

                                    return jsGrid.fields.select.prototype.insertTemplate.call(this);

                                }
                            },
                            { type: "control", editButton: false }
                        ],
                        width: "90%",
                        height: "auto",
                        inserting: true,
                        paging: true,
                        pageSize: 10,
                        autoload: true,
                        controller: {
                            loadData: function (filter) {
                                return $.ajax({
                                    type: "GET",
                                    url: "presencas/?aula[id]=" + args.item.id,
                                    data: filter
                                });
                            },
                            insertItem: function (item) {
                                item.aula = { id: args.item.id };
                                return $.ajax({
                                    type: "POST",
                                    url: "presencas/",
                                    data: item
                                })
                            },
                            deleteItem: function(item) {
                                return $.ajax({
                                    type: "DELETE",
                                    url: "presencas/",
                                    data: item
                                });
                            }
                        }
                    }).dialog({ title: "Alunos", width: 500});

                });

            },

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
                // Requisição do tipo GET ao servidor, retornando as aulas cadastradas.
                loadData: function (filter) {
                    return $.ajax({
                        type: "GET",
                        url: "aulas/",
                        data: filter
                    });
                },
                // Requisição do tipo POST ao servidor, inserindo a aulas contida no objeto "item".
                insertItem: function (item) {
                    return $.ajax({
                        type: "POST",
                        url: "aulas/",
                        data: item
                    });
                },
                // Requisição do tipo PUT ao servidor, atualizando informações da pessoa contida no objeto "item".
                updateItem: function (item) {
                    return $.ajax({
                        type: "PUT",
                        url: "aulas/",
                        data: item
                    });
                },
                // Requisição do tipo DELETE ao servidor, removendo a aulas contida no objeto "item".
                deleteItem: function (item) {
                    return $.ajax({
                        type: "DELETE",
                        url: "aulas/",
                        data: item
                    });
                }
            }

        });

    });

})