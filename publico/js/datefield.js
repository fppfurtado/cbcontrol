var MyDateField = function (config) {
    jsGrid.Field.call(this, config);
};

$.datepicker.setDefaults({
    dateFormat: 'dd/mm/yy',
    dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
    dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
    monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']
});

MyDateField.prototype = new jsGrid.Field({

    css: "date-field",            // redefine general property 'css'
    align: "center",              // redefine general property 'align'

    sorter: function (date1, date2) {
        return new Date(date1) - new Date(date2);
    },

    itemTemplate: function (value) {
        console.log(value);
        var data, dia, mes, ano;

        data = new Date(value);       
        dia = data.getDate()+1;
        mes = data.getMonth < 10 ? '0' + (data.getMonth()+1) : data.getMonth()+1;
        ano = data.getFullYear();

        return dia + '/' + mes + '/' + ano;
    },

    insertTemplate: function (value) {
        return this._insertPicker = $("<input>").datepicker();
    },

    editTemplate: function (value) {
        return this._editPicker = $("<input>").datepicker();
    },

    filterTemplate: function () {
        this._fromPicker = $("<input>").datepicker();
        this._toPicker = $("<input>").datepicker();
        return $("<div>").append(this._fromPicker).append(this._toPicker);
    },

    insertValue: function () {
        return this._insertPicker.datepicker("getDate").toISOString();
    },

    editValue: function () {
        return this._editPicker.datepicker("getDate").toISOString();
    },

    filterValue: function () {
        return {
            from: this._fromPicker.datepicker("getDate"),
            to: this._toPicker.datepicker("getDate")
        };
    }

});

jsGrid.fields.date = MyDateField;