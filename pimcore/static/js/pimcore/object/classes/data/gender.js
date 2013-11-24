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
 * @copyright  Copyright (c) 2009-2010 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.pimcore.org/license     New BSD License
 */

pimcore.registerNS("pimcore.object.classes.data.gender");
pimcore.object.classes.data.gender = Class.create(pimcore.object.classes.data.data, {

    type: "gender",
    /**
     * define where this datatype is allowed
     */
    allowIn: {
        object: true,
        objectbrick: false,
        fieldcollection: false,
        localizedfield: false
    },

    initialize: function (treeNode, initData) {
        this.type = "gender";

        if(!initData["name"]) {
            initData = {
                title: t("gender")
            };
        }

        initData.fieldtype = "gender";
        initData.datatype = "data";
        initData.name = "gender";
        treeNode.setText("gender");

        this.initData(initData);

        this.treeNode = treeNode;
    },

    getTypeName: function () {
        return t("gender");
    },

    getGroup: function () {
        return "crm";
    },

    getIconClass: function () {
        return "pimcore_icon_gender";
    },

    getLayout: function ($super) {

        $super();

        var nameField = this.layout.getComponent("standardSettings").getComponent("name");
        nameField.disable();

        this.specificPanel.removeAll();
        return this.layout;
    }
});
