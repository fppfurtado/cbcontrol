var MyDateField = function (config) {
    jsGrid.Field.call(this, config);
};

MyDateField.prototype = new jsGrid.Field({

    css: "date-field",            // redefine general property 'css'
    align: "center",              // redefine general property 'align'

    sorter: function (date1, date2) {
        return new Date(date1) - new Date(date2);
    },

    itemTemplate: function (value) {
        return new Date(value).toDateString();
    },

    insertTemplate: function (value) {
        return this._insertPicker = $("<input>").datepicker({ defaultDate: new Date() });
    },

    editTemplate: function (value) {
        return this._editPicker = $("<input>").datepicker().datepicker("setDate", new Date(value));
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