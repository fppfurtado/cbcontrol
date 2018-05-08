$(function(){

    $.ajax({
        type: "GET",
        url: "pessoas/?primeiro_nome=&ultimo_nome="
    }).done(function(discipuladores) {

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
                        return '<span class="date">' + value + '</span>';
                    }
                },
                { 
                    type: "text", 
                    name: "data_batismo", 
                    title: "Data de Batismo", 
                    width: 50, 
                    filtering: false ,
                    itemTemplate: function(value, item) {
                        return '<span class="date">' + value + '</span>';
                    }
                },
                { 
                    type: "text", 
                    name: "telefone", 
                    title: "Telefone", 
                    width: 50, 
                    filtering: false,
                    itemTemplate: function(value, item) {
                        return '<span class="cel">' + value + '</span>';
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
                    textField: "primeiro_nome",
                    itemTemplate: function(value, item) {
                        if(typeof value == 'string'){
                            var discipulador = discipuladores.find(e => e.id == value);
                            return discipulador.primeiro_nome + ' ' + discipulador.ultimo_nome;
                        }
                    }
                },
                { type: "control"}
            ],
        
            width: "100%",
            height: "100%",
        
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
            },

            onDataLoaded: function() {
                $('.date').mask('00/00/0000');
                $('.cel').mask('(00) 00000-0000')
            },
        
        });        

    });

})