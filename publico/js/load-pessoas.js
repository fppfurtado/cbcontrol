// Inicia quando o DOM estiver pronto
$(function () {

    /* 
    Faz uma requisição ao servidor para recuperar 
    um array de objetos contendo informações das pessoas
    cadastradas    
    */
    $.ajax({
        type: "GET",
        url: "pessoas/"
    }).done(function (pessoas) {

        /* 
        Array que irá armazenar objetos contendo informações
        das pessoas cadastradas, porém apenas com as informações
        'id' e 'nome' (concatenação de primeiro_nome e ultimo_nome)
        */
        var discipuladores = [];

        // percorrendo o array de pessoas para popular o array 'discipuladores'
        for (var i = 0; i < pessoas.length; i++) {
            // concatenando primeiro_nome e ultimo_nome
            discipuladores.push({
                id: pessoas[i].id,
                nome: pessoas[i].primeiro_nome + ' ' + pessoas[i].ultimo_nome
            });
        }

        // inserindo um objeto vazio na primeira posição do array
        discipuladores.unshift("");

        // Carregando o plugin JSGrid
        $("#jsGrid").jsGrid({

            // Propriedade que contém um vetor com objetos que representam os campos da tabela
            fields: [
                { type: "text", name: "primeiro_nome", title: "Primeiro Nome", width: 60, filtering: true, validate: "required" },
                { type: "text", name: "ultimo_nome", title: "Último Nome", width: 60, filtering: true, validate: "required" },
                { type: "date", name: "data_nascimento", title: "Data de Nascimento", width: 50, filtering: true },
                { type: "date", name: "data_batismo", title: "Data de Batismo", width: 50, filtering: true },
                {
                    type: "text",
                    name: "telefone",
                    title: "Telefone",
                    width: 50,
                    filtering: false,
                    validate: "required",
                    // propriedade que define o coteúdo da célula em modo de exibição
                    itemTemplate: function (value) {
                        //console.log('telefone:itemTemplate => ' + value);
                        return $("<span>")
                            .html(value)
                            .addClass('cel')
                            .mask('(00) 00000-0000');
                    },
                    // propriedade que define o coteúdo da célula em modo de inserção
                    insertTemplate: function () {
                        //console.log('telefone:insertTemplate');
                        return jsGrid.fields.text.prototype.insertTemplate
                            .call(this)
                            .addClass('cel')
                            .mask('(00) 00000-0000');
                    },
                    // propriedade que define o coteúdo da célula em modo de edição
                    editTemplate: function (value) {
                        //console.log('telefone:editTemplate => ' + value);
                        return jsGrid.fields.text.prototype.editTemplate
                            .call(this)
                            .val(value)
                            .addClass('cel')
                            .mask('(00) 00000-0000');
                    }
                },
                { type: "text", name: "email", title: "Email", width: 100, filtering: false },
                {
                    type: "checkbox",
                    name: "e_professor",
                    title: "É Professor",
                    width: 50,
                    filtering: true,
                    filterTemplate: function () {
                        //return jsGrid.fields.checkbox.prototype.filterTemplate.call(this);
                        var controle, optBranco, optSim, optNao;

                        controle = this.filterControl = $('<select>');
                        optBranco = $('<option>').attr('value', '').text('').appendTo(controle);
                        optSim = $('<option>').attr('value', 'true').text('Sim').appendTo(controle);
                        optNao = $('<option>').attr('value', 'false').text('Não').appendTo(controle);

                        return controle;

                    },
                    filterValue: function () {
                        return this.filterControl.find('option:selected').val();
                    }
                },
                { type: "select", name: "discipulador", title: "Discipulador", width: 100, items: discipuladores, valueField: "id", textField: "nome" },
                { type: "control" }
            ],

            // propriedade que armazena a função que irá executar antes de um item ser inserido na tabela
            onItemInserting: function (args) {

                //console.log("onItemInserting");

                var tel = args.item.telefone.replace(new RegExp(/\(|\)|\-|\s/, 'g'), '');
                args.item.telefone = tel;
            },

            // propriedade que armazena a função que irá executar antes de um item ser atualizado na tabela
            onItemUpdating: function (args) {

                //console.log("onItemUpdating");

                var tel = args.item.telefone.replace(new RegExp(/\(|\)|\-|\s/, 'g'), '');
                args.item.telefone = tel;

            },

            rowClick: function (args) {

                $.ajax({
                    type: "GET",
                    url: "dons/"
                }).done(function (dons) {

                    //window.dons = dons;
                    $("<div>").jsGrid({
                        fields: [
                            { type: "select", name: "dom_id", title: "Nome", width: 50, items: dons, textField: "nome", valueField: "id", align: "left" },
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
                                    url: "pessoas/dons/?pessoa[id]=" + args.item.id,
                                    data: filter
                                });
                            },
                            insertItem: function (item) {
                                item.pessoa = { id: args.item.id };
                                return $.ajax({
                                    type: "POST",
                                    url: "pessoas/dons/",
                                    data: item
                                })
                            },
                            deleteItem: function (item) {
                                return $.ajax({
                                    type: "DELETE",
                                    url: "pessoas/dons/",
                                    data: item
                                });
                            }
                        }
                    }).dialog({ title: "Dons Espirituais", width: 500 });

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
                // Requisição do tipo GET ao servidor, retornando as pessoas cadastradas.
                loadData: function (filter) {
                    return $.ajax({
                        type: "GET",
                        url: "pessoas/",
                        data: filter
                    });
                },
                // Requisição do tipo POST ao servidor, inserindo a pessoas contida no objeto 'item'.
                insertItem: function (item) {
                    return $.ajax({
                        type: "POST",
                        url: "pessoas/",
                        data: item
                    });
                },
                // Requisição do tipo PUT ao servidor, atualizando informações da pessoa contida no objeto 'item'.
                updateItem: function (item) {
                    return $.ajax({
                        type: "PUT",
                        url: "pessoas/",
                        data: item
                    });
                },
                // Requisição do tipo DELETE ao servidor, removendo a pessoas contida no objeto 'item'.
                deleteItem: function (item) {
                    return $.ajax({
                        type: "DELETE",
                        url: "pessoas/",
                        data: item
                    });
                }
            }

        });

    });

})