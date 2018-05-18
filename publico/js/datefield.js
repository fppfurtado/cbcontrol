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

        if (!value || value[0] === '0') return;

        var data, dia, mes, ano;

        data = new Date(value);
        // Ajustando data ao Fuso Horário do sistema
        data.setTime(data.getTime() + data.getTimezoneOffset() * 60 * 1000);

        dia = data.getDate() < 10 ? '0' + data.getDate() : data.getDate();
        mes = data.getMonth() < 10 ? '0' + (data.getMonth() + 1) : data.getMonth() + 1;
        ano = data.getFullYear();

        return dia + '/' + mes + '/' + ano;

    },

    insertTemplate: function (value) {
        return this._insertPicker = $("<input>").datepicker();
    },

    editTemplate: function (value) {
        var data = value.split("-");
        return this._editPicker = $("<input>").datepicker().val(data[2] + '/' + data[1] + '/' + data[0]);
    },

    filterTemplate: function () {
        this._fromPicker = $("<input>").datepicker();
        this._toPicker = $("<input>").datepicker();
        return $("<div>").append(this._fromPicker).append(this._toPicker);
    },

    insertValue: function () {
        var data = this._insertPicker.datepicker("getDate");
        return converterDateParaMysql(data);
    },

    editValue: function () {
        //console.log('editValue');
        var data = this._editPicker.datepicker("getDate");
        //console.log(data.toDateString());
        return converterDateParaMysql(data);

    },

    filterValue: function () {

        var data, from, to, fromDia, fromMes, fromAno, toDia, toMes, toAno;

        from = '';
        to = '';

        if (data = this._fromPicker.datepicker("getDate")) {

            fromDia = data.getDate();
            fromMes = data.getMonth() < 10 ? '0' + (data.getMonth() + 1) : data.getMonth() + 1;
            fromAno = data.getFullYear();

            from = fromAno + '-' + fromMes + '-' + fromDia;

        }

        if (data = this._toPicker.datepicker("getDate")) {

            toDia = data.getDate()
            toMes = data.getMonth() < 10 ? '0' + (data.getMonth() + 1) : data.getMonth() + 1;
            toAno = data.getFullYear();

            to = toAno + '-' + toMes + '-' + toDia;

        }

        return {
            from: from,
            to: to
        };
    }

});

jsGrid.fields.date = MyDateField;

function converterDateParaMysql(data) {

    if(!data) return;

    var dia, mes, ano;
    //console.log(data.toDateString());
    data.setTime(data.getTime() + data.getTimezoneOffset() * 60 * 1000);
    //console.log(data.toDateString());
    dia = data.getDate();
    mes = data.getMonth() < 0 ? '0' + (data.getMonth() + 1) : data.getMonth() + 1;
    ano = data.getFullYear();

    return ano + '-' + mes + '-' + dia;
}