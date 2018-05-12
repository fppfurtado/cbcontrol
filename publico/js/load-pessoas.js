$(function(){

    $.ajax({
        type: "GET",
        url: "pessoas/?primeiro_nome=&ultimo_nome="
    }).done(function(pessoas) {

        var discipuladores = [];

        for(var i = 0; i < pessoas.length; i++) {
            discipuladores.push({id: pessoas[i].id, nome: pessoas[i].primeiro_nome + ' ' + pessoas[i].ultimo_nome});
        }
        
        discipuladores.unshift("");
        
        $("#jsGrid").jsGrid({

            fields:[
                { type: "text", name: "primeiro_nome", title: "Primeiro Nome", width: 60, filtering: true },
                { type: "text", name: "ultimo_nome", title: "Último Nome", width: 60, filtering: true },
                { 
                    type: "text", 
                    name: "data_nascimento", 
                    title: "Data de Nascimento", 
                    width: 50, 
                    filtering: false,
                    itemTemplate: function(value, item) {
                        //console.log('data_nascimento:itemTemplate => ' + value);
                        return $("<span>")
                        .html(value)
                        .addClass('date')
                        .mask('00/00/0000');
                    },
                    insertTemplate: function() {
                        //console.log('data_nascimento:insertTemplate');
                        return jsGrid.fields.text.prototype.insertTemplate
                        .call(this)
                        .addClass('date')
                        .mask('00/00/0000');
                    },
                    editTemplate: function(value, item) {
                        //console.log('data_nascimento:editTemplate => ' + value);
                        return jsGrid.fields.text.prototype.editTemplate
                        .call(this)
                        .val(value)
                        .addClass('date')
                        .mask('00/00/0000');
                    }
                },
                { 
                    type: "text", 
                    name: "data_batismo", 
                    title: "Data de Batismo", 
                    width: 50, 
                    filtering: false ,
                    itemTemplate: function(value, item) {
                        //console.log('data_batismo:itemTemplate => ' + value);
                        return $("<span>")
                        .html(value)
                        .addClass('date')
                        .mask('00/00/0000');
                    },
                    insertTemplate: function() {
                        //console.log('data_batismo:insertTemplate');
                        return jsGrid.fields.text.prototype.insertTemplate
                        .call(this)
                        .addClass('date')
                        .mask('00/00/0000');
                    },
                    editTemplate: function(value, item) {
                        //console.log('data_batismo:editTemplate => ' + value);
                        return jsGrid.fields.text.prototype.editTemplate
                        .call(this)
                        .val(value)
                        .addClass('date')
                        .mask('00/00/0000');
                    }
                },
                { 
                    type: "text", 
                    name: "telefone", 
                    title: "Telefone", 
                    width: 50, 
                    filtering: false,
                    itemTemplate: function(value, item) {
                        //console.log('telefone:itemTemplate => ' + value);
                        return $("<span>")
                        .html(value)
                        .addClass('cel')
                        .mask('(00) 00000-0000');
                    },
                    insertTemplate: function() {
                        //console.log('telefone:insertTemplate');
                        return jsGrid.fields.text.prototype.insertTemplate
                        .call(this)
                        .addClass('cel')
                        .mask('(00) 00000-0000');
                    },
                    editTemplate: function(value, item) {
                        //console.log('telefone:editTemplate => ' + value);
                        return jsGrid.fields.text.prototype.editTemplate
                        .call(this)
                        .val(value)
                        .addClass('cel')
                        .mask('(00) 00000-0000');
                    }
                },
                { type: "text", name: "email", title: "Email", width: 100, filtering: false },
                { type: "checkbox", name: "e_professor", title: "É Professor", width: 50, filtering: true },
                { 
                    type: "select",
                    name: "discipulador", 
                    title: "Discipulador",
                    width: 100,
                    items: discipuladores, 
                    valueField: "id", 
                    textField: "nome",
                    itemTemplate: function(value, item) {
                        if(typeof value == 'string'){
                            var discipulador = pessoas.find(e => e.id == value);
                            return discipulador.primeiro_nome + ' ' + discipulador.ultimo_nome;
                        }
                    }
                },
                { type: "control"}
            ],

            onItemInserting: function(args) {

                //console.log("onItemInserting");

                var dtaNasc = args.item.data_nascimento.split("/");
                var dtaBat = args.item.data_batismo.split("/");
                var tel = args.item.telefone.replace(new RegExp(/\(|\)|\-|\s/,'g'),'');

                args.item.data_nascimento = dtaNasc[2] + '-' + dtaNasc[1] + '-' + dtaNasc[0];
                args.item.data_batismo = dtaBat[2] + '-' + dtaBat[1] + '-' + dtaBat[0];
                args.item.telefone = tel;

            },

            onItemInserted: function(args) {
                
//                console.log("onItemInserted");

                var dtaNasc = args.item.data_nascimento.split("-");
                var dtaBat = args.item.data_batismo.split("-");
                //var tel = args.item.telefone.replace(new RegExp(/\(|\)|\-|\s/,'g'),'');

                args.item.data_nascimento = dtaNasc[2] + dtaNasc[1] + dtaNasc[0];
                args.item.data_batismo = dtaBat[2] + dtaBat[1] + dtaBat[0];
                //args.item.telefone = tel;

            },

            onItemUpdating: function(args) {

                //console.log("onItemUpdating");

                var dtaNasc = args.item.data_nascimento.split("/");
                var dtaBat = args.item.data_batismo.split("/");
                var tel = args.item.telefone.replace(new RegExp(/\(|\)|\-|\s/,'g'),'');

                args.item.data_nascimento = dtaNasc[2] + '-' + dtaNasc[1] + '-' + dtaNasc[0];
                args.item.data_batismo = dtaBat[2] + '-' + dtaBat[1] + '-' + dtaBat[0];
                args.item.telefone = tel;

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
            controller: {
                loadData: function(filter){
                    return $.ajax({
                        type: "GET",
                        url: "pessoas/",
                        data: filter
                    }).done(function(dados) {
                        
                        for(var i = 0; i < dados.length; i++) {

                            dtaNasc = dados[i].data_nascimento.split("-");
                            dtaBat = dados[i].data_batismo.split("-");
                        
                            dados[i].data_nascimento = dtaNasc[2] + dtaNasc[1] + dtaNasc[0];
                            dados[i].data_batismo = dtaBat[2] + dtaBat[1] + dtaBat[0];
                        
                        }
                    });
                },
                insertItem: function(item) {
                    return $.ajax({
                        type: "POST",
                        url: "pessoas/",
                        data: item
                    });
                },
                updateItem: function(item) {
                    return $.ajax({
                        type: "PUT",
                        url: "pessoas/",
                        data: item
                    });
                },
                deleteItem: function(item) {
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