/**
 * Pimcore
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.pimcore.org/license
 *
 * @copyright  Copyright (c) 2009-2012 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.pimcore.org/license     New BSD License
 */

pimcore.registerNS("pimcore.object.tags.keyValue");
pimcore.object.tags.keyValue = Class.create(pimcore.object.tags.abstract, {

    type: "keyValue",

    initialize: function (data, fieldConfig) {

        this.fieldConfig = fieldConfig;

        var fields = [];

        fields.push("id");
        fields.push("groupName");
        fields.push("key");
        fields.push("keyName");
        fields.push("keyDesc");
        fields.push("value");
        fields.push("translated");
        fields.push("type");
        fields.push("possiblevalues");
        fields.push("inherited");
        fields.push("source");
        fields.push("altSource");
        fields.push("altValue");
        // this.visibleFields = fields;

        this.store = new Ext.data.ArrayStore({
            data: [],
            listeners: {
                remove: function() {
                    this.dataChanged = true;
                }.bind(this),
                clear: function () {
                    this.dataChanged = true;
                }.bind(this),
                update: function(store, record, operation) {
                    this.dataChanged = true;
                    if (this.isDeleteOperation) {
                        // do nothing
                    } else {
                        record.set("inherited", false);
                        if (record.data.type == "translated") {
                            // whoooo, we have to go to the server and ask for a new translation
                            this.translate(record);
                        }
                    }
                }.bind(this)
            },
            fields: fields,
            sortInfo : { field: "key", direction: "ASC" }
        });

        for (var i = 0; i < data.length; i++) {
            var pair = data[i];
            this.store.add(new this.store.recordType(pair));
        }

        this.store.sort("description", "ASC");

        this.store.on("add", function() {
            this.dataChanged = true;
        }.bind(this)
        );
    },

    translate: function(record) {
        Ext.Ajax.request({
            url: "/admin/key-value/translate",
            params: {
                "recordId": record.id,
                "keyId" : record.data.key,
                "objectId" : record.data.o_id,
                "text": record.data.value
            },
            success: this.translationReceived.bind(this),
            failure: function() {
                alert("translation failed");
            }.bind(this)
        });

    },

    translationReceived: function (response) {
        var translation = Ext.decode(response.responseText);
        if (translation.success) {
            var recordId = translation.recordId;
            var record = this.store.getById(recordId);
            if (record.data.value == translation.text) {
                record.set("translated", translation.translated);
            }
        }
    },

    getGridColumnEditor: function(field) {
        var editorConfig = {};

        if (field.config) {
            if (field.config.width) {
                if (intval(field.config.width) > 10) {
                    editorConfig.width = field.config.width;
                }
            }
        }

        if(field.layout.noteditable) {
            return null;
        }

        if (field.layout.gridType == "text" || field.layout.gridType == "translated") {
            return new Ext.form.TextField(editorConfig);
            // }
        } else if (field.layout.gridType == "select") {
            var store = new Ext.data.JsonStore({
                autoDestroy: true,
                root: 'options',
                fields: ['key',"value"],
                data: field.layout
            });

            editorConfig = Object.extend(editorConfig, {
                store: store,
                triggerAction: "all",
                editable: false,
                mode: "local",
                valueField: 'value',
                displayField: 'key'
            });

            return new Ext.form.ComboBox(editorConfig);
        } else if (field.layout.gridType == "number") {
            return new Ext.form.NumberField();
        } else if (field.layout.gridType == "bool") {
            return false;
        }

        return  null;
    },

    isDirty: function()  {
        //console.log(this.dataChanged);
        return this.dataChanged;
    },


    getLayoutEdit: function () {

        var autoHeight = true;

        var gridWidth = 0;
        var gridHeight = 150;
        var keyWidth = 150;
        var descWidth = 300;
        var groupWidth = 200;
        var groupDescWidth = 200;
        var valueWidth = 600;
        var maxHeight = 190;

        if (this.fieldConfig.maxheight > 0) {
            maxHeight = this.fieldConfig.maxheight;
        }

        if (this.fieldConfig.keyWidth) {
            keyWidth = this.fieldConfig.keyWidth;
        }

        if (this.fieldConfig.groupWidth) {
            groupWidth = this.fieldConfig.groupWidth;
        }

        if (this.fieldConfig.groupDescWidth) {
            groupDescWidth = this.fieldConfig.groupDescWidth;
        }



        if (this.fieldConfig.valueWidth) {
            valueWidth = this.fieldConfig.valueWidth;
        }

        if (this.fieldConfig.descWidth) {
            descWidth = this.fieldConfig.descWidth;
        }


        var readOnly = false;
        // css class for editorGridPanel
        var cls = 'object_field';

        var columns = [];

        // var visibleFields = ['key','description', 'value','type','possiblevalues'];
        var visibleFields = ['group', 'groupDesc', 'keyName', 'keyDesc', 'value' /*, 'inherited', 'source' ,'altSource', 'altValue' */];


        for(var i = 0; i < visibleFields.length; i++) {
            var editor = null;
            var editable = false;
            var renderer = null;
            var cellEditor = null;
            var col = visibleFields[i];
            var listeners = null;
            var colWidth = keyWidth;


            if (i == 0) {
                renderer = this.getCellRenderer.bind(this);
                listeners =  {
                    "dblclick": this.keycellMousedown.bind(this)
                };
            }

            if (col == "group") {
                colWidth = groupWidth;
            }

            if (col == "groupDesc") {
                colWidth = groupDescWidth;
            }

            if (col == 'value') {
                colWidth = valueWidth;
                editable = true;
                cellEditor = this.getCellEditor.bind(this);
                renderer = this.getCellRenderer.bind(this);
                listeners =  {
                    "mousedown": this.cellMousedown.bind(this)
                };
            }

            gridWidth += colWidth;

            var columnConfig = {
                header: t("keyvalue_tag_col_" + visibleFields[i]),
                dataIndex: visibleFields[i],
                width: colWidth,
                editor: editor,
                editable: editable,
                renderer: renderer,
                getCellEditor: cellEditor,
                listeners: listeners
            };
            columns.push(columnConfig);
        }


        var actionColWidth = 30;
        if(!readOnly) {
            columns.push({
                xtype: 'actioncolumn',
                width: actionColWidth,
                hideable: false,
                items: [
                    {
                        getClass: function (v, meta, rec) {
                            var klass = "pimcore_action_column";
                            if (!rec.data.inherited) {
                                klass +=  " pimcore_icon_cross";
                            }
                            return klass;

                        },
                        tooltip: t('remove'),
                        // icon: "/pimcore/static/img/icon/cross.png",
                        handler: function (grid, rowIndex) {
                            var store = grid.getStore();
                            var record = store.getAt(rowIndex);
                            var data = record.data;
                            if (data.inherited) {
                                record.set("inherited", false);
                            } else {
                                if (data.altSource) {
                                    this.isDeleteOperation = true;
                                    record.set("inherited", true);
                                    record.set("value", data.altValue);
                                    record.set("source", data.altSource);
                                    this.isDeleteOperation = false;
                                } else {
                                    grid.getStore().removeAt(rowIndex);
                                }

                            }
                        }.bind(this)
                    }
                ]
            });

        }

        gridWidth += actionColWidth;

        var configuredFilters = [
            {
                type: "string",
                dataIndex: "group"
            },
            {
                type: "string",
                dataIndex: "description"
            },
            {
                type: "string",
                dataIndex: "value"
            }
        ];

        // filters
        var gridfilters = new Ext.ux.grid.GridFilters({
            encode: true,
            local: true,
            filters: configuredFilters
        });


        var plugins = [gridfilters];



        this.component = new Ext.grid.EditorGridPanel({
            clicksToEdit: 1,
            store: this.store,
            colModel: new Ext.grid.ColumnModel({
                defaults: {
                    sortable: true
                },
                columns: columns
            }),
            viewConfig: {
                markDirty: false
            },
            cls: cls,
            width: gridWidth,
            stripeRows: true,
            plugins: plugins,
            title: t('keyvalue_tag_title'),
            tbar: {
                items: [
                    {
                        xtype: "tbspacer",
                        width: 20,
                        height: 16
                    },
                    {
                        xtype: "tbtext",
                        text: "<b>" + this.fieldConfig.title + "</b>"
                    },
                    "->",
                    {
                        xtype: "button",
                        iconCls: "pimcore_icon_delete",
                        handler: this.empty.bind(this)
                    },
                    {
                        xtype: "button",
                        iconCls: "pimcore_icon_add",
                        handler: this.openSearchEditor.bind(this)
                    }
                ],
                ctCls: "pimcore_force_auto_width",
                cls: "pimcore_force_auto_width"
            },
            autoHeight: autoHeight,
            maxHeight: 10,
            bodyCssClass: "pimcore_object_tag_objects"
        });

        this.component.on("afteredit", function() {
            this.dataChanged = true;
        }.bind(this));



        return this.component;
    },

    keycellMousedown: function (col, grid, rowIndex, event) {

        var store = grid.getStore();
        var record = store.getAt(rowIndex);
        var data = record.data;


        pimcore.helpers.openObject(data.source, "object");
    },

    cellMousedown: function (col, grid, rowIndex, event) {


        var store = grid.getStore();
        var record = store.getAt(rowIndex);
        var data = record.data;

        var type = data.type;
        // this is used for the boolean field type
        if (type == "bool") {
            record.set("value", !record.data.value);
        }

    },

    getCellRenderer: function (value, metaData, record, rowIndex, colIndex, store) {
        var data = store.getAt(rowIndex).data;
        var type = data.type;

        if (colIndex == 0) {
            if (record.data.inherited) {
                metaData.css += " grid_value_inherited";
            }
        } else {
            if (type == "translated") {
                if (data.translated) {
                    return data.translated;
                }
            } else if (type == "bool") {
                metaData.css += ' x-grid3-check-col-td';
                return String.format('<div class="x-grid3-check-col{0}" style="background-position:10px center;">&#160;</div>', value ? '-on' : '');
            } else if (type == "select") {
                var decodedValues = Ext.util.JSON.decode(data.possiblevalues);
                for (var i = 0;  i < decodedValues.length; i++) {

                    var val = decodedValues[i];
                    if (val.value == value) {
                        return val.key;
                    }
                }
            }
        }

        return value;
    },


    getCellEditor: function (rowIndex) {

        var store = this.store;
        var data = store.getAt(rowIndex).data;
        // var value = data.all;

        var type = data.type;
        var property;

        if (type == "text" || type =="translated") {
            property = new Ext.form.TextField();
        } else if (type == "number") {
            property = new Ext.form.NumberField();
        } else if (type == "bool") {
            property = new Ext.form.Checkbox();
            return false;
        } else if (type == "select") {
            var values = [];
            var possiblevalues = data.possiblevalues;

            var storedata = [];

            var decodedValues = Ext.util.JSON.decode(possiblevalues);
            for (var i = 0;  i < decodedValues.length; i++) {
                var val = decodedValues[i];
                var entry = [val.value , val.key];
                storedata.push(entry);
            }

            property = new Ext.form.ComboBox({
                triggerAction: 'all',
                editable: false,
                mode: "local",
                store: new Ext.data.ArrayStore({
                    id: 0,
                    fields: [
                        'id',
                        'label'
                    ],
                    data: storedata
                }),
                valueField: 'id',
                displayField: 'label'

            });
        }


        return new Ext.grid.GridEditor(property);
    },


    empty: function () {
        this.store.removeAll();
    },

    getLayoutShow: function () {

        this.component = this.getLayoutEdit();
        this.component.disable();

        return this.component;
    },

    getValue: function () {
        var value = [];

        var totalCount = this.store.data.length;

        for (var i = 0; i < totalCount; i++) {
            var record = this.store.getAt(i);
            value.push(record.data);
        }
        return value;
    },

    getName: function () {
        return this.fieldConfig.name;
    },

    openSearchEditor: function () {
        var selectionWindow = new pimcore.object.keyvalue.selectionwindow(this);
        selectionWindow.show();
    },


    handleSelectionWindowClosed: function() {
        // nothing to do
    },

    requestPending: function() {
        // nothing to do
    },

    handleAddKeys: function (response) {
        var data = Ext.decode(response.responseText);

        if(data && data.success) {
            for (var i=0; i < data.data.length; i++) {
                var keyDef = data.data[i];


                var totalCount = this.store.data.length;

                var addKey = true;
                for (var x = 0; x < totalCount; x++) {
                    var record = this.store.getAt(x);
                    if (record.data.key == keyDef.id) {
                        addKey = false;
                        break;
                    }
                }

                if (addKey) {
                    var colData = {};
                    colData.key = keyDef.id;
                    colData.keyName = keyDef.name;
                    colData.type = keyDef.type;
                    colData.possiblevalues = keyDef.possiblevalues;
                    colData.keyDesc = keyDef.description;
                    colData.group = keyDef.groupName;
                    colData.groupDesc = keyDef.groupdescription;
                    this.store.add(new this.store.recordType(colData));
                }
            }
        }
    },

    getGridColumnConfig:function (field) {
        var renderer;
        if (field.layout.gridType == "bool") {
            return new Ext.grid.CheckColumn({
                header:ts(field.label),
                dataIndex:field.key,
                renderer:function (key, value, metaData, record, rowIndex, colIndex, store) {
                    if (record.data.inheritedFields[key] && record.data.inheritedFields[key].inherited == true) {
                        metaData.css += " grid_value_inherited";
                    }
                    metaData.css += ' x-grid3-check-col-td';g
                    return String.format('<div class="x-grid3-check-col{0}">&#160;</div>', value ? '-on' : '');
                }.bind(this, field.key)
            });
        } else if (field.layout.gridType == "translated") {
            renderer = function (key, value, metaData, record) {

                if (record.data["#kv-tr"][key] !== undefined) {
                    return record.data["#kv-tr"][key];
                } else {
                    return value;
                }
            }.bind(this, field.key);
            return {header:ts(field.label), sortable:true, dataIndex:field.key, renderer:renderer,
                editor:this.getGridColumnEditor(field)};
        } else {
            renderer = function (key, value, metaData, record) {
                if (record.data.inheritedFields[key] && record.data.inheritedFields[key].inherited == true) {
                    metaData.css += " grid_value_inherited";
                }

                return value;
            }.bind(this, field.key);

            return {header:ts(field.label), sortable:true, dataIndex:field.key, renderer:renderer,
                editor:this.getGridColumnEditor(field)};

        }
    }


});
